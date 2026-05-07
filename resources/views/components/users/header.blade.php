<header class="sticky top-0 left-0 right-0 p-8 flex flex-col mx-auto z-[10000] bg-[var(--color-bg)]">
  <nav class="w-full flex justify-between">
    <a href="{{ $link }}">
    <img src="/img/arrow.png" alt="arrow" class="rotate-180 w-[30px] h-[50px]">
    </a>
    <div class="flex gap-[32px] text-[var(--color-text)] text-[26px] font-semibold">
      <h1>{{ $title }}</h1>
    </div>

      @php
        $name = auth()->user()->fullname;
        $initials = strtoupper(substr($name, 0, 1));
        @endphp
    <div class="flex flex-col gap-1 text-right">
        <div class="w-[50px] h-[50px] rounded-full text-[var(--color-text)] flex items-center justify-center font-semibold"
        style = "background: var(--btn-gradient-logo);">
          {{ $initials }}
        </div>
    </div>
  </nav>
</header>