<div id="confirm-modal" 
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

    <div class="bg-[#1E1E1E] p-8 rounded-xl w-[400px] text-center border border-purple-500">

        <p class="text-white mb-6">
            {{ $message }}
        </p>

        <div class="flex gap-4 w-full">
            <div class="flex-1">
                @include('components.common.errorBtn',[
                    'type' => 'button',
                    'id' => 'cancel-btn',
                    'slot' => $cancelText
                ])
            </div>
            <div class="flex-1">
                @include('components.common.button',[
                    'type' => 'button',
                    'id' => 'confirm-btn',
                    'slot' => $confirmText
                ])
            </div>
        </div>

    </div>
</div>