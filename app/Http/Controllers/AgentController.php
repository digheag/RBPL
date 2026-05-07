<?php

namespace App\Http\Controllers;

use App\Dto\AppointmentScheduleDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Property;
use App\Models\Property_image;
use App\Repositories\Appointment\AppointmentRepository;
use Carbon\Carbon;

class AgentController extends Controller
{
    public function __construct(private AppointmentRepository $appointmentRepository) {}

    public function appointment()
    {
        $appointments = $this->appointmentRepository->getAll();

        return view("agent/appointment", [
            "appointments" => $appointments
        ]);
    }

    public function appointmentDetail($id)
    {
        $appointment = $this->appointmentRepository->getById($id);

        if (!$appointment) {
            abort(404);
        }

        return view("agent/appointment-detail", [
            "link" => route("agent.appointmentList"),
            "title" => "Detail Pertemuan",
            "appointment" => $appointment
        ]);
    }

    public function approveAppointment($appointmentId)
    {
        $approveResult = $this->appointmentRepository->approveAppointment($appointmentId, true);

        if ($approveResult) {
            return redirect()
                ->route("agent.appointmentDetail", $appointmentId)
                ->with([
                    "status" => true,
                    "message" => "Berhasil Menyetujui Appointment"
                ]);
        } else {
            return redirect()
                ->route("agent.appointmentDetail", $appointmentId)
                ->with([
                    "status" => false,
                    "message" => "Gagal Menyetujui Appointment"
                ]);
        }
    }

    public function rescheduleAppointment($id)
    {
        $appointment = $this->appointmentRepository->getbyId($id);

        if (!$appointment) {
            abort(404);
        }

        return view("agent.reschedule-appointment", [
            "link" => route("agent.appointmentDetail", $id),
            "title" => "Atur Ulang Pertemuan",
            "appointment" => $appointment
        ]);
    }

    public function rescheduleAppointmentAction($appointmentId, Request $request)
    {
        $schedule = $request->input("schedule");

        $appointmentScheduleDto = new AppointmentScheduleDTO();

        $appointmentScheduleDto->schedule = Carbon::parse($schedule);
        $appointmentScheduleDto->isAgentApprove = true;
        $appointmentScheduleDto->isSellerApprove = false;

        $rescheduleResult = $this->appointmentRepository->rescheduleAppointment(
            $appointmentId,
            $appointmentScheduleDto
        );

        if ($rescheduleResult) {
            return redirect()
                ->route("agent.appointmentDetail", $appointmentId)
                ->with([
                    "status" => true,
                    "message" => "Berhasil Reschedule"
                ]);
        } else {
            return redirect()
                ->route("agent.appointmentDetail", $appointmentId)
                ->with([
                    "status" => false,
                    "message" => "Gagal Reschedule"
                ]);
        }
    }

    public function createProperty($id)
    {
        $appointment = $this->appointmentRepository->getbyId($id);
        return view("agent/create-property",[
            'link' => route("agent.appointmentDetail", $id),
            "title" => "Publikasi Property",
            'appointment' => $appointment
        ]);
    }

    public function propertyStore(Request $request, $appointmentId){
        $request->merge([
            'propertyPrice' => str_replace('.', '', $request->propertyPrice)
        ]);

        $validate = $request->validate([
            'propertyName' => 'required|string|max:255',
            'propertyAddress' => 'required|string',
            'propertyPrice' => 'required|numeric|min:0',
            'propertyArea' => 'required|numeric|min:0',
            'sold' => 'nullable',
            'description' => 'nullable|string|max:100',

            // array field
            'spesification' => 'nullable|array',
            'spesification.*' => 'nullable|string',

            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string',

            // gambar
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $property = Property::create([
        'name' => $validate['propertyName'],
        'address' => $validate['propertyAddress'],
        'price' => $validate['propertyPrice'],
        'area_in_hectare' => $validate['propertyArea'],
        'sold_date' => $validate['sold'] ?? null,
        'description' => $validate['description'],
        'appoinment_id' => $appointmentId
        ]); 

        if($request->hasFile('images')){
            foreach($request->file('images') as $index => $image){
                $path = $image->store('properties', 'public');
               $property->Property_image()->create([
                    'name' => $image->getClientOriginalName(),
                    'url' => $path,
                    'is_banner' => $index === 0 ? 1 : 0
                ]);
            }
        }
        return redirect()->route('agent.property');
    }

    public function property(Request $request)
    {
        $search = $request->input('search');
        $properties = Property::with('property_image')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

        return view("agent/property",[
            'properties' => $properties,
            'search' => $search,
        ]);
    }

    public function detailProperty($id)
    {
        $property = Property::with([
            'property_image',
            'facilities',
            'spesification',
            'appoinment'
        ])->find($id);

        return view("agent/detail-property", [
            'link' => route("agent.property"),
            'title' => "Detail Property",
            'property' => $property
        ]);
    }

    public function editProperty($id){
        $property = Property::with([
            'property_image',
            'facilities',
            'spesification',
            'appoinment'
        ])->find($id);
        return view("agent/edit-property",[
            'link' => route("agent.detailProperty", $property->id),
            'title' => "Edit Property",
            'property' => $property
        ]);
    }

    public function updateProperty(Request $request, $id){
        $request->merge([
            'propertyPrice' => str_replace('.', '', $request->propertyPrice)
        ]);

        $validate = $request->validate([
            'propertyName' => 'required|string|max:255',
            'propertyAddress' => 'required|string',
            'propertyPrice' => 'required|numeric|min:0',
            'propertyArea' => 'required|numeric|min:0',
            'sold' => 'nullable',
            'description' => 'nullable|string|max:1000',

            // array field
            'spesification' => 'nullable|array',
            'spesification.*' => 'nullable|string',

            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string',

            // gambar
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $property = Property::findOrFail($id);

        $property->update([
            'name' => $validate['propertyName'],
            'address' => $validate['propertyAddress'],
            'price' => $validate['propertyPrice'],
            'area_in_hectare' => $validate['propertyArea'],
            'sold_date' => $validate['sold'] ?? null,
            'description' => $validate['description'],
        ]);

        // replace spesifikasi
        $property->spesification()->delete();
        foreach ($validate['spesification'] ?? [] as $spec) {
            $property->spesification()->create(['description' => $spec]);
        }

        // replace fasilitas
        $property->facilities()->delete();
        foreach ($validate['fasilitas'] ?? [] as $fac) {
            $property->facilities()->create(['description' => $fac]);
        }

        if($request->hasFile('images')){
            foreach($request->file('images') as $index => $image){
                $path = $image->store('properties', 'public');

                $property->property_image()->create([
                    'name' => $image->getClientOriginalName(),
                    'url' => $path,
                    'is_banner' => $index === 0 ? 1 : 0
                ]);
            }
        }

        return redirect()->route('agent.detailProperty', $property->id)
        ->with('success', 'Property berhasil diupdate');
    }

    public function deleteProperty($id)
    {
        $property = Property::findOrFail($id);

        $property->spesification()->delete();
        $property->facilities()->delete();
        $property->property_image()->delete();
        $property->delete();

        return redirect()->route('agent.property')
            ->with('success', 'Property berhasil dihapus');
    }

    public function publication($id)
    {
        return view("agent/publication-property");
    }

    public function offer()
    {
        return view("agent/offer");
    }
}
