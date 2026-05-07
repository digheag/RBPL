<div id="upload-area" 
     class="border-2 border-dashed border-purple-500 rounded-xl p-10 text-center cursor-pointer">

    <input type="file" id="image-input" name="images[]" multiple hidden>

    <!-- placeholder (yang boleh hilang) -->
    <div id="upload-placeholder" class="flex flex-col items-center gap-4">
        <img src="/img/upload.png" class="w-[100px] h-[100px]">
        <p class="text-white text-lg">Unggah file mu di sini</p>
    </div>

    <!-- tombol SELALU ADA -->
    <div class="mt-4">
        @include("components/admin/button", [
            'type' => 'button',
            'id' => 'upload-btn',
            'slot' => 'Tambah Gambar'
        ])
    </div>

    <!-- preview -->
    <div id="preview" class="grid grid-cols-4 gap-4 mt-4"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('image-input');
    const btn = document.getElementById('upload-btn');
    const area = document.getElementById('upload-area');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('upload-placeholder');

    // klik tombol
    btn.addEventListener('click', (e) => {
        e.stopPropagation(); // biar nggak double trigger
        input.click();
    });

    // klik area
    area.addEventListener('click', () => input.click());

    // saat pilih file
    let selectedFiles = [];

    input.addEventListener('change', function () {
    const newFiles = [...this.files];

    selectedFiles = [...selectedFiles, ...newFiles];

    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    console.log("FILES:", input.files.length);

    handleFiles(newFiles); 
    });

    function handleFiles(files) {
        preview.classList.remove('hidden');

        if (preview.children.length === 0) {
            placeholder.classList.add('hidden');
        }

        [...files].forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const wrapper = document.createElement('div');
                wrapper.className = "relative";

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "w-full h-32 object-cover rounded-lg";

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = "✕";
                removeBtn.className = "absolute top-1 right-1 bg-black/60 text-white px-2 rounded";

                removeBtn.onclick = (ev) => {
                    ev.stopPropagation();
                    wrapper.remove();

                     // hapus dari array
                    selectedFiles = selectedFiles.filter(f => f !== file);

                    // update input lagi
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(f => dataTransfer.items.add(f));
                    input.files = dataTransfer.files;

                    wrapper.remove();

                    if (preview.children.length === 0) {
                        placeholder.classList.remove('hidden');
                    }
                };

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

});
</script>