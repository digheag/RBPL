@extends('layouts.agent')

@section('content')
    @include("components/common/navbar")
<div class="w-full flex justify-center bg-black">
    <div class="w-[1280px] p-[80px] flex flex-col items-start gap-16">

        <div class="w-[1120px] px-[80px] py-[56px] flex flex-col items-start gap-8 rounded-tr-[20px] rounded-br-[20px] rounded-bl-[20px]
            shadow-[inset_4px_0px_3px_rgba(59,104,255,0.28),0px_4px_4px_rgba(59,104,255,0.10)] backdrop-blur-[1px]">

            <h1 class="self-stretch text-[48px] font-bold text-[#EFECE3]">
                Riwayat Negosiasi
            </h1>

            {{-- Pending --}}
            <div class="w-full px-4 bg-[#1E1E1E] rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-[#0E21A0] flex justify-between items-center">
                <div class="h-24 flex items-center">
                    <div class="flex flex-col justify-center">
                        <p class="text-base font-medium text-[#EFECE3]">
                            ID Negosiasi: N00
                        </p>
                        <p class="text-2xl font-bold text-[#EFECE3]">
                            Modern Boarding House
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                        'type' => 'pending'
                    ])

                    <a href="{{ url('/agent/negotiation-pending') }}">
                        <img src="/img/arrow.png" class="w-6 h-6" alt="Detail">
                    </a>
                </div>
            </div>

            {{-- Approved --}}
            <div class="w-full px-4 bg-[#1E1E1E] rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-[#0E21A0] flex justify-between items-center">
                <div class="h-24 flex items-center">
                    <div class="flex flex-col justify-center">
                        <p class="text-base font-medium text-[#EFECE3]">
                            ID Negosiasi: N01
                        </p>
                        <p class="text-2xl font-bold text-[#EFECE3]">
                            Modern Boarding House
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                        'type' => 'approved'
                    ])

                    <img src="/img/arrow.png" class="w-6 h-6" alt="Detail">
                    
                </div>
            </div>

            {{-- Rejected --}}
            <div class="w-full px-4 bg-[#1E1E1E] rounded-lg shadow-[0px_4px_4px_rgba(0,0,0,0.25)] border border-[#0E21A0] flex justify-between items-center">
                <div class="h-24 flex items-center">
                    <div class="flex flex-col justify-center">
                        <p class="text-base font-medium text-[#EFECE3]">
                            ID Negosiasi: N02
                        </p>
                        <p class="text-2xl font-bold text-[#EFECE3]">
                            Modern Boarding House
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @include('components.common.negotiation-status', [
                        'type' => 'rejected'
                    ])

                    <a href="{{ url('/agent/negotiation-rejected') }}">
                        <img src="/img/arrow.png" class="w-6 h-6" alt="Detail">
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection