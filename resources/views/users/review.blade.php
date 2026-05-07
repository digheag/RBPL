@extends("layouts/property")
@section("content")
<div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl m-[80px] p-[56px] pb-[32px]">
    <div class = "flex flex-col2 place-content-between">
        <div class = "flex flex-col gap-[24px]">
        <p class="text-[18px] text-[var(--color-text)] font-semibold">Appoinment ID </p>
        <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama Agent </p>
        <p class="text-[18px] text-[var(--color-text)] font-semibold">Tanggal Pertemuan</p>
        </div>
        <div class = "flex flex-col gap-[24px]">
            <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->id }}</p>
            <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $appoinment->agent->user->fullname }}</p>
            <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $schedule->schedule }}</p>
        </div>
    </div>
    
    <div class="pt-[32px] pb-[16px]">
        @include("components/common/button")
    </div>
</div>
@endsection