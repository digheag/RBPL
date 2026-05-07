@php
    $steps = [
        1 => 'Pilih agen',
        2 => 'Janji Temu',
        3 => 'Review'
    ];
@endphp

<div class="py-8 flex justify-center items-start">
@foreach ($steps as $step => $label)
    <div class="step relative flex flex-col items-center w-[180px]
        after:content-[''] 
        after:absolute 
        after:top-[25px] 
        after:right-[-90px] 
        after:w-[180px] 
        after:h-[2px] 
        after:bg-gray-300 
        after:z-[-1]
        last:after:hidden">

        {{-- STEP CIRCLE --}}
        @if ($step < $currentStep)
            {{-- COMPLETE --}}
            <div class="w-[50px] h-[50px] rounded-full 
                flex items-center justify-center"
                style="background:
                linear-gradient(var(--color-highlight), var(--color-highlight)) padding-box,
                var(--color-bg) border-box;">
                <i class="fa-solid fa-check" style="color: var(--color-text);"></i>
            </div>

        @elseif ($step == $currentStep)
            {{-- ACTIVE --}}
            <div class="border-0 outline-none relative w-[50px] h-[50px]">
                <div class="absolute z-[-1] rounded-full inset-[-4px]"
                    style="background : var(--color-bg);"></div>
                <div class="absolute z-[-2] rounded-full inset-[-6px]"
                    style="background : var(--btn-gradient2);"></div>
                <div class="absolute inset-0 rounded-full flex items-center justify-center font-bold text-[var(--color-text)]"
                    style="background: var(--color-primary);">
                    {{ $step }}
                </div>
            </div>

        @else
            {{-- DEFAULT --}}
            <div class="w-[50px] h-[50px] rounded-full text-[var(--color-text)] 
                flex items-center justify-center font-bold"
                style="background: var(--color-surface);">
                {{ $step }}
            </div>
        @endif

        {{-- LABEL --}}
        <div class="text-[var(--color-text)] mt-2 text-[var(--body-md)] 
            font-[var(--fw-md)] text-center">
            {{ $label }}
        </div>

    </div>
@endforeach
</div>

