<div class="p-[2px] rounded-lg bg-gradient-to-r from-pink-500 to-purple-500">
    @if(isset($href))
        {{-- kalau ada link --}}
        <a href="{{ $href }}" 
           id="{{ $id ?? '' }}" 
           class="inline-block w-full text-center rounded-lg bg-[var(--color-surface)] px-[24px] py-[8px] text-white
                  hover:bg-[var(--btn-gradient2)] hover:text-white
                  hover:shadow-[0_0_0_2px_rgba(243,117,194,0.3),0_0_12px_rgba(243,117,194,0.7),0_0_20px_rgba(243,117,194,0.4)]
                  transition-all duration-300">
            {{ $slot ?? 'Link' }}
        </a>
    @else
      <button type="{{ $type ?? 'button' }}" 
      id="{{ $id ?? '' }}" 
      class="w-full rounded-lg bg-[var(--color-surface)] px-[24px] py-[8px] text-white
       hover:bg-[var(--btn-gradient2)]
    hover:text-white
    hover:shadow-[0_0_0_2px_rgba(243,117,194,0.3),0_0_12px_rgba(243,117,194,0.7),0_0_20px_rgba(243,117,194,0.4)]">
       {{ $slot ?? 'Button' }}
      </button>
    @endif
</div>
