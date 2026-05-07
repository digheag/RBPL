@extends("layouts/property")
@section("content")
@php
    $fields = [
        ['type'=>'text', 'name' => 'property_name', 'label' => 'Nama Properti'],
        ['type' => 'text', 'name' => 'property_address', 'label' => 'Alamat Properti'],
        ['type' => "datetime-local", 'name' => 'actual_time_schedule', 'label' => 'Tanggal Pertemuan' ]
    ];

@endphp
    <form action="{{ route('users.appointmentAction') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-white/10 backdrop-blur-md border border-white/20
            shadow-lg rounded-2xl m-[80px]">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full p-[64px]">
                @foreach ($fields as $field)
                  @include("components/admin/field")
                @endforeach

                <div class="w-64 relative">
                <!-- Input visible -->
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Pilih Kecamatan"
                    class="w-full px-4 py-2 text-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    onclick="toggleDropdown()"
                    onkeyup="filterOptions()"
                    autocomplete="off"
                >

                <!-- Hidden input (INI YANG DIKIRIM) -->
                <input type="hidden" name="district_id" id="selectedValue">

                <!-- Dropdown -->
                <div id="dropdown" class="absolute w-full mt-1 bg-white border rounded-lg shadow-lg hidden max-h-48 overflow-y-auto z-10">
                    @foreach($districts as $district)
                        <div data-value="{{ $district["id"] }}" class="px-4 py-2 hover:bg-green-100 cursor-pointer" onclick="selectOption(this)">{{ $district["name"] }}</div>
                    @endforeach
                </div>
                </div>

                <div class="pt-[32px] pb-[16px]">
                  @include("components/admin/button", [
                    'type' => 'submit',
                    'id' => NULL,
                    'slot' => 'Selanjutnya'
                    ])
                </div>
            </div>
    </div>

  </form>
@endsection

@push("scripts")
<script>
function toggleDropdown() {
  document.getElementById("dropdown").classList.toggle("hidden");
}

function selectOption(el) {
  // tampilkan text ke input
  document.getElementById("searchInput").value = el.innerText;

  // simpan ID ke hidden input
  document.getElementById("selectedValue").value = el.dataset.value;

  // tutup dropdown
  document.getElementById("dropdown").classList.add("hidden");
}

function filterOptions() {
  let input = document.getElementById("searchInput").value.toLowerCase();
  let items = document.querySelectorAll("#dropdown div");

  items.forEach(item => {
    if (item.innerText.toLowerCase().includes(input)) {
      item.style.display = "block";
    } else {
      item.style.display = "none";
    }
  });
}

// klik luar nutup dropdown
document.addEventListener("click", function(e) {
  if (!e.target.closest(".relative")) {
    document.getElementById("dropdown").classList.add("hidden");
  }
});
</script>
@endpush
