<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    @vite('resources/css/app.css')
</head>

<body class="m-5 bg-gray-100">

    <!-- Navigation Links -->
    <div class="space-y-6">
        <a href="{{ route('categories.index') }}" class="p-4 mt-10 text-center text-white bg-gray-500 rounded-lg">Categories</a>
        <a href="{{ route('subcategories.index') }}" class="p-4 mt-10 text-center text-white bg-green-500 rounded-lg">Sub-Categories</a>
        <a href="{{ route('products.index') }}" class="p-4 mt-10 text-center text-white bg-green-500 rounded-lg">Products</a>
    </div>

    <!-- Category Section -->
    <section class="w-full p-6 mt-8">
        <h2 class="mb-6 text-3xl font-semibold text-center text-red-800">Category</h2>

        <!-- Add Category Button -->
        <div class="mb-6 text-left">
            <button id="addCategoryButton" class="px-4 py-2 text-white bg-blue-800 rounded-lg hover:bg-blue-600">Add Category</button>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
        <div class="p-4 mb-4 text-white bg-green-500 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Categories Table -->
        <table class="w-full overflow-hidden bg-white border border-gray-300 rounded-lg shadow-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-lg font-medium text-center text-gray-700">S.No.</th>
                    <th class="px-6 py-4 text-lg font-medium text-center text-gray-700">Name</th>
                    <th class="px-6 py-4 text-lg font-medium text-center text-gray-700">Status</th>
                    <th class="px-6 py-4 text-lg font-medium text-center text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $index + 1 }}.</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">{{ ucfirst($category->status) }}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-800">
                        <!-- Edit Button -->
                        <button class="px-2 py-1 text-white bg-blue-400 rounded editButton"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-status="{{ $category->status }}"
                            data-image="{{ asset('storage/' . $category->image) }}"
                            onclick="openEditCategoryModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->status }}', '{{ asset('storage/' . $category->image) }}')">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 text-white bg-red-500 rounded deleteButton">Delete</button>
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
    <div class="fixed inset-0 flex items-center justify-center hidden overflow-y-auto bg-gray-500 bg-opacity-75" id="addCategoryModal">
        <div class="w-1/3 p-6 overflow-y-auto bg-white rounded-lg">
            <h2 class="mb-4 text-xl">Add Category</h2>
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                <div class="p-4 mb-4 text-white bg-red-500 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-4">
                    <label for="category_name" class="block">Name</label>
                    <input type="text" name="name" id="category_name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="category_status" class="block">Status</label>
                    <select name="status" id="category_status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="category_image" class="block font-medium text-gray-700">Upload Image</label>
                    <input type="file" name="image" id="category_image" accept="image/*" class="w-full p-3 mt-1 border border-gray-300 rounded-lg">
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden mt-3">
                        <img id="previewImage" src="" alt="Image Preview" class="object-cover w-full h-48 rounded-lg">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 mt-1 text-white bg-blue-500 rounded">Save</button>
            </form>
            <button id="closeAddCategoryModalButton" class="px-4 py-2 mt-4 text-white bg-red-500 rounded">Cancel</button>
        </div>
    </div>

    <!-- Edit Category Modal -->
<<<<<<< HEAD
    <div class="fixed inset-0 flex items-center justify-center hidden overflow-y-auto bg-gray-500 bg-opacity-75" id="editCategoryModal">
        <div class="w-1/3 p-6 overflow-y-auto bg-white rounded-lg">
            <h2 class="mb-4 text-xl">Edit Category</h2>
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
=======
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold mb-4">Edit Category</h3>
            <form action="editCategoryForm" method="POST" enctype="multipart/form-data">
>>>>>>> 1fe287b6d6ad5002288c5fbbba37510cc649abd0
                @csrf
                @method('PUT')
                @if ($errors->any())
                <div class="p-4 mb-4 text-white bg-red-500 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-4">
                    <label for="edit_category_name" class="block">Name</label>
                    <input type="text" name="name" id="edit_category_name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit_category_status" class="block">Status</label>
                    <select name="status" id="edit_category_status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editCategoryImage" class="block font-medium text-gray-700">Upload Image</label>
                    <input type="file" name="image" id="editCategoryImage" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg" onchange="previewEditImage(event)">
                    <!-- Image Preview Section -->
                    <div id="editCategoryImagePreview" class="mt-4">
                        <img id="editCategoryPreviewImage" src="" alt="Preview" class="hidden w-32 h-32 border rounded">
                        <p id="currentImageText" class="hidden mt-2 text-gray-600">No image uploaded</p>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 mt-1 text-white bg-blue-500 rounded">Save</button>
            </form>
            <button id="closeEditCategoryModalButton" class="px-4 py-2 mt-4 text-white bg-red-500 rounded">Cancel</button>
        </div>
    </div>

    <script>
        // Image Preview for Add Category Modal
        document.getElementById('category_image').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Image Preview for Edit Category Modal
        function previewEditImage(event) {
            const file = event.target.files[0];
            const previewImage = document.getElementById('editCategoryPreviewImage');
            const currentImageText = document.getElementById('currentImageText');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    currentImageText.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewImage.classList.add('hidden');
                currentImageText.classList.remove('hidden');
            }
        }

        // Open Add Category Modal
        document.getElementById('addCategoryButton').addEventListener('click', function () {
            document.getElementById('addCategoryModal').classList.remove('hidden');
        });

        // Close Add Category Modal
        document.getElementById('closeAddCategoryModalButton').addEventListener('click', function () {
            document.getElementById('addCategoryModal').classList.add('hidden');
        });

        // Open Edit Category Modal
        function openEditCategoryModal(id, name, status, image) {
            document.getElementById('editCategoryModal').classList.remove('hidden');
            document.getElementById('edit_category_name').value = name;
            document.getElementById('edit_category_status').value = status;
            document.getElementById('editCategoryForm').action = '/categories/' + id;

            // Set preview image for Edit Modal
            const previewImage = document.getElementById('editCategoryPreviewImage');
            const currentImageText = document.getElementById('currentImageText');
            previewImage.src = image;
            previewImage.classList.remove('hidden');
            currentImageText.classList.add('hidden');
        }

        // Close Edit Category Modal
        document.getElementById('closeEditCategoryModalButton').addEventListener('click', function () {
            document.getElementById('editCategoryModal').classList.add('hidden');
        });
    </script>

</body>

</html>
