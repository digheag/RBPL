@extends("layouts/agent")

@section("content")
@php use Illuminate\Support\Str; @endphp
    @include("components/common/navbar")

    <main class="py-8">
        <form action="{{ route("agent.property") }}" method="get">
        @csrf
            <div class="flex w-full items-center px-[80px]">
                @include('components/common/search')
            </div>
            @if ($search)
                <div class="px-6 md:px-20 mt-4">
                    <p class="text-gray-600">Hasil pencarian untuk: "{{ $search }}", <a href="{{ route('agent.property') }}" class="text-blue-500 hover:underline">Lihat semua properti</a></p>
                </div>
            @endif
        </form>
        
        <section class="px-[80px] mt-[24px]">
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            <ul class="grid grid-cols-3 gap-[80px] ">
                @foreach($properties as $property)
                <li>
                    <article class="h-[400px] p-[1px] rounded-xl bg-gradient-to-r from-[#0560E8] to-[#7000FF]">
                        <div class=" h-full gap-3 flex flex-col rounded-xl bg-[var(--color-bg)] p-[10px]">
                            <img src="{{ asset('storage/' . $property->property_image->whereNotNull('url')->first()?->url) }}" alt="gambar" 
                            class="w-full h-[160px] object-cover rounded-[16px] mb-[16px]">
                            <h1 class="font-bold text-lg">{{ $property->name }}</h1>
                            <p class="mt-2xl">{{ Str::limit($property->description, 100) }}</p>

                            <div class="grid grid-cols-2 gap-[16px] mt-auto">
                                <div class="">
                                    @include("components/common/errorBtn",[ 
                                        'type' => 'button', 
                                        'id' => "open-delete-{$property->id}", 
                                        'slot' => "Hapus", 
                                    ]) 
                                </div>

                                <div class="">
                                    @include("components/common/button",[
                                        'href' => route("agent.detailProperty", $property->id), 
                                        'id' => "#", 
                                        'slot' => "Edit", 
                                    ])
                                </div>
                            </div>
                        </div>
                    </article>
                    {{-- FORM DELETE --}}
                    <form id="delete-form-{{ $property->id }}" 
                        action="{{ route('agent.propertyDelete', $property->id) }}" 
                        method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </li>
                @endforeach
            </ul>
        </section>
        @include("components.common.floatingCard",[
            'message' => "Apakah yakin mau buang property?",
            'cancelText' => 'tidak',
            'confirmText' => 'iya'
        ])            
    </main>
   <script>
document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('confirm-modal');
    const confirmBtn = document.getElementById('confirm-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    let selectedId = null;

    // ambil semua tombol yang id nya diawali open-delete-
    document.querySelectorAll('[id^="open-delete-"]').forEach(btn => {

        btn.addEventListener('click', () => {

            // ambil id dari nama id
            selectedId = btn.id.split('-').pop();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

    });

    // cancel
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        selectedId = null;
    });

    // confirm delete
    confirmBtn.addEventListener('click', () => {
        if (selectedId) {
            document.getElementById(`delete-form-${selectedId}`).submit();
        }
    });

});
</script>
@endsection
