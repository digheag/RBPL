<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

use App\Repositories\Appointment\AppointmentRepository;

use App\Dto\AppointmentDTO;
use App\Dto\AgentDTO;
use App\Dto\DistrictDTO;
use App\Dto\UserDTO;

class AgentAppointmentTest extends TestCase
{
    use RefreshDatabase;
    protected $agent, $user;
    protected function setUp():void{
        parent::setUp();
        $this->agent = User::factory()->create([
            'role' => 'agent',
        ]);
        $this->actingAs($this->agent);
    }

      public function test_guest_cannot_access_agent_appointment_page(): void
    {
        Auth::logout();
        $response = $this->get(route('agent.appointmentList'));
        $response->assertRedirect(route('login'));
    }

    public function test_agent_can_access_list_appointment_page(): void
    {
        $fakeAppointments = [
        $this->makeAppointmentDTO()
        ];
        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointments) {
        $mock->shouldReceive('getAll')
            ->once()
            ->andReturn($fakeAppointments);
        });
        
        $response = $this->get(route('agent.appointmentList'));

        $response->assertOk();
        $response->assertViewIs('agent.appointment');
        $response->assertViewHas('appointments');
    }
    
    protected function makeAppointmentDTO($isApproved = null, $isAgentApprove = false): AppointmentDTO
    {
        Auth::logout();
        $this->user = User::factory()->create([
            'role' => 'agent',
        ]);
        $this->actingAs($this->user);
        $this->withoutVite();

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

    public function test_agent_can_view_appointment_detail(): void
    {
        $fakeAppointment = $this->makeAppointmentDTO(null, false);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('agent.appointmentDetail', ['id' => 1]));

        $response->assertOk();
        $response->assertViewIs('agent.appointment-detail', ['id' => 1]);
        $response->assertViewHas('appointment', $fakeAppointment);
    }

    public function test_agent_cannot_view_nonexistent_appointment(): void
    {
        $this->mock(AppointmentRepository::class, function ($mock) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(999)
                ->andReturn(null);
        });

        $response = $this->get(route('agent.appointmentDetail', ['id' => 999]));

        $response->assertStatus(404);
    }

    public function test_agent_can_accept_appointment(): void
    {
        $approvedAppointment = $this->makeAppointmentDTO(true);

        $this->mock(AppointmentRepository::class, function ($mock) use ($approvedAppointment) {
            $mock->shouldReceive('approveAppointment')
                ->once()
                ->withArgs(function ($id, $status) {
                    return $id == 1 && $status === true; 
                })
                ->andReturn(true);

            $mock->shouldReceive('getById')
                ->andReturn($approvedAppointment);
        });

        $response = $this->post(route('agent.approveAppointment', ['id' => 1]));

        $response->assertRedirect(route('agent.appointmentDetail', ['id' => 1]));

        $response = $this->get(route('agent.appointmentDetail', ['id' => 1]));

        $response->assertSee('Disetujui');
    }

    public function test_agent_can_view_reschedule_page_when_rejected(): void
    {
        $fakeAppointment = $this->makeAppointmentDTO(null, true);

        $this->mock(AppointmentRepository::class, function ($mock) use ($fakeAppointment) {
            $mock->shouldReceive('getById')
                ->once()
                ->with(1)
                ->andReturn($fakeAppointment);
        });

        $response = $this->get(route('agent.rescheduleAppointment', ['id' => 1]));

        $response->assertOk();
        $response->assertViewIs('agent.reschedule-appointment');
        $response->assertViewHas('appointment', $fakeAppointment);
    }

    public function test_agent_can_reschedule_appointment(): void
    {
        $this->mock(AppointmentRepository::class, function ($mock) {
            $mock->shouldReceive('rescheduleAppointment')
                ->once()
                ->withArgs(function ($id, $dto) {
                    return $id == 1
                        && $dto->isAgentApprove === true  
                        && $dto->isSellerApprove === false
                        && $dto->schedule instanceof \Carbon\Carbon;
                })
                ->andReturn(true);
        });

        $response = $this->post(route('agent.rescheduleAppointmentAction', ['id' => 1]), [
            'schedule' => '2026-04-25 10:00:00'
        ]);

        $response->assertRedirect(route('agent.appointmentDetail', ['id' => 1]));
        $response->assertSessionHas('status', true);
        $response->assertSessionHas('message', 'Berhasil Reschedule');
    }

    public function test_agent_failed_reschedule_appointment(): void
    {
        $this->mock(AppointmentRepository::class, function ($mock) {
            $mock->shouldReceive('rescheduleAppointment')
                ->once()
                ->andReturn(false);
        });

        $response = $this->post(route('agent.rescheduleAppointmentAction', ['id' => 1]), [
            'schedule' => '2026-04-25 10:00:00'
        ]);

        $response->assertRedirect(route('agent.appointmentDetail', ['id' => 1]));
        $response->assertSessionHas('status', false);
        $response->assertSessionHas('message', 'Gagal Reschedule');
    }

}
