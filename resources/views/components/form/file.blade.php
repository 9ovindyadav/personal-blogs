<div>
    @include(
            'components.form.label',
            [
                'for' => $name, 
                'name' => $label
            ]
        )
    <div class="mt-2">
        <div class="w-full relative border-2 border-gray-300 border-dashed rounded-lg p-6" id="{{ $name }}-dropzone">
            <input type="file" name="{{ $name }}" class="absolute inset-0 w-full h-full opacity-0 z-50" id="{{ $name }}-uploaded-file" accept="image/jpeg,image/png,image/gif,image/jpg">
            <div class="text-center">
                <img class="mx-auto h-12 w-12" src="https://www.svgrepo.com/show/357902/image-upload.svg" alt="">

                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    <label for="{{ $name }}-uploaded-file" class="relative cursor-pointer">
                        <span>Drag and drop</span>
                        <span class="text-indigo-600"> or browse</span>
                        <span>to upload</span>
                    </label>
                </h3>
                <p class="mt-1 text-xs text-gray-500">
                    PNG, JPG, GIF up to 10MB
                </p>
            </div>

            <img src="{{ $img_link }}" class="mt-4 mx-auto max-h-40 {{ $img_link ?? 'hidden' }}" id="{{ $name }}-preview">
        </div>

        <script>
            var dropzone = document.getElementById('{{ $name }}-dropzone');

            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('border-indigo-600');
            });

            dropzone.addEventListener('dragleave', e => {
                e.preventDefault();
                dropzone.classList.remove('border-indigo-600');
            });

            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('border-indigo-600');
                var file = e.dataTransfer.files[0];
                console.log(file);
                displayPreview(file);
            });

            var input = document.getElementById('{{ $name }}-uploaded-file');

            input.addEventListener('change', e => {
                var file = e.target.files[0];
                displayPreview(file);
            });

            function displayPreview(file) {
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    var preview = document.getElementById('{{ $name }}-preview');
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                };
            }
        </script>
    </div>
    @include(
        'components.form.error',
        [ 
            'name' => $name
        ]
    )
</div>