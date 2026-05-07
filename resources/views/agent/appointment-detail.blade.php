@php
    use App\Enums\AppointmentScheduleStatus;
@endphp

@extends("layouts/agent")

@section('content')
    @include("components/users/header")

    <main>
        <section class="px-[80px]">
            <div class="py-[60px] px-[80px] border-2 border-[#1E1E1E] rounded-xl">
                <h1 class="text-4xl font-bold">Detail Janji Temu</h1>
                <ul class="pt-[2rem] grid grid-cols-3 gap-6">
                    <li>
                        <h1 class="font-bold text-2xl">Nama Properti</h1>
                        <p class="text-2xl mt-2">{{ $appointment->propertyName }}</p>
                    </li>
                    <li>
                        <h1 class="font-bold text-2xl">Nama Agen</h1>
                        <p class="text-2xl mt-2">{{ $appointment->agent->fullname }}</p>
                    </li>
                    <li>
                        <h1 class="font-bold text-2xl">Nama Pemilik</h1>
                        <p class="text-2xl mt-2">{{ $appointment->seller->fullname }}</p>
                    </li>
                    <li>
                        <h1 class="font-bold text-2xl">Alamat Properti</h1>
                        <p class="text-2xl mt-2">{{ $appointment->propertyAddress }}</p>
                    </li>
                    <li>
                        <h1 class="font-bold text-2xl">Kecamatan</h1>
                        <p class="text-2xl mt-2">{{ $appointment->district->name }}</p>
                    </li>
                    <li>
                        <h1 class="font-bold text-2xl"> Waktu Temu</h1>
                        <p class="text-2xl mt-2">{{ $appointment->appointmentSchedules[0]->schedule->translatedFormat('d F Y, H:i'); }}</p>
                    </li>
                </ul>

                <div class="mt-6">
                    @if($appointment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_AGENT)
                        <div class="flex gap-[32px] items-center">
                            <div class="bg-[var(--color-secondary)] inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
                                Belum Diproses
                            </div>

                            <p class="text-2xl font-medium text-[var(--color-highlight)]">Menunggu Persetujuan Agen</p>
                        </div>
                    @elseif($appointment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::APPROVED)
                        <div class="bg-[var(--color-primary)] inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
                            Disetujui
                        </div>
                        <div class="my-[32px]">
                            @include('components.common.button',[
                                'href' => route('agent.createProperty', $appointment->id),
                                'id' => "#",
                                'slot' => "Tambah Properti"

                            ])
                        </div>
                    @else

                        <div class="flex gap-[32px] items-center">
                            <div class="bg-[var(--color-secondary)] inline-block px-[24px] py-[8px] rounded-[8px] border border-lg">
                                Belum Diproses
                            </div>

                            <p class="text-2xl font-medium text-[var(--color-highlight)]">Menunggu Persetujuan Pemilik</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        @if($appointment->getAppointmentScheduleStatus() == AppointmentScheduleStatus::WAITING_APPROVE_AGENT)
            <section class="px-[80px] pt-[1rem]">
                <ul class="flex gap-[2rem]">
                    <li class="flex-1">
                    <form method="post" action="{{ route("agent.approveAppointment", $appointment->id) }}">
                    @csrf
                        <button type="submit" class="bg-gradient-to-r from-[#0560E8] to-[#7000FF] rounded-lg p-[1px] w-full flex justify-center items-center" href="">
                            <span class="w-full flex justify-center bg-[#1E1E1E] items-center py-4 rounded-lg text-[#F375C2]">
                                Setuju Janji Temu
                            </span>
                        </button>
                    </form>
                    </li>

                    <li class="flex-1">
                        <a class="bg-gradient-to-r from-[#0560E8] to-[#7000FF] rounded-lg p-[1px] w-full flex justify-center items-center" href="{{ route("agent.rescheduleAppointment", $appointment->id) }}">
                            <span class="w-full flex justify-center bg-[var(--color-bg)] items-center py-4 rounded-lg text-[#F375C2]">
                                Tolak & Atur Ulang Jadwal
                            </span>
                        </a>
                    </li>
                </ul>
            </section>
        @endif
    </main>
@endsection
