<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 m-5">

    <!-- Navigation Links -->
    <div class="space-y-6">
        <a href="{{ route('categories.index') }}" class=" mt-10 rounded-lg bg-gray-500 p-4 text-white text-center">Categories</a>
        <a href="{{ route('subcategories.index') }}" class=" mt-10 rounded-lg bg-green-500 p-4 text-white text-center">Sub-Categories</a>
        <a href="{{ route('products.index') }}" class=" mt-10 rounded-lg bg-green-500 p-4 text-white text-center">Products</a>
    </div>

    <!-- Category Section -->
    <section class="w-full p-6 mt-8">
        <h2 class="text-3xl font-semibold text-center text-red-800 mb-6">Category</h2>

        <!-- Add Category Button -->
        <div class="text-left mb-6">
            <button id="addCategoryButton" class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Add Category</button>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Categories Table -->
        <table class="w-full bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">S.No.</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Name</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Status</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-center text-sm text-gray-800">{{ $index + 1 }}.</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-800">{{ ucfirst($category->status) }}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-800">
                        <!-- Edit Button -->
                        <button type="button" class="bg-blue-400 text-white py-1 px-2 rounded editButton"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-status="{{ $category->status }}"
                            data-image="{{ asset('storage/' . $category->image) }}">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded deleteButton">Delete</button>
</form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </section>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden ">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96 ">
            <h3 class="text-xl font-semibold text-center mb-4">Add New Category</h3>
            <form id="addCategoryForm" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <input type="text" name="name" id="name" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" placeholder="Enter category name" required>
              

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium">Status</label>
                    <select name="status" id="status" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium">Upload Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">

                     <!-- Image Preview -->
                <div id="imagePreview" class="mt-3 hidden">
                    <img id="previewImage" src="" alt="Image Preview" class="w-full h-48 object-cover rounded-lg">
                </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600">Add Category</button>
                </div>
            </form>
            <button id="closeAddCategoryModalButton" class="mt-4 text-red-500">Close</button>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold mb-4">Edit Category</h3>
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
           
                <div class="mb-4">
                    <label for="editName" class="bloc
                    k text-gray-700 font-medium">Category Name</label>
                    <input type="text" id="editName" name="name" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="editStatus" class="block text-gray-700 font-medium">Status</label>
                    <select name="status" id="editStatus" class="w-full mt-1 p-3 border border-gray-300 rounded-lg" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Image Upload for Edit -->
                <div class="mb-4">
                    <label for="editImage" class="block text-gray-700 font-medium">Upload Image</label>
                    <input type="file" name="image" id="editImage" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg" onchange="previewImage(event)">
                    <div id="editImagePreview" class="mt-4">
                        <img id="editPreviewImage" src="" alt="Preview" class="w-32 h-32 rounded border hidden">
                        <p id="currentImageText" class="mt-2 text-gray-600">No image uploaded</p>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600">Update Category</button>
                </div>
            </form>
            <button id="closeEditModalButton" class="mt-4 text-red-500">Close</button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Image Preview
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = event.target.id === 'editImage' ? document.getElementById('editPreviewImage') : document.getElementById('previewImage');
            const currentImageText = document.getElementById('currentImageText');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
                if (currentImageText) {
                    currentImageText.textContent = "No image uploaded";
                }
            }
        }

        // Open and Close Modals
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        document.getElementById('addCategoryButton').addEventListener('click', function() {
            document.getElementById('addCategoryModal').classList.remove('hidden');
        });

        // Close the Add Category Modal
        document.getElementById('closeAddCategoryModalButton').addEventListener('click', function() {
            document.getElementById('addCategoryModal').classList.add('hidden');
        });
        document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const previewImage = document.getElementById('previewImage');
                
                previewImage.src = e.target.result;
                preview.classList.remove('hidden'); // Show the preview
            };

            reader.readAsDataURL(file);
        }
    });

        // Edit Button Logic
        document.querySelectorAll('.editButton').forEach(button => {
            button.addEventListener('click', (event) => {
                const categoryId = event.target.getAttribute('data-id');
                const categoryName = event.target.getAttribute('data-name');
                const categoryStatus = event.target.getAttribute('data-status');
                const categoryImage = event.target.getAttribute('data-image');

                document.getElementById('editName').value = categoryName;
                document.getElementById('editStatus').value = categoryStatus;
               

                const editPreviewImage = document.getElementById('editPreviewImage');
                const currentImageText = document.getElementById('currentImageText');
                if (categoryImage) {
                    editPreviewImage.src = categoryImage;
                    editPreviewImage.classList.remove('hidden');
                    currentImageText.textContent = "Current Image";
                }

                openModal('editModal');
            });
        });

        // Close modals
        document.getElementById('closeAddCategoryModalButton').addEventListener('click', () => closeModal('addCategoryModal'));
        document.getElementById('closeEditModalButton').addEventListener('click', () => closeModal('editModal'));
    </script>
</body>
</html>
