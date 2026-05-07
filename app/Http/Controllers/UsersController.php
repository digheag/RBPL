<?php

namespace App\Http\Controllers;

use App\Dto\AppointmentScheduleDTO;
use App\Models\Agent;
use App\Models\Agent_regency;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Appoinment_schedule;
use App\Models\Appoinment;
use App\Models\Regency;
use App\Models\District;
use App\Repositories\Appointment\AppointmentRepository;
use Carbon\Carbon;
use Termwind\Components\Raw;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    ) {}

    public function property(Request $request)
    {
        $search = $request->input('search');
        $properties = Property::with('property_image')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

        return view('users.property',[
            'properties' => $properties,
            'search' => $search,
        ]);
    }

    public function propertyDetail($id)
    {
        $property = Property::with([
            'property_image',
            'facilities',
            'spesification',
            'appoinment'
        ])->find($id);

        $regencyId = $property->appoinment->district->regency->id;
        $agent = Agent_regency::with([
            'agent.user'
        ])
            ->where('regency_id', $regencyId)
            ->inRandomOrder()
            ->first();

        return view('users/detail-property', [
            'link' => route("users.property"),
            'title' => "Detail Property",
            'property' => $property,
            'agent' => $agent,
        ]);
    }

    public function propertyAction(Request $request)
    {
        session([
            'propertyId' => $request->input('property_id'),
            'agentId' => $request->input('agent_id'),
        ]);

        return redirect()->route('users.transactionMethod');
    }

    public function chooseAgent(Request $request)
    {
        $search = $request->input('search');
        $locationFilter = $request->input('location');

        $agentQuery = Agent::with(['user', 'agentRegency.regency'])
            ->whereHas("user", function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%');
            });

        if ($locationFilter) {
            $agentQuery->whereHas('agentRegency.regency', function ($q) use ($locationFilter) {
                $q->where('regency_id', $locationFilter);
            });
        }

        $agents = $agentQuery->get();

        $regencies = Regency::orderBy('name')->get();

        return view('users/choose-agent', [
            "link" => '/users/property',
            "title" => 'Pilih Agen Properti',
            'currentStep' => 1,
            'agents' => $agents,
            'regencies' => $regencies,
            'searchQuery' => $search,
        ]);
    }

    public function chooseAgentAction(Request $request)
    {
        // ambil agent id dari input hidden di agent card
        $agentId = $request->input('agent_id');

        // set session supaya nanti agentId bisa diambil waktu insert appointment
        session([
            'agentId' => $agentId
        ]);

        // redirect ke step ke-2(appointment)
        return redirect()->route('users.appointment');
    }

    public function appoinment()
    {
        if (!session()->has('agentId')) {
            return redirect()->route('users.chooseAgent');
        }

        $agent = Agent::find(6);

        // ambil semua districts untuk ngisi dropdown
        $districts = District::all();

        return view('users/appoinment', [
            "link" => '/users/choose/agent',
            "title" => 'Jadwal Pertemuan',
            'agent' => $agent,
            'currentStep' => 2,
            'districts' => $districts,
        ]);
    }

    public function appoinmentPost(Request $request)
    {
        // kita butuh data agentId, sellerId, districtId, propertyName, propertyAddress, dll
        $schedule = $request->input('actual_time_schedule');
        if (!session()->has('agentId')) {
        return redirect()->route('users.chooseAgent');
        }

        // get session
        // ngambil agentId dari session
        $agentId = session()->get("agentId");
        $sellerId = Auth::user()->id;
        $namaProperti = $request->input('property_name');
        $alamatProperti = $request->input('property_address');
        $districtId = $request->input('district_id');

        $appoinment = Appoinment::create([
            'agent_id' => $agentId,
            'seller_id' => $sellerId,
            'district_id' => $districtId,
            'property_name' => $namaProperti,
            'property_address' => $alamatProperti,
            'is_approved_by_agen' => null,
        ]);
        $id = $appoinment->id;

        Appoinment_schedule::create([
            'schedule' => $schedule,
            'is_agen_approve_schedule' => null,
            'is_seller_approve_schedule' => null,
            'appointment_id' => $id,
        ]);
        return redirect()->route('users.review')->with('appoinment_id', $id);
    }

    public function review()
    {
        $appoinmentId = session('appoinment_id');
        $appoinment = Appoinment::with([
            'agent',
            'appoinment_schedules' => function ($q) {
                $q->latest()->limit(1);
            }
        ])->findorFail($appoinmentId);
        $latestSchedule = $appoinment->appoinment_schedules->first();
        return view('users/review', [
            "link" => '/users/appoinment',
            "title" => 'Review Jadwal',
            'currentStep' => 3,
            'href' => route('users.listAppoinment'),
            'id' => null,
            'slot' => "Lihat Janji Temu",
            'appoinment' => $appoinment,
            'schedule' => $latestSchedule,
        ]);
    }

    public function listAppoinment()
    {
        $sellerId = Auth::user()->id;

        $appointments = $this->appointmentRepository->getBySellerId($sellerId);

        return view('users/listAppoinment', [
            'appoinments' => $appointments,

        ]);
    }

    public function appoinmentDetail($appoinmentId)
    {
        $appointment = $this->appointmentRepository->getById($appoinmentId);

        return view('users/appoinmentDetail', [
            "link" => route('users.listAppoinment'),
            "title" => 'Detail Negosiasi',
            'appoinment' => $appointment,
        ]);
    }

    public function appoinmentDetailPost($appoinmentId, Request $request)
    {
        $status = $request->input('status');
        $appoinment = Appoinment::find($appoinmentId);
        if ($status == '0') {
            $appoinment->is_approved_by_agen = 0;
        } else if ($status == '1') {
            $appoinment->is_approved_by_agen = 1;
        } else if ($status == '2') {
            $appoinment->is_approved_by_agen = null;
        }
        $appoinment->save();
    }

    public function rescheduleAppointment($id)
    {
        $appointment = $this->appointmentRepository->getbyId($id);

        if (!$appointment) {
            abort(404);
        }

        return view("users.reschedule-appointment", [
            "link" => route("users.AppoinmentDetail", $id),
            "title" => "Atur Ulang Pertemuan",
            "appointment" => $appointment
        ]);
    }

    public function rescheduleAppointmentAction($id, Request $request)
    {
        $schedule = $request->input("schedule");

        $appointmentScheduleDto = new AppointmentScheduleDTO();

        $appointmentScheduleDto->schedule = Carbon::parse($schedule);
        $appointmentScheduleDto->isAgentApprove = false;
        $appointmentScheduleDto->isSellerApprove = true;

        $rescheduleResult = $this->appointmentRepository->rescheduleAppointment(
            $id,
            $appointmentScheduleDto
        );

        if ($rescheduleResult) {
            return redirect()
                ->route("users.AppoinmentDetail", $id)
                ->with([
                    "status" => true,
                    "message" => "Berhasil Reschedule"
                ]);
        } else {
            return redirect()
                ->route("users.AppoinmentDetail", $id)
                ->with([
                    "status" => false,
                    "message" => "Gagal Reschedule"
                ]);
        }
    }

    public function approveAppointment($appointmentId)
    {
        $approveResult = $this->appointmentRepository->approveAppointment($appointmentId, false);

        if ($approveResult) {
            return redirect()
                ->route("users.AppoinmentDetail", $appointmentId)
                ->with([
                    "status" => true,
                    "message" => "Berhasil Menyetujui Appointment"
                ]);
        } else {
            return redirect()
                ->route("users.AppoinmentDetail", $appointmentId)
                ->with([
                    "status" => false,
                    "message" => "Gagal Menyetujui Appointment"
                ]);
        }
    }
}
