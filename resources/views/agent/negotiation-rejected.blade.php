@extends('layouts.agent')

@section('content')
<div class="w-[1280px] p-[80px] mx-auto flex flex-col justify-center items-start gap-16 bg-black text-[#EFECE3] font-[Poppins]">

    <div class="w-full flex justify-between items-center">
        <div class="flex items-center gap-8">
            <a href="{{ url('/agent/history-negotiation') }}">
                <img src="/img/arrow.png"
                     class="w-3 h-6 object-contain -scale-x-100"
                     alt="Back">
            </a>
        </div>

        <h1 class="text-[48px] font-bold text-[#EFECE3]">
            Detail Negosiasi
        </h1>

        <div class="w-12 h-12 rounded-full bg-zinc-400 flex items-center justify-center text-white text-[10px] font-normal tracking-wider uppercase">
            MK
        </div>
    </div>

    <div class="w-[1120px] px-[80px] py-[56px] flex flex-col items-start gap-8 rounded-tr-[20px] rounded-br-[20px] rounded-bl-[20px]
        shadow-[inset_4px_0px_3px_rgba(59,104,255,0.28),0px_4px_4px_rgba(59,104,255,0.10)] backdrop-blur-[1px]">

        <h2 class="text-[48px] font-bold text-[#EFECE3]">
            Modern Building House
        </h2>

        <div class="w-full flex flex-col gap-6">

            <div class="w-full flex justify-between items-center">
                <p class="text-[24px] font-bold text-[#EFECE3]">Nama Properti</p>
                <p class="text-[24px] font-medium text-[#EFECE3]">Modern Boarding House</p>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-[24px] font-bold text-[#EFECE3]">Harga Properti</p>
                <p class="text-[24px] font-medium text-[#EFECE3]">Rp. 500.000.000</p>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-[24px] font-bold text-[#EFECE3]">Nama Pemilik</p>
                <p class="text-[24px] font-medium text-[#EFECE3]">Rizki Pratama</p>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-[24px] font-bold text-[#EFECE3]">Nama Agent</p>
                <p class="text-[24px] font-medium text-[#EFECE3]">Rizki Pratama</p>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-[24px] font-bold text-[#EFECE3]">Harga Negosiasi</p>
                <p class="text-[24px] font-medium text-[#EFECE3]">Rp. 450.000.000</p>
            </div>

            <div class="w-full flex justify-between items-start">
                <p class="text-[24px] font-bold text-[#EFECE3]">Catatan</p>
                <p class="text-[24px] font-medium text-[#EFECE3] text-right max-w-[540px]">
                    Pelanggan menawar dengan harga yang lebih rendah.
                </p>
            </div>

        </div>

        @include('components.common.negotiation-status', [
            'type' => 'rejected'
        ])

        <p class="text-[24px] font-medium text-[#F375C2]">
            Penawaran tidak sesuai dengan harga pasar
        </p>

    </div>

    

</div>
@endsection