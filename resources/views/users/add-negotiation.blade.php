<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negosiasi</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:wght@400;700&display=swap"
        rel="stylesheet"
    >

    @vite('resources/css/design-system.css')
    @vite('resources/css/admin.css')
</head>

<body class="bg-[var(--color-bg)] text-[var(--color-text)] font-[Poppins]">

<div class="w-[1280px] p-[80px] mx-auto flex flex-col justify-center items-start gap-8 bg-black">

    <div class="self-stretch flex flex-col items-start gap-4">
        <div class="self-stretch flex justify-between items-center">

            <div class="flex items-center gap-8">
                <a href="{{ url('/users/property') }}">
                    <img src="/img/arrow.png" class="w-3 h-6 object-contain -scale-x-100" alt="Back">
                </a>
            </div>

            <div class="w-[525px] h-16 text-center text-[var(--color-text)] text-5xl font-bold">
                Negosiasi
            </div>

            <div class="w-[50px] h-[50px] bg-[#999] rounded-full flex items-center justify-center text-white text-[10px] tracking-widest">
                MK
            </div>

        </div>
    </div>

    <div class="self-stretch px-[64px] py-[60px]
        rounded-[20px]
        bg-black/0
        shadow-[inset_0px_4px_3px_rgba(59,104,255,0.28),0px_4px_4px_rgba(59,104,255,0.10)]
        backdrop-blur-[1px]
        flex flex-col items-center gap-8">

        <div class="self-stretch text-center text-[var(--color-text)] text-5xl font-bold">
            Modern Building House
        </div>

        <div class="self-stretch text-[var(--highlight)] text-4xl font-bold">
            Harga Jual: Rp. 500.000.000,00
        </div>

        <div class="self-stretch flex flex-col items-start gap-8">

            <input
                type="text"
                placeholder="Harga Negosiasi"
                class="self-stretch h-14 px-4 rounded-[10px]
                border-2 border-blue-900
                bg-transparent
                text-white text-base font-normal
                placeholder:text-stone-500
                focus:outline-none"
            />

            <textarea
                placeholder="Alasan"
                class="self-stretch h-28 px-4 py-3 rounded-[10px]
                border-2 border-blue-900
                bg-transparent
                text-white text-base font-normal
                placeholder:text-stone-500
                resize-none
                focus:outline-none"
            ></textarea>

        </div>

        <div class="self-stretch">
            @include('components.common.button', [
                'slot' => 'Selanjutnya'
            ])
        </div>

    </div>

</div>

</body>
</html>