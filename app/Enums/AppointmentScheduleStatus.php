<?php

namespace App\Enums;

enum AppointmentScheduleStatus
{
    case WAITING_APPROVE_AGENT;
    case WAITING_APPROVE_USER;
    case APPROVED;
}
