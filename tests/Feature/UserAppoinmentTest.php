<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Agent;
use App\Models\District;
use App\Models\Regency;
use App\Models\Province;
use App\Models\Appoinment;
use App\Models\Appoinment_schedule;
use App\Repositories\Appointment\AppointmentRepository;
use App\Dto\AppointmentDTO;
use App\Dto\AgentDTO;
use App\Dto\DistrictDTO;
use App\Dto\UserDTO;

class UserAppoinmentTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected function setUp():void{
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => 'users',
        ]);
        $this->actingAs($this->user);
        $this->withoutVite();
    }

    protected function makeAppointmentDTO($isApproved = null, $isAgentApprove = false): AppointmentDTO
    {
        $fakeAppointment = new AppointmentDTO();
        $fakeAppointment->id = 1;
        $fakeAppointment->propertyName = 'Rumah B';
        $fakeAppointment->propertyAddress = 'Jl. Test No.1';
        $fakeAppointment->actualTimeSchedule = $isApproved ? now() : null;
        $fakeAppointment->appointmentSchedules = [
        (object)[
            'isAgentApprove' => $isAgentApprove,
            'schedule' => now(),
        ]
        ];
        $fakeAppointment->isApprovedByAgent = $isApproved;
        $fakeAppointment->updatedAt = now();
        $fakeAppointment->createdAt = now();


        $fakeAgent = new AgentDTO();
        $fakeAgent->userId = '1';
        $fakeAgent->fullname = 'Agent A';
        $fakeAppointment->agent = $fakeAgent;

        $fakeSeller = new UserDTO();
        $fakeSeller->id = $this->user->id;
        $fakeSeller->fullname = 'Seller A';
        $fakeAppointment->seller = $fakeSeller;

        $fakeDistrict = new DistrictDTO();
        $fakeDistrict->name = 'Depok';
        $fakeAppointment->district = $fakeDistrict;
        return $fakeAppointment;
    }


    public function test_user_can_create_appointment(): void
    {
        $province = Province::factory()->create();
        $regency = Regency::factory()->create([
            'province_id' => $province->id,
        ]);
        $district = District::factory()->create([
            'regency_id' => $regency->id,
        ]);    
        $agent = Agent::factory()->create();
        session(['agentId' => $agent->id]);

        $response = $this->post(route('users.appointmentAction'), [
            'property_name' => 'Rumah A',
            'property_address' => 'Jl. Test',
            'district_id' => $district->id,
            'actual_time_schedule' => '2026-04-25 10:00:00',
        ]);

        $response->assertRedirect(route('users.review'));

        // cek appointment ke DB
        $this->assertDatabaseHas('appoinments', [
        'agent_id' => $agent->id,
        'seller_id' => $this->user->id,
        'district_id' => $district->id,
        'property_name' => 'Rumah A',
        'property_address' => 'Jl. Test',
        ]);

        // cek schedule ke DB
        $this->assertDatabaseHas('appoinment_schedules', [
            'schedule' => '2026-04-25 10:00:00',
        ]);
    }

    public function test_user_cannot_create_appointment_without_agent(): void
    {
        $this->actingAs($this->user);

        $district = District::factory()
            ->for(Regency::factory()->for(Province::factory()))
            ->create();

        $response = $this->post(route('users.appointmentAction'), [
            'property_name' => 'Rumah A',
            'property_address' => 'Jl. Test',
            'district_id' => $district->id,
            'actual_time_schedule' => '2026-04-25 10:00:00',
        ]);

        $response->assertRedirect(route('users.chooseAgent'));
        $this->assertDatabaseCount('appoinments', 0);
    }

    //helper 
    protected function createAppointmentWithSchedule()
    {
        $province = Province::factory()->create();
        $regency = Regency::factory()->create([
            'province_id' => $province->id,
        ]);
        $district = District::factory()->create([
            'regency_id' => $regency->id,
        ]);

        $agent = Agent::factory()->create();

        $appointment = Appoinment::factory()->create([
            'agent_id' => $agent->id,
            'seller_id' => $this->user->id,
            'district_id' => $district->id,
        ]);

        $schedule = Appoinment_schedule::factory()->create([
            'appointment_id' => $appointment->id,
        ]);

        return [$appointment, $schedule];
    }

    public function test_user_can_view_review_page(): void
    {
        [$appointment, $schedule] = $this->createAppointmentWithSchedule();

        session(['appoinment_id' => $appointment->id]);

        $response = $this->get(route('users.review'));

        $response->assertOk();
        $response->assertViewIs('users.review');

        $response->assertViewHas('appoinment', function ($data) use ($appointment) {
            return $data->id === $appointment->id;
        });

        $response->assertViewHas('schedule');
    }

    public function test_user_cannot_view_review_without_session(): void
    {
        $response = $this->get(route('users.review'));
        $response->assertStatus(404);
    }

    public function test_user_can_access_list_appointment_page(): void
    {
        $fakeAppointments = collect([
        (object)[
            'id' => 1,
            'propertyName' => 'Rumah A'
        ]
        ]);
        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointments) {
        $mock->shouldReceive('getBySellerId')
            ->once()
            ->andReturn($fakeAppointments);
        });

        $response = $this->get(route('users.listAppoinment'));
        $response->assertOk();
        $response->assertViewIs('users.listAppoinment');
        $response->assertViewHas('appoinments', $fakeAppointments);
    }

    public function test_user_cannot_access_list_appoinment_page():void{
        Auth::logout();
        $response = $this->get(route('users.listAppoinment'));
        $response->assertRedirect(route('login'));
    }

    
    public function test_user_can_view_appointment_with_unapproved_agent():void{
        $fakeAppointment = $this->makeAppointmentDTO(null, false);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('users.AppoinmentDetail', ['id' => 1]));

        $response->assertOk();
        $response->assertViewIs('users.appoinmentDetail');
        $response->assertViewHas('appoinment', $fakeAppointment);
        $response->assertSee('Menunggu Persetujuan Agen');
    }

    public function test_appointment_status_is_not_processed_when_waiting_approve():void{
        $fakeAppointment = $this->makeAppointmentDTO(null, false);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('users.AppoinmentDetail', ['id' => 1]));

        $response->assertOk();
        $response->assertSee('Belum Diproses');
    }

    public function test_appointment_status_is_accepted_when_agent_approves(): void
    {
        $fakeAppointment = $this->makeAppointmentDTO(true);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('users.AppoinmentDetail', ['id' => 1]));

        $response->assertOk();
        $response->assertSee('Disetujui');
    }

    public function test_appointment_status_waiting_for_user_approval(): void
    {
        $fakeAppointment = $this->makeAppointmentDTO(null, true);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('users.AppoinmentDetail', ['id' => 1]));

        $response->assertOk();

       //cek teks utama
        $response->assertSee('Menunggu Persetujuan Anda');

        //cek button user muncul
        $response->assertSee('Tolak janji temu');
        $response->assertSee('Setujui janji temu');
    }

    public function test_user_can_accept_reschedule(): void
    {
        $approvedAppointment = $this->makeAppointmentDTO(true); //sesudah approve

        $this->mock(AppointmentRepository::class, function ($mock) 
        use ($approvedAppointment) {
             $mock->shouldReceive('approveAppointment')
            ->once()
            ->withArgs(function ($id, $status) {
                return $status === false;
            });

            $mock->shouldReceive('getById')
                ->andReturn($approvedAppointment);
        });

        $response = $this->post(route('users.approveAppointment', ['id' => 1]));

        $response->assertRedirect(route('users.AppoinmentDetail', ['id' => 1]));

        $response = $this->get(route('users.AppoinmentDetail', ['id' => 1]));

        $response->assertSee('Disetujui');
    }

    public function test_user_redirected_to_reschedule_page_when_reject(): void
    {
        $fakeAppointment = $this->makeAppointmentDTO(null, true);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('users.rescheduleAppointment', ['id' => 1]));

        $response->assertOk();
        $response->assertViewIs('users.reschedule-appointment');
    }

    public function test_user_can_reschedule_appointment(): void
    {
        $this->mock(AppointmentRepository::class, function ($mock) {
            $mock->shouldReceive('rescheduleAppointment')
                ->once()
                ->withArgs(function ($id, $dto) {
                    return $id == 1
                        && $dto->isAgentApprove === false
                        && $dto->isSellerApprove === true
                        && $dto->schedule instanceof \Carbon\Carbon;
                })
                ->andReturn(true);
        });

        $response = $this->post(route('users.rescheduleAppointmentAction', ['id' => 1]), [
            'schedule' => '2026-04-25 10:00:00'
        ]);

        $response->assertRedirect(route('users.AppoinmentDetail', ['id' => 1]));
        $response->assertSessionHas('status', true);
        $response->assertSessionHas('message', 'Berhasil Reschedule');
    }

    public function test_user_failed_reschedule_appointment(): void
    {
        $this->mock(AppointmentRepository::class, function ($mock) {
            $mock->shouldReceive('rescheduleAppointment')
                ->once()
                ->andReturn(false);
        });

        $response = $this->post(route('users.rescheduleAppointmentAction', ['id' => 1]), [
            'schedule' => '2026-04-25 10:00:00'
        ]);

        $response->assertRedirect(route('users.AppoinmentDetail', ['id' => 1]));
        $response->assertSessionHas('status', false);
        $response->assertSessionHas('message', 'Gagal Reschedule');
    }
    
}
