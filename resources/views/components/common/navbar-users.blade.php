{{-- @php
function isActive($route) {
    return request()->is($route) 
        ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]' 
        : 'class="text-[var(--color-text)] hover:opacity-70"';
}
@endphp --}}

<header class="sticky top-0 z-50 w-full pt-[80px] pb-[64px] bg-[var(--color-bg)]">
    <nav class="flex w-full justify-between items-center px-[80px]">
        <img src="/img/Logo.png" alt="Plotify logo">

        <ul class="flex items-center gap-[2rem]">
            <li><a class="font-bold text-xl {{ request()->routeIs('users.property') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="/users/property">Properti</a></li>
            <li><a class="font-bold text-xl {{ request()->routeIs('users.listAppoinment') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="/users/appoinment/list">Pertemuan</a></li>
            <li><a class="font-bold text-xl {{ request()->routeIs('users.negotiation') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="/users/negotiation">Negosiasi</a></li>
            <li><a class="font-bold text-xl {{ request()->routeIs('users.transaction') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="/users/transaction">Transaksi</a></li>
 
                <form method="POST" action="/agent/logout">
                    @csrf
                    <button class="font-bold text-xl cursor-pointer" type="submit">Logout</button>
                </form>
            </li>
        </ul>
        @php
        $name = auth()->user()->fullname;
        $initials = strtoupper(substr($name, 0, 1));
        @endphp
        <div class="w-[50px] h-[50px] rounded-full text-[var(--color-text)] flex items-center justify-center font-semibold"
        style = "background: var(--btn-gradient-logo);">
          {{ $initials }}
        </div>
    </nav>
</header>