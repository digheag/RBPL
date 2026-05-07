@extends("layouts/users")
@section("content")

<form action="{{ route("users.property") }}" method="get">
@csrf
    <div class="flex w-full items-center px-[80px]">
        @include('components/common/search')
    </div>
    @if ($search)
        <div class="px-6 md:px-20 mt-4">
            <p class="text-gray-600">Hasil pencarian untuk: "{{ $search }}", <a href="{{ route('users.property') }}" class="text-blue-500 hover:underline">Lihat semua properti</a></p>
        </div>
    @endif
</form>


<div class="flex w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6 md:px-[80px] mt-[48px] items-center px-[80px] mt-[48px]">
    @foreach ($properties as $property)
        @include("components/common/card-property", [
            'property' => $property
        ])
    @endforeach
</div>
<div>
    @include('components/users/btn-add')
</div>
@endsection