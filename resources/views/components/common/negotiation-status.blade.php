@php
    $status = strtolower($type ?? 'pending');

    $styles = [
        'approved' => 'bg-[#F375C2]',
        'rejected' => 'bg-[#B153D7]',
        'pending' => 'bg-[#4D2FB2]',
    ];

    $labels = [
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'pending' => 'Belum Diproses',
    ];
@endphp

<div class="px-6 py-2 {{ $styles[$status] ?? $styles['pending'] }} rounded-lg border-2 border-[#EFECE3]">
    <p class="text-white text-xl font-semibold font-['Poppins']">
        {{ $labels[$status] ?? $labels['pending'] }}
    </p>
</div>