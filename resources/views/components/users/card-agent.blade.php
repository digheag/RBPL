<div class="relative w-full p-[24px] rounded-[20px]bg-white/15 backdrop-blur-[15px]
border-transparent border rounded-[16px] shadow-[0_8px_32px_rgba(0,0,0,0.2)]"
style="background: linear-gradient(var(--color-surface)) padding-box, var(--btn-gradient) border-box">

<img src="{{ asset($agent->user->profile) }}" alt="building" class="w-full rounded-[16px] mb-[16px]">

<form action="{{ route('users.chooseAgentAction') }}" method="post">
    @csrf

    <input type="hidden" name="agent_id" value="{{ $agent->id }}" />
    <h1 class="text-[var(--color-text)] mb-[16px] font-[var(--fw-bold)] text-[18px] text-center">{{ $agent->user->fullname }}</h1>

    <div class="w-full flex text-[var(--color-text)] gap-[8px] items-center pb-[8px]">
        <img src="/img/call.png" alt="call icon">
        <span class="text-[var(--color-text)]">{{ $agent->user->telp_number }}</span>
    </div>
    <div class="w-full flex text-[var(--color-text)] gap-[8px] mb-[16px] items-center">
        <img src="/img/location.png" alt="location icon">
        <span class="text-[var(--color-text)]">{{ $agent->agentRegency->flatten()->pluck('regency.name')->filter()->unique()->implode(', ') }}</span>
    </div>

    <div class="w-full h-[1.5px] bg-[var(--btn-gradient2)]"></div>
    @include("components/admin/button", [
        'type' => 'submit',
        'id' => NULL,
        'slot' => 'Pilih Agent'
        ])
    </div>
</form>


