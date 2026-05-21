@extends("layouts/detail")
@section('content')
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
    <h1 class="text-[24px] font-bold text-[var(--color-text)] text-center">{{ $negotiation->property->name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full">
        <h1 class="text-[20px] font-semibold text-[var(--color-text)]">ID Negosiasi: {{ $negotiation->id }}</h1>
        <h1 class="text-[20px] font-semibold text-[var(--color-text)]">Harga Jual: {{ $negotiation->property->price }}</h1>
        <h1 class="text-[20px] font-semibold text-[var(--color-text)]">Harga Negosiasi: {{ $negotiation->offer_price }}</h1>
    </div>

    <form action="{{ route('users.rejectNegotiation', $negotiation->id) }}" method="POST">
        @csrf
        @method ('PATCH')
        @php
            $fields = [
                ['type' => 'text','name' => 'rejection_reason', 'label' => 'Alasan Penolakan']
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full">
            @foreach ($fields as $field)
                @include("components/admin/field")
            @endforeach

            @include("components.common.button", [
                'type' => 'submit',
                'id' => NULL,
                'slot' => 'Tolak Negosiasi'
            ])
        </div>
        
    </form>
</div>
@endsection