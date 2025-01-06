<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Index</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-gray-100 m-5">
    <a href="{{ route('categories.index') }}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Category</a>
    <a href="{{ route('subcategories.index') }}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Sub-Category</a>
    <a href="{{ route('products.index') }}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Products</a>
    <a href="{{ route('galleries.index') }}" class="mt-10 rounded-lg bg-gray-500 p-4 text-white">Gallery</a>

    <section class="w-full mt-8 p-6">
        <h2 class="text-3xl text-center font-semibold text-red-800">Gallery</h2>

        <div>
            <a href="{{ route('galleries.create') }}" class="bg-blue-800 rounded-lg mb-4 px-4 py-4 text-white hover:bg-blue-600">Create New Gallery</a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4 mt-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full bg-white border border-red-800 shadow-lg rounded-lg overflow-hidden mt-8">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">S.No.</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Title</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Description</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Status</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Images</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($galleries as $index => $gallery)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="text-gray-800 text-center text-sm px-6 py-4">{{$index+1}}</td>
                        <td class="text-gray-800 text-center text-sm px-6 py-4">{{ $gallery->title }}</td>
                        <td class="text-gray-800 text-center text-sm px-6 py-4">{{ $gallery->description }}</td>
                        <td class="text-gray-800 text-center text-sm px-6 py-4">{{ $gallery->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-gray-800 text-center text-sm px-6 py-4">{{ $gallery->images->count() }}</td>
                        <td class="text-gray-800 text-center text-sm px-6 py-4">
                            <!-- Edit Button -->
                            <button onclick="openEditModal('{{ $gallery->id }}')" class="bg-blue-400 text-white py-1 px-2 rounded">Edit</button>
                            <!-- Delete Button -->
                            <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" class="inline deleteForm" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                @csrf
                                @method ('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

  <!-- Edit Gallery Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-1/3 max-h-screen overflow-y-auto">
        <h2 class="text-xl font-semibold mb-4">Edit Gallery</h2>
        <form id="editGalleryForm" method="POST" enctype="multipart/form-data" action="{{ route('galleries.update', ':galleryId') }}">
            @csrf
            @method('PUT')
            <input type="hidden" id="galleryId" name="galleryId" value="">

            <!-- Title -->
            <div class="mb-4">
                <label for="editTitle" class="block text-gray-700">Title</label>
                <input type="text" id="editTitle" name="title" class="border rounded w-full p-2" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="editDescription" class="block text-gray-700">Description</label>
                <textarea id="editDescription" name="description" class="border rounded w-full p-2" required></textarea>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="editStatus" class="block text-gray-700">Status</label>
                <select id="editStatus" name="status" class="border rounded w-full p-2">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <!-- Thumbnail Image -->
            <div class="mb-4">
                <label for="editThumbnail" class="block text-gray-700">Thumbnail Image</label>
                <input type="file" id="editThumbnail" name="thumbnail_image" class="border rounded w-full p-2" accept="image/*">
                <img id="editThumbnailPreview" class="mt-4 rounded-lg border w-full h-auto hidden" src="" alt="Current Thumbnail">
            </div>

            <!-- Gallery Images -->
            <div class="mb-4">
                <label for="editGalleryImages" class="block text-gray-700">Gallery Images</label>
                <input type="file" id="editGalleryImages" name="gallery_images[]" class="border rounded w-full p-2" accept="image/*" multiple>
                <div id="editGalleryPreview" class="mt-4 grid grid-cols-2 gap-4"></div>
            </div>

            <!-- Buttons -->
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save Changes</button>
            <button type="button" class="bg-gray-500 text-white py-2 px-4 rounded" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

    <script>
        // Open the edit modal and populate the fields
        function openEditModal(galleryId) {
            fetch(`/galleries/${galleryId}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Populate the modal fields
                document.getElementById('galleryId').value = data.id;
                document.getElementById('editTitle').value = data.title;
                document.getElementById('editDescription').value = data.description;
                document.getElementById('editStatus').value = data.status;

                // Update the thumbnail preview
                const thumbnailPreview = document.getElementById('editThumbnailPreview');
                thumbnailPreview.classList.remove('hidden');
                thumbnailPreview.src = `/storage/${data.thumbnail_image}`; // Assuming the image is stored in 'storage'

                // Update the gallery images preview
                const galleryPreview = document.getElementById('editGalleryPreview');
                galleryPreview.innerHTML = ''; // Clear existing previews

                data.images.forEach(image=> {
                    const img = document.createElement('img');
                    img.src = `/storage/${image.image_path}`; // Ensure the correct storage path
                    img.alt = 'Gallery Image';
                    img.classList.add('w-32', 'h-32', 'rounded', 'border', 'border-gray-300', 'mr-2', 'mb-2');
                    galleryPreview.appendChild(img);
                });

                // Show the modal
                document.getElementById('editModal').classList.remove('hidden');

                // Update the form action dynamically
                document.getElementById('editGalleryForm').action = `{{ route('galleries.update', ':galleryId') }}`.replace(':galleryId', galleryId);
            })
            .catch(error => console.error('Error:', error));
        }

        // Close the modal
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Preview thumbnail image
        document.getElementById('editThumbnail').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const editThumbnailPreview = document.getElementById('editThumbnailPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editThumbnailPreview.src = e.target.result;
                    editThumbnailPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                editThumbnailPreview.classList.add('hidden');
            }
        });

        // Preview gallery images
        document.getElementById('editGalleryImages').addEventListener('change', function(event) {
            const files = event.target.files;
            const editGalleryPreview = document.getElementById('editGalleryPreview');
            editGalleryPreview.innerHTML = ''; // Clear existing previews

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded-lg border w-full h-auto';
                    editGalleryPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        window.addEventListener('click', function(event) {
            const modal = document.getElementById('editModal');
            
            // Check if the clicked area is the modal background
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>

</body>
</html>
