@extends('layouts/detail')
@section('content')
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">

    <div class="self-stretch text-center text-[var(--color-text)] text-[40px] font-bold">
        Modern Building House
    </div>

    <div class="self-stretch text-[var(--color-highlight)] text-[32px] font-bold">
        Harga Jual: Rp. 500.000.000,00
    </div>

    @php 
        $fields = [
            ['type' => 'text','name' => 'negotiation_price', 'label' => 'Harga Negosiasi' ],
            ['type' => 'textarea','name' => 'negotiation_message', 'label' => 'Alasan']
        ];
    @endphp

    <form method="POST">
     @csrf
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full">
            @foreach ($fields as $field)
                @include("components/admin/field")
            @endforeach
            
            {{-- button submit --}}
            <div class="self-stretch">
                @include('components.common.button', [
                    'type' => 'submit',
                    'slot' => 'Kirim Negosiasi'
                    ])
            </div>
        </div>
    </form>

</div>
@endsection