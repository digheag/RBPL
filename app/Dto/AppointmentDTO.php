<?php

namespace App\Dto;

use App\Enums\AppointmentScheduleStatus;
use Carbon\Carbon;

class AppointmentDTO
{
    public string $id;
    public AgentDTO $agent;
    public UserDTO $seller;
    public DistrictDTO $district;
    public array $appointmentSchedules;
    public string $propertyName;
    public string $propertyAddress;
    public ?Carbon $actualTimeSchedule;
    public ?bool $isApprovedByAgent;
    public Carbon $updatedAt;
    public Carbon $createdAt;

    public function getAppointmentScheduleStatus(): AppointmentScheduleStatus
    {
        if ($this->actualTimeSchedule) {
            return AppointmentScheduleStatus::APPROVED;
        }

        if ($this->appointmentSchedules[0]->isAgentApprove) {
            return AppointmentScheduleStatus::WAITING_APPROVE_USER;
        }

        return AppointmentScheduleStatus::WAITING_APPROVE_AGENT;
    }
}
