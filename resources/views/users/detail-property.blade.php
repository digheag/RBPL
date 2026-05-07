@extends('layouts/detail')
@section("content")
<div class="flex flex-cols2 content-between gap-[24px] mx-[80px] my-[24px] items-center">
    <img src="{{ asset($property->property_image->first()->url) }}" alt="building" class="w-[500px] h-[350px] rounded-[16px]">
    <div class="gap-[16px]">
        <div class="flex flex-col gap-[16px]">
            @if($property->sold_date)
                <span class="inline-block w-fit px-[8px] py-[8px] border-2 rounded-[8px] text-[var(--color-highlight)] font-semibold border-transparent"
                style="background: linear-gradient(var(--color-surface)) padding-box, 
                var(--btn-gradient2) border-box">
                Terjual
                </span>
            @else 
                <span class="inline-block w-fit px-[8px] py-[8px] border-2 rounded-[8px] text-[var(--color-text)] font-semibold border-transparent"
                style="background: linear-gradient(var(--color-surface)) padding-box, 
                var(--btn-gradient2) border-box">
                    Tersedia
                </span>
            @endif
            <h1 class="text-[var(--color-text)] font-bold text-[32px]">{{ $property->name }}</h1>
        </div>
        <p class="text-[var(--color-text)] font-normal text-[24px]">{{ $property->address }}, {{ $property->appoinment->district->regency->province->name }},{{ $property->appoinment->district->regency->name }}, {{ $property->appoinment->district->name }}</p>
        <p class="text-[var(--color-text)] font-semibold text-[24px]">Rp {{ number_format($property->price, 0, ',', '.') }}</p>
    </div>
</div>

{{-- buat gambar yang kecil kecil --}}
<div class="mx-[80px] flex flex-row gap-[24px] overflow-x-auto scrollbar-hide">
    @foreach ($property->property_image as $image)
        <img src="{{ asset($image->url) }}" alt="" class="w-[240px] h-[200px] rounded-[16px]">
   @endforeach
</div>

<div class="flex justify-between mx-[80px] my-[24px] pb-[250px]">
    <div class="flex flex-col gap-[16px]">
        <h1 class="text-[var(--color-text)] font-bold text-[24px]">Spesifikasi</h1>
        @foreach ($property->spesification as $spec)
            <div class="flex flex-row gap-[8px]">
                <img src="/img/star-icon.png" alt="" class="w-[26px] aspect-square">
                <p class="text-[var(--color-text)] font-normal text-[18px]">{{ $spec->description }}</p>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col gap-[16px]">
        <h1 class="text-[var(--color-text)] font-bold text-[24px]">Fasilitas</h1>
        @foreach ($property->facilities as $facility)
            <div class="flex flex-row gap-[8px]">
                <img src="/img/feather-icon.png" alt="" class="w-[26px] aspect-square">
                <p class="text-[var(--color-text)] font-normal text-[18px]">{{ $facility->description }}</p>
            </div>
        @endforeach
    </div>
</div>
<form action="{{ route('users.propertyAction') }}" method="get">
    <input type="hidden" name="agent_id" value="{{ $agent->agent->id }}">
    <input type="hidden" name="property_id" value="{{ $property->id }}">
    @include("components.users.floatingCardAgent", ['agent' => $agent])
</form>
@endsection