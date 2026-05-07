<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

  <!-- font -->
  <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,400;1,600&display=swap"
    rel="stylesheet">
  <!-- link -->
   @vite('resources/css/design-system.css')
    @vite('resources/css/admin.css')
</head>
<body class="text-[var(--color-text)]">
    {{-- @php
function isActive($route) {
    return request()->is($route) 
        ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]' 
        : 'class="text-[var(--color-text)] hover:opacity-70"';
}
@endphp --}}

<header class="sticky top-0 z-50 w-full pt-[80px] pb-[64px] bg-[var(--color-bg)]">
    <nav class="flex w-full justify-between items-center px-[80px]">
        <img src="/img/Logo.png" alt="Plotify logo">

        <ul class="flex items-center gap-[2rem]">
            <li><a class="font-bold text-xl {{ request()->routeIs('users.property') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="/users/home">Properti</a></li>
            <li><a class="font-bold text-xl {{ request()->routeIs('users.property') ? ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="">Negosiasi</a></li>
            <li><a class="font-bold text-xl {{ request()->routeIs('users.property') ? 'bg-clip-text text-transparent bg-gradient-to-t from-[#B153D7] via-[#4D2FB2] to-[#0E21A0]'
            : 'text-[var(--color-text)] hover:opacity-70'; }} transition-all duration-300" href="">Transaksi</a></li>
            <li>
                <form method="POST" action="/agent/logout">
                    @csrf
                    <button class="font-bold text-xl cursor-pointer" type="submit">Logout</button>
                </form>
            </li>
        </ul>
        <div class="size-[50px] bg-[#999] rounded-full flex justify-center items-center">MK</div>
    </nav>
</header>
</body>
</html>
