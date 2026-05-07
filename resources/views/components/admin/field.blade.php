@php
    $type = $type ?? ($field['type'] ?? 'text');
    $name = $name ?? ($field['name'] ?? '');
    $label = $label ?? ($field['label'] ?? '');
    $value = $value ?? ($field['value'] ?? '');
@endphp

<div class="relative">
          <input 
            type="{{ $type }}"
            name="{{ $name }}"
            placeholder="isi"
            value="{{ old($name, $value) }}"
            class="peer input-field w-full border border-transparent rounded-lg px-[12px] 
            pt-7 pb-2 outline-none focus:border-pink-500 text-[var(--color-text)]
            placeholder-transparent
            focus:shadow-[0_0_0_2px_rgba(243,117,194,0.3),0_0_12px_rgba(243,117,194,0.7),0_0_20px_rgba(243,117,194,0.4)]" 
            style="background: linear-gradient(var(--color-surface)) padding-box,var(--btn-gradient2) border-box ;"
          >
          <label 
            class="absolute z-10 left-3 px-1 pointer-events-none
            bg-[var(--color-surface)]
            text-[var(--color-text)] text-sm transition-all

            top-1/2 -translate-y-1/2

            peer-placeholder-shown:top-1/2
            peer-placeholder-shown:-translate-y-1/2 
            peer-placeholder-shown:text-base 

            peer-focus:top-2.5
            peer-focus:-translate-y-1                
            peer-focus:text-sm 

            peer-not-placeholder-shown:top-2.5
            peer-not-placeholder-shown:-translate-y-1
            peer-not-placeholder-shown:text-sm">
            {{ $label }}
          </label>          
        </div>
