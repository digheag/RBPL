<?php

namespace App\Repositories\Appointment;

use App\Dto\AppointmentDTO;
use App\Dto\DistrictDTO;
use App\Dto\AgentDTO;
use App\Dto\AppointmentScheduleDTO;
use App\Dto\UserDTO;
use Carbon\Carbon;

class AppointmentRepositoryImpl implements AppointmentRepository
{
    private function fakeData(): array
    {
        // User / Agent
        $agent = new AgentDTO();
        $agent->id = "agent-1";
        $agent->userId = "user-1";
        $agent->username = "agent_keren";
        $agent->fullname = "Budi Santoso";
        $agent->email = "budi@example.com";
        $agent->telpNumber = "08123456789";
        $agent->profile = "agent.jpg";
        $agent->createdAt = Carbon::now()->subDays(10);
        $agent->updatedAt = Carbon::now();

        $seller = new UserDTO();
        $seller->id = "seller-1";
        $seller->username = "seller_keren";
        $seller->fullname = "Farhan Ramadhana";
        $seller->email = "farhan@example.com";
        $seller->telpNumber = "081234567890";
        $seller->profile = "profile.jpg";
        $seller->createdAt = Carbon::now()->subDays(7);
        $seller->updatedAt = Carbon::now();

        // District
        $district = new DistrictDTO();
        $district->id = "district-1";
        $district->postalCode = "55792";
        $district->name = "Bantul";
        $district->createdAt = Carbon::now()->subMonths(1);
        $district->updatedAt = Carbon::now();

        // Schedules
        $schedule1 = new AppointmentScheduleDTO();
        $schedule1->id = "schedule-1";
        $schedule1->schedule = Carbon::now()->addDays(1);
        $schedule1->isAgentApprove = true;
        $schedule1->isSellerApprove = null;
        $schedule1->createdAt = Carbon::now()->subDays(2);
        $schedule1->updatedAt = Carbon::now();

        $schedule2 = new AppointmentScheduleDTO();
        $schedule2->id = "schedule-2";
        $schedule2->schedule = Carbon::now()->addDays(3);
        $schedule2->isAgentApprove = true;
        $schedule2->isSellerApprove = true;
        $schedule2->createdAt = Carbon::now()->subDays(1);
        $schedule2->updatedAt = Carbon::now();

        // Appointment
        $appointment = new AppointmentDTO();
        $appointment->id = "1";
        $appointment->agent = $agent;
        $appointment->district = $district;
        $appointment->seller = $seller;
        $appointment->appointmentSchedules = [$schedule1, $schedule2];
        $appointment->propertyName = "Rumah Minimalis";
        $appointment->propertyAddress = "Jl. Malioboro No. 1";
        $appointment->actualTimeSchedule = $schedule2->schedule;
        $appointment->isApprovedByAgent = true;
        $appointment->createdAt = Carbon::now()->subDays(5);
        $appointment->updatedAt = Carbon::now();

        return [$appointment];
    }

    public function getAll(): array
    {
        return $this->fakeData();
    }

    public function getByAgentId(): array
    {
        // sementara semua data pakai agent yang sama
        return $this->fakeData();
    }

    public function getById(): ?AppointmentDTO
    {
        $data = $this->fakeData();
        return $data[0] ?? null;
    }

    public function rescheduleAppointment($appointmentId, AppointmentScheduleDTO $appointmentSchedule): bool
    {
        return true;
    }

    public function approveAppointment($appointmentId, $isApproveByAgent): bool
    {
        return true;
    }
}
