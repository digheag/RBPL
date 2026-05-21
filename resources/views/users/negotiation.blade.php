@extends('layouts.users')

@section('content')
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
    <div class="w-full flex flex-col gap-9">
        <h1 class="text-[24px] font-bold text-[var(--color-text)]">Riwayat Negosiasi</h1>
            <div class="w-full flex flex-col gap-9">
                @foreach($negotiations as $negotiation)
                @php
                    if ($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 1) {
                        $status = 'approved';
                    } else if($negotiation->is_agen_approve === 0 && $negotiation->is_seller_approve === 0) {
                        $status = 'rejected';
                    } else if($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 0) {
                        $status = 'rejected';
                    } else {
                        $status = 'pending';
                    }
                @endphp
                    <article class="bg-[#1E1E1E] flex justify-between items-center px-[16px] py-[16px] rounded-xl">
                        <div class="flex items-center gap-6">
                            <div>
                                <p class="text-[16px] text-[var(--color-text)] pb-[8px]">ID Negosiasi: {{ $negotiation->id }}</p>
                                <h2 class="text-[24px] font-bold">{{$negotiation->property->name}}</h2>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            @include('components.common.negotiation-status', [
                                'type' => $status
                            ])
                            <a href="{{ route('negotiation.detail', ['id' => $negotiation->id]) }}">
                                <i class="fa-solid fa-angle-right text-[24px]"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

    <div class="w-full flex flex-col gap-9 mt-[32px]">
        <h1 class="text-[32px] font-bold text-[var(--color-text)]">Menunggu persetujuan</h1>
        @foreach($negotiations as $negotiation)
        @if($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === null && $negotiation->seller_id === Auth::id())
           <article class="bg-[#1E1E1E] flex justify-between items-center px-[16px] py-[16px] rounded-xl">
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-[16px] text-[var(--color-text)] pb-[8px]">ID Negosiasi: {{ $negotiation->id }}</p>
                        <h2 class="text-[24px] font-bold">{{$negotiation->property->name}}</h2>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                        'type' => 'pending'
                    ])
                    <a href="{{ route('negotiation.detail', ['id' => $negotiation->id]) }}">
                        <i class="fa-solid fa-angle-right text-[24px]"></i>
                    </a>
                </div>
            </article>
            @endif
            @endforeach
    </div>
</div>
@endsection