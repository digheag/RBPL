@extends("layouts/property")
@section("content")

<!--Search bar n filter-->
<div class="flex gap-2 w-full px-6 md:px-20 mt-4 items-center">
    <div class="flex-1">
        <form action="" method="GET">
            @include('components/common/search')
        </form>
    </div>
    <div class="relative">
        @include('components/users/filter')
    </div>
</div>

@if ($searchQuery)
    <div class="px-6 md:px-20 mt-4">
        <p class="text-gray-600">Hasil pencarian untuk: "{{ $searchQuery }}", <a href="{{ route('users.chooseAgent') }}" class="text-blue-500 hover:underline">Lihat semua agen</a></p>
    </div>
@endif

{{-- card-loop --}}
<div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[64px] px-6 md:px-20 mt-12">
    @foreach ($agents as $agent)
        @include("components/users/card-agent")
    @endforeach
</div>
@endsection
