<?php

namespace App\Repositories\Appointment;

use App\Dto\AppointmentDTO;
use App\Dto\AppointmentScheduleDTO;

interface AppointmentRepository
{
    public function getAll(): array;

    public function getByAgentId($agentId): array;

    public function getById($id): ?AppointmentDTO;

    public function rescheduleAppointment($appointmentId, AppointmentScheduleDTO $appointmentSchedule): bool;

    public function approveAppointment($appointmentId, $isApproveByAgent): bool;
}
