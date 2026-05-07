@extends("layouts/agent")

@section('content')
    @include("components/common/navbar")

    <main class="pb-7">
        <section class="px-[80px]">
            <div class="py-[60px] px-[80px] border-2 border-[#1E1E1E] rounded-xl">
                <h1 class="text-4xl font-bold">Daftar Janji Temu</h1>
                <ul class="pt-[2rem] flex flex-col gap-5">
                    @foreach($appointments as $appointment)
                        <li>
                            <a href="{{ route("agent.appointmentDetail", $appointment->id) }}">
                                <div class="p-[1px] bg-gradient-to-r from-[#0E21A0] via-[#B153D7] to-[#4D2FB2] rounded-xl">
                                    <article class="bg-[#1E1E1E] flex justify-between items-center px-[1rem] py-[2rem] rounded-xl">

                                        <div>
                                            <p class="text-lg font-medium">{{ $appointment->appointmentSchedules[0]->schedule->translatedFormat('d F Y, H:i'); }}</p>
                                            <h2 class="text-2xl font-bold mt-2">{{ $appointment->propertyName }}</h2>
                                            <h3 class="text-lg mt-1">{{ $appointment->seller->fullname }}</h3>
                                        </div>
                                        <i class="fa-solid fa-angle-right text-4xl"></i>
                                    </article>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </main>
@endsection
