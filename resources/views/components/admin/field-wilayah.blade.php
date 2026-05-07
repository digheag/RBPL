<div class="w-full">

  <!-- wrapper -->
  <div class="relative dropdown-wrapper">

    <!-- input hidden (buat kirim ke Laravel) -->
    <input type="hidden" name="{{ $region['wilayah'] }}" class="hidden-input">

    <!-- box utama -->
    <div class="peer w-full border border-transparent rounded-lg 
    min-h-[56px] px-[12px] pt-4.5 pb-2 
    outline-none focus:border-pink-500 text-[var(--color-text)]
    focus:shadow-[0_0_0_2px_rgba(243,117,194,0.3),0_0_12px_rgba(243,117,194,0.7),0_0_20px_rgba(243,117,194,0.4)]" 
    style="background: linear-gradient(var(--color-surface)) padding-box,var(--btn-gradient2) border-box ;"
    >

      <!-- tags -->
      <div class="tags flex flex-wrap gap-2"></div>

      <!-- input search -->
      <input 
      type="text" 
      placeholder="{{ $region['label'] }}"
      class="search outline-none bg-transparent flex-1 placeholder:text-[var(--color-text)]"

      >
    </div>

    <!-- dropdown -->
    <div class="dropdown hidden absolute w-full mt-1 
    outline-none focus:border-pink-500 text-[var(--color-text)]
    focus:shadow-[0_0_0_2px_rgba(243,117,194,0.3),0_0_12px_rgba(243,117,194,0.7),0_0_20px_rgba(243,117,194,0.4)]
    border rounded-lg max-h-40 overflow-y-auto z-50"
    style="background: linear-gradient(var(--color-surface)) padding-box,var(--btn-gradient2) border-box;">
        @foreach ($works as $work)
            <div class="option p-2 hover:bg-[var(--color-highlight)] cursor-pointer">{{ $work['name'] }}</div>
        @endforeach
    </div>

  </div>

</div>
@once
@push('scripts')
<script>
document.querySelectorAll('.dropdown-wrapper').forEach(wrapper => {

  const input = wrapper.querySelector('.search')
  const dropdown = wrapper.querySelector('.dropdown')
  const options = wrapper.querySelectorAll('.option')
  const tagsContainer = wrapper.querySelector('.tags')
  const hiddenInput = wrapper.querySelector('.hidden-input')

  let selected = []

  // buka dropdown
  input.addEventListener('focus', () => {
    dropdown.classList.remove('hidden')
  })

  // search
  input.addEventListener('input', () => {
    const value = input.value.toLowerCase()

    options.forEach(opt => {
      opt.style.display = opt.innerText.toLowerCase().includes(value)
        ? 'block'
        : 'none'
    })
  })

  // pilih
  options.forEach(opt => {
    opt.addEventListener('click', () => {
      const value = opt.innerText

      if (!selected.includes(value)) {
        selected.push(value)
        //sembunyiin option
        opt.classList.add('hidden')
        renderTags()
      }

      input.value = ''
      input.placeholder = selected.length > 0 ? '' : 'Kabupaten'
    })
  })

  function renderTags() {
    tagsContainer.innerHTML = ''

    selected.forEach(item => {
      const tag = document.createElement('div')
      tag.className = "flex items-center gap-1 px-2 py-1 border rounded-md text-sm"

      tag.innerHTML = `
        ${item}
        <span class="cursor-pointer text-pink-400">✕</span>
      `

      tag.querySelector('span').addEventListener('click', () => {
        selected = selected.filter(i => i !== item)
        // munculin lagi option
        options.forEach(opt => {
            if (opt.innerText === item) {
            opt.classList.remove('hidden')
            }
        })
        renderTags()
      })

      tagsContainer.appendChild(tag)
    })

    // kirim ke backend (JSON string)
    hiddenInput.value = JSON.stringify(selected)
  }

  // klik luar
  document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) {
      dropdown.classList.add('hidden')
    }
  })

})
</script>
@endpush
@endonce