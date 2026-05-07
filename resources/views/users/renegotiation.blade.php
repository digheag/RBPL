<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renegotiation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/design-system.css')
    @vite('resources/css/users.css')
</head>

<body class="bg-black text-[#EFECE3] font-[Poppins]">

<div class="w-[1280px] p-[80px] mx-auto flex flex-col justify-center items-start gap-8 bg-black">

    <div class="w-full flex flex-col items-start gap-4">
        <div class="w-full flex justify-between items-center">

            <div class="flex items-center gap-8">
                <a href="{{ url('/users/negotiation-detail-rejected') }}">
                    <img src="/img/arrow.png"
                         class="w-3 h-6 object-contain -scale-x-100"
                         alt="Back">
                </a>
            </div>

            <h1 class="w-[525px] text-center text-[48px] font-bold text-[#EFECE3]">
                Negosiasi
            </h1>

            <div class="w-12 h-12 rounded-full bg-zinc-400 flex items-center justify-center text-white text-[10px] font-normal tracking-wider uppercase">
                MK
            </div>

        </div>
    </div>

    <div class="w-full px-[64px] py-[56px] rounded-[20px]
        shadow-[inset_0px_4px_3px_rgba(59,104,255,0.28),0px_4px_4px_rgba(59,104,255,0.10)]
        backdrop-blur-[1px]
        flex flex-col items-center gap-8">

        <h2 class="w-full text-center text-[48px] font-bold text-[#EFECE3]">
            Modern Building House
        </h2>

        <p class="w-full text-[36px] font-bold text-[#F375C2]">
            Harga Jual: Rp. 500.000.000,00
        </p>

        <p class="w-full text-[36px] font-bold text-[#F375C2]">
            Negosiasi Sebelumnya: Rp. 300.000.000,00
        </p>

        <form class="w-full flex flex-col gap-8">

            <input
                type="text"
                placeholder="Harga Negosiasi"
                class="w-full h-14 px-4 rounded-[10px] border-2 border-[#0E21A0] bg-transparent text-[#EFECE3] text-base font-normal placeholder:text-[#676666] outline-none"
            >

            <textarea
                placeholder="Alasan"
                class="w-full h-14 px-4 py-3 rounded-[10px] border-2 border-[#0E21A0] bg-transparent text-[#EFECE3] text-base font-normal placeholder:text-[#676666] outline-none resize-none"
            ></textarea>

            <div class="w-full">
                @include('components.common.button', [
                    'type' => 'submit',
                    'slot' => 'Negosiasi Ulang'
                ])
            </div>

        </form>

    </div>

</div>

</body>
</html>