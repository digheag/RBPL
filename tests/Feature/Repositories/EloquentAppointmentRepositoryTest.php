<?php

namespace Tests\Feature\Repositories;

use App\Dto\AppointmentDTO;
use App\Dto\AppointmentScheduleDTO;
use App\Enums\AppointmentScheduleStatus;
use App\Models\Agent;
use App\Models\Appoinment;
use App\Models\Appoinment_schedule;
use App\Models\District;
use App\Models\User;
use App\Repositories\Appointment\EloquentAppointmentRepository;
use Carbon\Carbon;
use Database\Seeders\AgentSeeder;
use Database\Seeders\AppoinmentSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentAppointmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EloquentAppointmentRepository $eloquentAppointmentRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->eloquentAppointmentRepository = new EloquentAppointmentRepository();
    }

    public function testGetAll(): void
    {
        $seeders = [
            new UserSeeder(),
            new AgentSeeder(),
            new LocationSeeder(),
            new AppoinmentSeeder()
        ];

        foreach ($seeders as $seeder) {
            $seeder->run();
        }

        $results = $this->eloquentAppointmentRepository->getAll();


        $this->assertCount(5, $results);
        $this->assertInstanceOf(AppointmentDTO::class, $results[0]);
    }

    public function testGetById()
    {
        $data = $this->seedAppointmentForTest();

        $appointmentId = $data[3][0]->id;

        $result = $this->eloquentAppointmentRepository->getById($appointmentId);

        $this->assertInstanceOf(AppointmentDTO::class, $result);
        $this->assertEquals($appointmentId, $result->id);
    }

    public function testGetByAgentId()
    {
        $data = $this->seedAppointmentForTest();
        $agentId = $data[0]->id;

        $results = $this->eloquentAppointmentRepository->getByAgentId($agentId);

        $this->assertCount(3, $results);
        $this->assertInstanceOf(AppointmentDTO::class, $results[0]);

        foreach ($results as $res) {
            $this->assertEquals($agentId, $res->agent->id);
        }
    }

    public function testGetBySellerId()
    {
        $data = $this->seedAppointmentForTest();
        $sellerId = $data[1]->id;

        $results = $this->eloquentAppointmentRepository->getBySellerId($sellerId);

        $this->assertCount(3, $results);
        $this->assertInstanceOf(AppointmentDTO::class, $results[0]);

        foreach ($results as $res) {
            $this->assertEquals($sellerId, $res->seller->id);
        }
    }

    public function testRescheduleAppointment()
    {
        $data = $this->seedAppointmentForTest();

        $appointmentId = $data[3][count($data[3]) - 1]->id;

        $appointmentScheduleDto = new AppointmentScheduleDTO();
        $appointmentScheduleDto->schedule = Carbon::now();
        $appointmentScheduleDto->isAgentApprove = true;
        $appointmentScheduleDto->isSellerApprove = false;

        $this->eloquentAppointmentRepository->rescheduleAppointment($appointmentId, $appointmentScheduleDto);

        $result = $this->eloquentAppointmentRepository->getById($appointmentId);

        $this->assertEquals(null, $result->actualTimeSchedule);
        $this->assertTrue($result->appointmentSchedules[0]->isAgentApprove);
        $this->assertFalse($result->appointmentSchedules[0]->isSellerApprove);
        $this->assertTrue($result->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_USER);
    }

    public function testApproveAppointment()
    {
        $data = $this->seedAppointmentForTest();

        $appointmentId = $data[3][count($data[3]) - 1]->id;

        $this->eloquentAppointmentRepository->approveAppointment($appointmentId, true);

        $result = $this->eloquentAppointmentRepository->getById($appointmentId);

        $this->assertNotNull($result->actualTimeSchedule);
        $this->assertEquals($result->getAppointmentScheduleStatus(), AppointmentScheduleStatus::APPROVED);
    }

    private function seedAppointmentForTest(): array
    {
        $agent = Agent::factory()->create();
        $seller = User::factory()->create();

        $locationSeeder = new LocationSeeder();
        $locationSeeder->run();

        $district = District::first();

        $appointments = Appoinment::factory()
            ->count(3)
            ->create([
                'agent_id' => $agent->id,
                'seller_id' => $seller->id,
                'district_id' => $district->id,
                'actual_time_schedule' => null, // pastikan ada schedule
            ])
            ->each(function ($appointment) {
                Appoinment_schedule::factory()->create([
                    'appointment_id' => $appointment->id,
                    'schedule' => Carbon::now(),
                    'is_agen_approve_schedule' => false,
                    'is_seller_approve_schedule' => true,
                ]);
            });
        return [$agent, $seller, $district, $appointments];
    }
}
