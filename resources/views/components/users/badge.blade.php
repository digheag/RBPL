<div class="inline-flex w-fit items-center px-[8px] py-[8px] rounded-[8px] border-2 border-white 
        bg-[var(--color-highlight)] text-[var(--color-text)] font-semibold text-[16px] text-center
        @if ($status == 'Disetujui')
            bg-[var(--color-highlight)]
        @elseif ($status == 'Ditolak')
            bg-[var(--color-accent)]
        @else 
            bg-[var(--color-primary)]
        @endif
        ">
        {{ $status }}
</div>