@php use Illuminate\Support\Str; @endphp
<div class="relative w-full h-[400px] flex flex-col p-[24px] rounded-[20px] bg-white/15 backdrop-blur-[15px]
border-transparent border rounded-[16px] shadow-[0_8px_32px_rgba(0,0,0,0.2)]"
style="background: linear-gradient(var(--color-surface)) padding-box, var(--btn-gradient) border-box">
<img src="{{ asset('storage/' . $property->property_image->whereNotNull('url')->first()?->url) }}" alt="building" 
class="w-full h-[160px] object-cover rounded-[16px] mb-[16px]">
<h1 class="text-[var(--color-text)] mb-[16px] font-[var(--fw-bold)] text-[18px]">{{ $property->name }}</h1>
<p class="line-clamp2 text-[var(--color-text)] mb-[16px] text-[14px] ">{{ Str::limit($property->description, 100) }}</p>
 <div class="mt-auto">
  @include("components/common/button", [
        'href' => route('property.detail', ['id' => $property->id]),
        'slot' => 'Lihat Selengkapnya'
      ])
 </div>
</div>