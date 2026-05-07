@extends("layouts/users")
@section("content")
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
    <h1 class="text-[26px] font-bold text-[var(--color-text)]">Daftar Pertemuan</h1>
    @foreach ($appoinments as $appoinment)
    <div class="p-[1px] bg-gradient-to-r from-[#0E21A0] via-[#B153D7] to-[#4D2FB2] rounded-xl">
        <article class="bg-[var(--color-surface)] flex justify-between items-center p-[16px] rounded-xl">
            <div>
                <p class="text-[18px] text-[var(--color-text)] pb-[8px]">{{ $appoinment->actualTimeSchedule ?? 'No schedule set' }}</p>
                <h2 class="text-[22px] font-semibold">{{ $appoinment->propertyName }}</h2>
                <p class="text-[16px] text-[var(--color-text)]">{{ $appoinment->agent->user->fullname ?? 'tidak ada nama'}}</p>
            </div>
            <a href="{{ route('users.AppoinmentDetail', ['id' => $appoinment->id]) }}">
                <i class="fa-solid fa-angle-right text-4xl"></i>
            </a>
        </article>
    </div>
    @endforeach
</div>
@endsection
