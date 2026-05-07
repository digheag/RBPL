@extends('layouts/detail')

@section('content')
@php
    $fields = [
        ['type' => 'text','name' => 'propertyName', 'label' => 'Nama Property', 'value' => old('propertyName', $property->name)],
        ['type' => 'text','name' => 'propertyAddress', 'label' => 'Alamat Property', 'value' =>  old('propertyAddress', $property->address) ],
        ['type' => 'text','name' => 'propertyPrice', 'label' => 'Harga Property', 'value' => old('propertyPrice', $property->price)],
        ['type' => 'text','name' => 'propertyArea', 'label' => 'Luas Property', 'value' => old('propertyArea', $property->area_in_hectare)],
        ['type' => 'datetime-local','name' => 'sold', 'label' => 'Tanggal Terjual', 'value' => old('sold', $property->sold_date)],
        ['type' => 'text','name' => 'description', 'label' => 'Deskripsi Property', 'value' => old('description', $property->description)],
    ]
@endphp
    <main>
        <form action="{{ route('agent.propertyUpdate', $property->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <section class="px-[80px] py-[48px]">
                <div class="py-[60px] px-[80px] border-2 border-[#1E1E1E] rounded-xl">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="flex flex-col gap-[32px] mb-[32px]">
                    @foreach ($fields as $field)
                        @include('components.admin.field')
                    @endforeach
                </div>

                {{-- SPESIFIKASI --}}
                <div class="flex flex-col gap-[8px]" id="spec-container">
                    <h1 class="text-[var(--color-text)] font-bold text-[18px]">Spesifikasi</h1>

                    @foreach(old('spesification', $property->spesification->pluck('description')->toArray()) as $spec)
                        <div class="spec-item">
                            @include('components.admin.field',[
                                'type' => 'text',
                                'name' => 'spesification[]',
                                'label' => 'Spesifikasi Property',
                                'value' => $spec
                            ])
                        </div>
                    @endforeach

                </div>
                <div class="grid grid-cols-2 place-content-between gap-[16px] mt-[32px]">
                        @include('components.common.button',[
                            'type' => "button",
                            'id' => "add-spec",
                            'slot' => "Tambah spesifikasi"
                        ])
                        @include('components.common.button',[
                            'type' => "button",
                            'id' => "remove-spec",
                            'slot' => "Hapus spesifikasi"
                        ])
                </div>
                <template id="spec-template">
                    <div class="spec-item">
                            @include('components.admin.field',[
                            'type' => 'text',
                            'name' => 'spesification[]', 
                            'label' => 'Spesifikasi Property',
                            'value' => '' 
                            ])
                    </div>
                </template>

                {{-- FASILITAS --}}
                <div class="flex flex-col gap-[8px]" id="facility-container">
                    <h1 class="text-[var(--color-text)] font-bold text-[18px] mt-[32px]">Fasilitas</h1>

                    @foreach(old('fasilitas', $property->facilities->pluck('description')->toArray()) as $fac)
                        <div class="facility-item">
                            @include('components.admin.field',[
                                'type' => 'text',
                                'name' => 'fasilitas[]',
                                'label' => 'Fasilitas Property',
                                'value' => ''
                            ])
                        </div>
                    @endforeach

                </div>
                <div class="grid grid-cols-2 place-content-between gap-[16px] mt-[32px]">
                        @include('components.common.button',[
                            'type' => "button",
                            'id' => "add-facility",
                            'slot' => "Tambah fasilitas"
                        ])
                        @include('components.common.button',[
                            'type' => "button",
                            'id' => "remove-facility",
                            'slot' => "Hapus fasilitas"
                        ])
                </div>
                <template id="facility-template">
                    <div class="facility-item">
                            @include('components.admin.field',[
                            'type' => 'text',
                            'name' => 'fasilitas[]', 
                            'label' => 'Fasilitas Property',
                            'value' => ''
                            ])
                    </div>
                </template>

                <div class="flex flex-col gap-[8px]">
                    <h1 class="text-[var(--color-text)] font-bold text-[18px] mt-[32px]" >Unggah Gambar Properti</h1>
                    @include('components.common.fieldImageProperty')
                </div>

                <div class="my-[32px]">
                    @include("components.common.button",[
                        'type' => 'button',
                        'id' => "open-confirm",
                        'slot' => 'Simpan Perubahan'
                    ])
                </div>
            </div>
        </section>
        @include("components.common.floatingCard",[
            'message' => "Apakah yakin mau simpan perubahan?",
            'cancelText' => 'tidak maw',
            'confirmText' => 'maw'
        ])
    </main>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // SPESIFIKASI
        document.getElementById('add-spec').addEventListener('click', function () {
        let template = document.getElementById('spec-template').content.cloneNode(true);
        document.getElementById('spec-container').appendChild(template);
        });

        const specContainer = document.getElementById('spec-container');
        
        document.getElementById('remove-spec').addEventListener('click', function () {
        let items = specContainer.querySelectorAll('.spec-item');
        
        if (items.length > 1) {
            items[items.length - 1].remove();
        }
        });

        // FASILITAS
        document.getElementById('add-facility').addEventListener('click', function () {
        let template = document.getElementById('facility-template').content.cloneNode(true);
        document.getElementById('facility-container').appendChild(template);
        });

        const facilityContainer = document.getElementById('facility-container');
        
        document.getElementById('remove-facility').addEventListener('click', function () {
        let items = facilityContainer.querySelectorAll('.facility-item');
        
        if (items.length > 1) {
            items[items.length - 1].remove();
        }
        });
    });

    const openBtn = document.getElementById('open-confirm');
    const modal = document.getElementById('confirm-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const confirmBtn = document.getElementById('confirm-btn');

    const form = document.querySelector('form');

    // buka modal
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // cancel → tutup modal
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // confirm → submit form
    confirmBtn.addEventListener('click', () => {
        form.submit();
    });
    </script>
@endsection
