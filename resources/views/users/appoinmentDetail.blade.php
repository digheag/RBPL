@php
    use App\Enums\AppointmentScheduleStatus;
@endphp

@extends("layouts/detail")
@section("content")
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
    <h1 class="text-[26px] font-bold text-[var(--color-text)]">Detail Pertemuan</h1>
    <div class = "flex flex-col3 place-content-between">
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama properti </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->propertyName }}</p>
        </div>
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama Agen </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->agent->fullname }}</p>
        </div>
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama pemilik </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->seller->fullname }}</p>
        </div>
    </div>
    <div class = "flex flex-col3 place-content-between">
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">Alamat properti </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->propertyAddress }}</p>
        </div>
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">Kecamatan </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->district->name }} </p>
        </div>
        <div class="flex flex-col gap-[8px]">
             <p class="text-[18px] text-[var(--color-text)] font-semibold">waktu temu </p>
             <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->actualTimeSchedule ?? 'No schedule set' }} </p>

            @if($appoinment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_AGENT)
                <p class="text-[18px] text-[var(--color-text)] font-normal">Ajuan anda {{ $appoinment->appointmentSchedules[0]->schedule }}</p>
                <p class="text-[18px] text-[var(--color-text)] font-normal">Menunggu Persetujuan Agen</p>
             @endif
        </div>
    </div>

    <div class="mt-6">
    @if($appoinment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::APPROVED)
        <div class="bg-[var(--color-secondary)] text-white inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
            Disetujui
        </div>
    @elseif($appoinment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_AGENT)
        <div class="flex gap-[32px] items-center">
            <div class="bg-[var(--color-secondary)] text-white inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
                Belum Diproses
            </div>

            <p class="text-2xl font-medium text-[var(--color-highlight)]">Menunggu Persetujuan Agen</p>
        </div>
    @else
        <div class="flex gap-[32px] items-center">
            <div class="bg-[var(--color-secondary)] text-white inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
                Belum Diproses
            </div>

            <p class="text-2xl font-medium text-[var(--color-highlight)]">Menunggu Persetujuan Anda</p>
        </div>
    @endif
    </div>
</div>

@if($appoinment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_USER)

<section class="px-[80px] pt-[1rem]">
    <ul class="flex gap-2">
        <li class="flex-1">
            <form action="{{ route('users.rescheduleAppointment', ['id' => $appoinment->id])}}" method="get">
                @include('components.common.button', [
                    'type' => 'submit',
                    'id' => '#',
                    'slot' => 'Tolak janji temu',
                ]);
                </form>
                </li>

                <li class="flex-1">
                <form action="{{ route('users.approveAppointment', ['id' => $appoinment->id])}}" method="post">
                @csrf
                @include('components.common.button', [
                    'type' => 'submit',
                    'id' => '#',
                    'slot' => 'Setujui janji temu',
                ]);
                </form>
                </li>
            </ul>
        </section>
@endif
@endsection
