<?php

namespace App\Repositories\Appointment;

use App\Dto\AppointmentDTO;
use App\Dto\DistrictDTO;
use App\Dto\AgentDTO;
use App\Dto\AppointmentScheduleDTO;
use App\Dto\UserDTO;
use App\Models\Appoinment;
use App\Models\Appoinment_schedule;
use Carbon\Carbon;

class EloquentAppointmentRepository implements AppointmentRepository
{
    public function getAll(): array
    {
        $appointments = Appoinment::with([
            "agent",
            "seller",
            "appoinment_schedules",
            "district"
        ])->get();

        $mappedAppointments = $appointments->map(function ($item) {
            return $this->mapAppointmentEloquentToDTO($item);
        });

        return $mappedAppointments->toArray();
    }

    public function getBySellerId($sellerId): array
    {
        $appointments = Appoinment::with([
            "agent",
            "seller",
            "appoinment_schedules",
            "district"
        ])
            ->where("seller_id", $sellerId)
            ->get();

        $mappedAppointments = $appointments->map(function ($item) {
            return $this->mapAppointmentEloquentToDTO($item);
        });

        return $mappedAppointments->toArray();
    }

    public function getByAgentId($agentId): array
    {
        $appointments = Appoinment::with([
            "agent",
            "seller",
            "appoinment_schedules",
            "district"
        ])
            ->where("agent_id", $agentId)
            ->get();

        $mappedAppointments = $appointments->map(function ($item) {
            return $this->mapAppointmentEloquentToDTO($item);
        });

        return $mappedAppointments->toArray();
    }

    public function getById($id): ?AppointmentDTO
    {
        try {
            $appointment = Appoinment::with([
                "agent",
                "seller",
                "appoinment_schedules",
                "district"
            ])->findOrFail($id);

            $convertedAppointment = $this->mapAppointmentEloquentToDTO($appointment);

            return $convertedAppointment;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rescheduleAppointment($appointmentId, AppointmentScheduleDTO $appointmentSchedule): bool
    {
        try {
            Appoinment_schedule::create([
                "appointment_id" => $appointmentId,
                "schedule" => $appointmentSchedule->schedule,
                'is_agen_approve_schedule' => $appointmentSchedule->isAgentApprove,
                'is_seller_approve_schedule' => $appointmentSchedule->isSellerApprove
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function approveAppointment($appointmentId, $isApproveByAgent): bool
    {
        try {
            $appointment = Appoinment::find($appointmentId);
            $appointmentSchedule = Appoinment_schedule::where("appointment_id", $appointmentId)
                ->latest()
                ->first();

            $appointment->actual_time_schedule = Carbon::parse($appointmentSchedule->schedule);

            if ($isApproveByAgent) {
                $appointmentSchedule->is_agen_approve_schedule = true;
            } else {
                $appointmentSchedule->is_seller_approve_schedule = true;
            }

            $appointment->save();
            $appointmentSchedule->save();

            return true;
        } catch (\Exception $e) {
            dd($e);
            return false;
        }
    }

    private function mapAppointmentEloquentToDTO(Appoinment $appointmentEloquent)
    {
        $appointmentSchedules = $appointmentEloquent->appoinment_schedules->map(function ($appointmentSchedule) {
            $dto = new AppointmentScheduleDTO();

            $dto->id = $appointmentSchedule->id;
            $dto->schedule = Carbon::parse($appointmentSchedule->schedule);
            $dto->isAgentApprove = $appointmentSchedule->is_agen_approve_schedule;
            $dto->isSellerApprove = $appointmentSchedule->is_seller_approve_schedule;
            $dto->createdAt = Carbon::parse($appointmentSchedule->created_at);
            $dto->updatedAt = Carbon::parse($appointmentSchedule->updated_at);


            return $dto;
        });

        /* // fake */
        /* $dto = new AppointmentScheduleDTO(); */
        /* $dto->id = "schedule-1"; */
        /* $dto->schedule = Carbon::now()->addDays(1); */
        /* $dto->isAgentApprove = true; */
        /* $dto->isSellerApprove = null; */
        /* $dto->createdAt = Carbon::now()->subDays(2); */
        /* $dto->updatedAt = Carbon::now(); */
        /**/
        /* $appointmentSchedules = collect([ */
        /*     $dto */
        /* ]); */

        $appointmentSchedules = $appointmentSchedules->sortByDesc(fn($s) => $s->id);

        $dto = new AppointmentDTO();

        $dto->id = $appointmentEloquent->id;

        $dto->agent = new AgentDTO();
        $dto->agent->id = $appointmentEloquent->agent->id;
        $dto->agent->userId = $appointmentEloquent->agent->user_id;
        $dto->agent->username = $appointmentEloquent->agent->user->username;
        $dto->agent->fullname = $appointmentEloquent->agent->user->fullname;
        $dto->agent->email = $appointmentEloquent->agent->user->email;
        $dto->agent->telpNumber = $appointmentEloquent->agent->user->telp_number;
        $dto->agent->profile = $appointmentEloquent->agent->user->profile;
        $dto->agent->updatedAt = Carbon::parse($appointmentEloquent->agent->updated_at);
        $dto->agent->createdAt = Carbon::parse($appointmentEloquent->agent->created_at);

        $dto->seller = new UserDTO();
        $dto->seller->id = $appointmentEloquent->seller->id;
        $dto->seller->username = $appointmentEloquent->seller->username;
        $dto->seller->fullname = $appointmentEloquent->seller->fullname;
        $dto->seller->email = $appointmentEloquent->seller->email;
        $dto->seller->telpNumber = $appointmentEloquent->seller->telp_number;
        $dto->seller->profile = $appointmentEloquent->seller->profile;
        $dto->seller->updatedAt = Carbon::parse($appointmentEloquent->seller->updated_at);
        $dto->seller->createdAt = Carbon::parse($appointmentEloquent->seller->created_at);

        $dto->district = new DistrictDTO();
        $dto->district->postalCode = $appointmentEloquent->district->postal_code;
        $dto->district->name = $appointmentEloquent->district->name;
        $dto->district->updatedAt = Carbon::parse($appointmentEloquent->district->updated_at);
        $dto->district->createdAt = Carbon::parse($appointmentEloquent->district->created_at);

        $dto->appointmentSchedules = $appointmentSchedules->values()->toArray();
        $dto->propertyName = $appointmentEloquent->property_name;
        $dto->propertyAddress = $appointmentEloquent->property_address;

        if ($appointmentEloquent->actual_time_schedule) {
            $dto->actualTimeSchedule = Carbon::parse($appointmentEloquent->actual_time_schedule);
        } else {
            $dto->actualTimeSchedule = null;
        }

        $dto->isApprovedByAgent = $appointmentEloquent->is_approved_by_agent;
        $dto->createdAt = Carbon::parse($appointmentEloquent->created_at);
        $dto->updatedAt = Carbon::parse($appointmentEloquent->updated_at);

        return $dto;
    }
}
