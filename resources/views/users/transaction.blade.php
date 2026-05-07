@extends("layouts/users")

@section("content")
    <section class="flex backdrop-blur-md border border-white/20 shadow-lg rounded-2xl mx-[80px] py-[32px] px-[80px] flex-col gap-[32px]">
                <h1 class="text-[26px] font-bold text-[var(--color-text)]">Riwayat Pembelian</h1>
                <ul class="pt-[2rem] space-y-6">
                    <li>
                        @foreach ($transactions as $transaction)
                        <div class="p-[1px] bg-gradient-to-r from-[#0E21A0] via-[#B153D7] to-[#4D2FB2] rounded-xl mb-[24px]">
                            @include("components/users/card-transaction", [
                                'transactions' => $transaction
                            ])
                        </div>
                        @endforeach
                    </li>
                </ul>
        </section>
@endsection
