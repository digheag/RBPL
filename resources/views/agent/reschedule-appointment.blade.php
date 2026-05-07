@php
    use App\Enums\AppointmentScheduleStatus;
@endphp

@extends("layouts/agent")

@section('content')
    @include("components/users/header")

    <main>
        <section class="px-[80px]">
            <div class="py-[60px] px-[80px] border-2 border-[#1E1E1E] rounded-xl">
            <form method="POST" action="{{ route("agent.rescheduleAppointmentAction", $appointment->id)  }}">
            @csrf

                @php
                    $field = ['type' => "datetime-local", 'name' => 'schedule', 'label' => 'Atur ulang tanggal' ]
                @endphp
                    @include("components/admin/field")


                <div class="pt-[32px] pb-[16px]">
                  @include("components/admin/button", [
                    'type' => 'submit',
                    'id' => NULL,
                    'slot' => 'Atur Jadwal'
                    ])
                </div>

            </form>
            </div>
        </section>
    </main>
@endsection
