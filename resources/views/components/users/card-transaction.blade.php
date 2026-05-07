   <article class="bg-[#1E1E1E] flex justify-between items-center px-[16px] py-[16px] rounded-xl">
      <div class="flex items-center gap-6">
         <img src="{{ asset('storage/' . $transaction->property->property_image->whereNotNull('url')->first()?->url)}}" alt="building" class="w-[100px] rounded-[16px]">
         <div>
            <p class="text-[18px] text-[var(--color-text)] pb-[8px]">{{ $transaction->sold_date }}</p>
            <h2 class="text-2xl font-bold">{{$transaction->property->name}}</h2>
         </div>
      </div>
      <a href="{{ route('users.detailTransaction', ['id' => $transaction->id]) }}">
         <i class="fa-solid fa-angle-right text-4xl"></i>
      </a>

   </article>