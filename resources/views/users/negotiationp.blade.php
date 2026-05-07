@extends("layouts/users")
@section("content")
<div class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
    <h1 class="text-[26px] font-bold text-[var(--color-text)]">Riwayat Negosiasi</h1>
    <div class="p-[1px] bg-gradient-to-r from-[#0E21A0] via-[#B153D7] to-[#4D2FB2] rounded-xl">
        <article class="bg-[var(--color-surface)] flex justify-between items-center p-[16px] rounded-xl">
            <div>
                <h2 class="text-[22px] font-semibold">Modern Building House</h2>
            </div>
            <div class="flex flex-row gap-[16px] items-center">
                <div class="inline-block px-[8px] py-[8px] rounded-[8px] border-2 border-white 
                bg-[var(--color-highlight)] text-[var(--color-text)] font-semibold text-[16px] text-center">
                Disetujui
                </div>
                <a href="/users/negotiation/detail">
                <i class="fa-solid fa-angle-right text-4xl"></i>
                </a>
            </div>
        </article>
    </div>
</div>
@endsection