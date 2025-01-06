<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Gallery</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-6">
<a href="{{ route('galleries.index') }}" class="mt-10 rounded-lg bg-green-500 p-4 text-white">Gallery</a>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create Gallery</h1>
    
    <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-lg font-medium text-gray-700 mb-2">Title</label>
            <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter gallery title" required>
        </div>
        
        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-lg font-medium text-gray-700 mb-2">Description</label>
            <textarea id="description" name="description" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Write a description"></textarea>
        </div>
        
        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-lg font-medium text-gray-700 mb-2">Status</label>
            <select id="status" name="status" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        
        <!-- Thumbnail Image -->
        <div class="mb-6">
            <label for="thumbnail_image" class="block text-lg font-medium text-gray-700 mb-2">Thumbnail Image</label>
            <input type="file" id="thumbnail_image" name="thumbnail_image" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" required>
            
            <!-- Thumbnail Preview -->
            <div id="thumbnailPreview" class="mt-4 hidden">
                <img id="previewThumbnail" src="" alt="Thumbnail Preview" class="rounded-lg border border-gray-300 shadow-md">
            </div>
        </div>

        <!-- Gallery Images -->
        <div class="mb-6">
            <label for="images" class="block text-lg font-medium text-gray-700 mb-2">Gallery Images</label>
            <input type="file" id="images" name="images[]" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" multiple required>
            
            <!-- Gallery Previews -->
            <div id="galleryPreview" class="mt-4 grid grid-cols-2 gap-4"></div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200">
            Create Gallery
        </button>
    </form>
</div>

<script>
    // Thumbnail Image Preview
    document.getElementById('thumbnail_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const thumbnailPreview = document.getElementById('thumbnailPreview');
        const previewThumbnail = document.getElementById('previewThumbnail');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewThumbnail.src = e.target.result;
                thumbnailPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewThumbnail.src = '';
            thumbnailPreview.classList.add('hidden');
        }
    });

    // Gallery Images Preview
    document.getElementById('images').addEventListener('change', function(event) {
        const files = event.target.files;
        const galleryPreview = document.getElementById('galleryPreview');
        galleryPreview.innerHTML = ''; // Clear existing previews

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded-lg border border-gray-300 shadow-md w-full h-auto';
                    galleryPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

</body>
</html>
