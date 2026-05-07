<?php

namespace App\Dto;

use Carbon\Carbon;

class AppointmentScheduleDTO
{
    public string $id;
    public Carbon $schedule;
    public ?bool $isAgentApprove;
    public ?bool $isSellerApprove;
    public Carbon $updatedAt;
    public Carbon $createdAt;
}
