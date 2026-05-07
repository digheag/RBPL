@extends("layouts/detail")
@section("content")
<div class="px-[80px] text-[18px] text-center text-[var(--color-text)] mt-2">
    <p>Silahkan menghubungi agent terkait dibawah ini  untuk melanjutkan transaksi
    </p>
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
</div>
<div class="p-[80px] bg-black">
    <div class="w-full bg-[#1E1E1E] border border-[#0E21A0] rounded-2xl p-6 flex flex-col gap-[16px] justify-between">
        <div class="flex gap-[32px] justify-center">
            <div>
                <img src="{{ asset($agent->user->profile) }}" alt="" class="rounded-[16px] w-[500px] h-[300px]">
            </div>
            <div>
                <h1 class="text-[var(--color-text)] font-[var(--fw-bold)] text-[32px] text-center">{{ $agent->user->fullname }}</h1>
                <h1 class="text-[var(--color-text)] mb-[16px] font-regular text-[20px] text-center">{{ $agent->user->role }}</h1>

                <div class="w-full flex text-[var(--color-text)] gap-[8px] items-center pb-[8px] mt-[32px]">
                    <img src="/img/call.png" alt="call icon" class="w-[32px] object-cover">
                    <span class="text-[var(--color-text)] text-[24px]">{{ $agent->user->telp_number }}</span>
                </div>
                <div class="w-full flex text-[var(--color-text)] gap-[8px] mb-[16px] items-center">
                    <img src="/img/location.png" alt="location icon" class="w-[32px] object-cover" >
                    <span class="text-[var(--color-text)] text-[24px]">{{ $agent->agentRegency->flatten()->pluck('regency.name')->filter()->unique()->implode(', ') }}</span>
                </div>
            </div>

        </div>
        <form action="{{ route('users.DirectStore') }}" method="POST">
            @csrf
            <div class="mt-auto">
                @include("components/common/button", [
                'type' => "submit",
                'id' => NULL,
                'slot' => 'Selanjutnya'
                ])
            </div>
        </form>
        </div>
</div>
@endsection