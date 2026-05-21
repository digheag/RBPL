@extends('layouts.detail')
@section('content')
    <div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
        <h1 class="text-[26px] font-bold text-[var(--color-text)]">{{ $negotiation->property->name }}</h1>
        <div class = "flex flex-col gap-[24px]">
            <div class="flex justify-between items-center">
                <p class="text-[18px] text-[var(--color-text)] font-semibold">Harga properti </p>
                <p class="text-[18px] text-[var(--color-text)] font-normal">Rp {{ number_format($negotiation->property->price, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama Pemilik</p>
                <p class="text-[18px] text-[var(--color-text)] font-normal">{{ $negotiation->seller->fullname }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[18px] text-[var(--color-text)] font-semibold">Nama Agent </p>
                <p class="text-[18px] text-[var(--color-text)] font-normal">{{  $negotiation->agen->user->fullname  }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[18px] text-[var(--color-text)] font-semibold">Harga Negosiasi </p>
                <p class="text-[18px] text-[var(--color-text)] font-normal">Rp {{ number_format($negotiation->offer_price, 0, ',', '.') }}</p>
            </div>
        </div>
                @php
                    if ($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 1) {
                        $status = 'approved';
                    } else if($negotiation->is_agen_approve === 0 && $negotiation->is_seller_approve === 0) {
                        $status = 'rejected';
                    } else if($negotiation->is_agen_approve === 0) {
                        $status = 'rejected';
                    } elseif($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 0) {
                        $status = 'rejected';
                    }else {
                        $status = 'pending';
                    }
                @endphp

        @if($negotiation->is_agen_approve === null && $negotiation->is_seller_approve === null)
            <div class="w-full flex gap-4 items-center">
                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                            'type' => $status
                    ])
                </div>
                <p class="text-[18px] text-[var(--color-highlight)] font-semibold">Menunggu persetujuan agen </p>
            </div>
        @elseif($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === null)
            <div class="w-full flex gap-4 items-center">
                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                            'type' => $status
                    ])
                </div>
                <p class="text-[18px] text-[var(--color-highlight)] font-semibold">Menunggu persetujuan pemilik </p>
            </div>
        @elseif($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 1)
            <div class="w-full">
                @include('components.common.button', [
                    'href' => route('users.negotiationTransaction'),
                    'slot' => 'Lakukan pembayaran'
                ])
            </div>
        @elseif($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === 0)
            <div class="w-full flex gap-4 items-center">
                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                            'type' => $status
                    ])
                </div>
                <p class="text-[18px] text-[var(--color-highlight)] font-semibold">{{ $negotiation->description }}</p>
            </div>
            <div class="w-full">
                @include('components.common.button', [
                    'href' => url('/users/transaction/method'),
                    'slot' => 'Ajukan negosiasi ulang'
                ])
            </div>
        @else
                <div class="w-full flex gap-4 items-center">
                    <div class="flex items-center gap-4">
                        @include('components.common.negotiation-status', [
                                'type' => $status
                        ])
                    </div>
                    <p class="text-[18px] text-[var(--color-highlight)] font-semibold">{{ $negotiation->description }}</p>
                </div>
                <div class="w-full">
                    @include('components.common.button', [
                        'href' => url('/users/transaction/method'),
                        'slot' => 'Ajukan negosiasi ulang'
                    ])
                </div>
        @endif

        @if($negotiation->is_agen_approve === 1 && $negotiation->is_seller_approve === null && $negotiation->seller_id === Auth::id())
        <div class="w-full flex gap-4">
                    <div class="w-full">
                        <form action="{{ route('users.approveNegotiation', ['id' => $negotiation->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                                @include('components.common.button', [
                                    'type' => 'submit',
                                    'slot' => 'Terima Negosiasi'
                                ])
                        </form>
                    </div>
                    <div class="w-full">
                        <form action="{{ route('users.rejectNegotiation', ['id' => $negotiation->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            @include('components.common.errorBtn', [
                                'type' => 'submit',
                                'slot' => 'Tolak Negosiasi'
                            ])
                        </form>
                    </div>
                </div>
        @endif
    </div>
@endsection
    