<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    @vite('resources/css/app.css')
</head>
<body class="m-5 bg-gray-100">
    <a href="{{route('categories.index')}}" class="p-4 mt-10 text-white bg-green-500 rounded-lg">Category</a>
    <a href="{{route('subcategories.index')}}" class="p-4 mt-10 text-white bg-green-500 rounded-lg">Sub-Category</a>
    <a href="{{route('products.index')}}" class="p-4 mt-10 text-white bg-gray-500 rounded-lg">Products</a>
    <a href="{{route('galleries.index')}}"  class="p-4 mt-10 text-white bg-green-500 rounded-lg">Gallery</a>

    <section class="w-full p-6 mt-8">
        <h2 class="text-3xl font-semibold text-center text-red-800">Products</h2>

        <div>
            <button id="addProductButton" class="px-4 py-4 text-white bg-blue-800 rounded-lg hover:bg-blue-600">Add Product</button>
        </div>
           <!-- Success Message -->
           @if (session('success'))
            <div class="p-4 mt-4 mb-4 text-white bg-green-500 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Product Table -->
        <table class="w-full mt-4 overflow-hidden bg-white border border-red-800 rounded-lg shadow-lg ">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">S.No.</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Name</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Category</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Subcategory</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Description</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Status</th>
                    <th class="px-4 py-4 text-lg font-medium text-center text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product )
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$index+1}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$product->name}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$product->category->name}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$product->subcategory->name}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$product->description}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">{{$product->status}}</td>
                  <td class="px-6 py-4 text-sm text-center text-gray-800">
                    <!-- Edit Button -->
                    <button 
                        class="px-2 py-1 text-white bg-blue-400 rounded"
                        onclick="openEditProductModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->description }}', '{{ $product->status }}', '{{ $product->category_id }}', '{{ $product->subcategory_id }}', '{{ asset('storage/'.$product->image) }}')">
                        Edit
                    </button>
                    <!-- Delete Button -->
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 text-white bg-red-500 rounded deleteButton">
                            Delete
                        </button>
                    </form>
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <!-- ADD PRODUCT MODAL -->
    <div class="fixed inset-0 flex items-center justify-center hidden overflow-y-auto bg-gray-500 bg-opacity-75" id="addProductModal">
        <div class="w-1/3 p-6 overflow-y-auto bg-white rounded-lg">
            <h2 class="mb-4 text-xl">Add Product</h2>
            <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
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
                <div>
                    <label for="name" class="block">Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="category_id" class="block">Category</label>
                    <select name="category_id" id="category_id" class="w-full p-2 border border-gray-300 rounded" required>
                        @foreach ($categories as $category )
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subcategory_id" class="block">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="w-full p-2 border border-gray-300 rounded">
                        @foreach ($subcategories as $subcategory)
                        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="description" class="block">Description</label>
                    <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded"></textarea>
                </div>
                <div>
                    <label for="status" class="block">Status</label>
                    <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="block font-medium text-gray-700">Upload Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full p-3 mt-1 border border-gray-300 rounded-lg">

                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden mt-3">
                        <img id="previewImage" src="" alt="Image Preview" class="object-cover w-full h-48 rounded-lg">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 mt-1 text-white bg-blue-500 rounded"> Save</button>
            </form>
            <button id="closeAddProductModalButton" type="submit" class="px-4 py-2 mt-4 text-white bg-red-500 rounded"> Cancel</button>
        </div>
    </div>

    <!-- EDIT PRODUCT MODAL -->
    <div id="editProductModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg w-1/3 max-h-[90vh] overflow-y-auto">
            <h2 class="mb-4 text-xl">Edit Product</h2>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
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
                    <label for="editProductName" class="block">Name</label>
                    <input type="text" name="name" id="editProductName" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editProductCategory" class="block">Category</label>
                    <select name="category_id" id="editProductCategory" class="w-full p-2 border border-gray-300 rounded" required>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editProductSubcategory" class="block">Subcategory</label>
                    <select name="subcategory_id" id="editProductSubcategory" class="w-full p-2 border border-gray-300 rounded" required>
                        <!-- Subcategories will be populated dynamically based on category -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editProductDescription" class="block">Description</label>
                    <textarea name="description" id="editProductDescription" class="w-full p-2 border border-gray-300 rounded" rows="4"></textarea>
                </div>
                <div class="mb-4">
                    <label for="editProductStatus" class="block">Status</label>
                    <select name="status" id="editProductStatus" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editImage" class="block font-medium text-gray-700">Upload Image</label>
                    <input type="file" name="image" id="editProductImage" accept="image/jpeg, image/png, image/jpg, image/gif" class="w-full p-3 border border-gray-300 rounded-lg" onchange="previewImage(event)">
                    <div id="editImagePreview" class="mt-4">
                        <img id="editPreviewImage" src="" alt="Preview" class="hidden w-32 h-32 border rounded">
                        <p id="currentImageText" class="mt-2 text-gray-600" class="hidden">No image uploaded</p>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">Save</button>
            </form>
            <button id="closeEditProductModalButton" class="px-4 py-2 mt-4 text-white bg-red-500 rounded">
                Cancel
            </button>
        </div>
    </div>

    <script>
    // Open Add Product Modal
    document.getElementById('addProductButton').addEventListener('click', () => {
        document.getElementById('addProductModal').classList.remove('hidden');
    });

    // Close Add Product Modal
    document.getElementById('closeAddProductModalButton').addEventListener('click', () => {
        document.getElementById('addProductModal').classList.add('hidden');
    });

    // Close Edit Product Modal
    document.getElementById('closeEditProductModalButton').addEventListener('click', () => {
        document.getElementById('editProductModal').classList.add('hidden');
    });

    // Open Edit Product Modal
    function openEditProductModal(id, name, description, status, categoryId, subcategoryId, currentImageUrl) {
        const form = document.getElementById('editProductForm');
        form.action = `/products/${id}`;

        document.getElementById('editProductName').value = name;
        document.getElementById('editProductDescription').value = description;
        document.getElementById('editProductStatus').value = status;
        document.getElementById('editProductCategory').value = categoryId;

        // Update subcategory select based on category
        const subcategorySelect = document.getElementById('editProductSubcategory');
        const subcategories = @json($subcategories);
        const filteredSubcategories = subcategories.filter(subcategory => subcategory.category_id === parseInt(categoryId));

        subcategorySelect.innerHTML = '';
        filteredSubcategories.forEach(subcategory => {
            const option = document.createElement('option');
            option.value = subcategory.id;
            option.textContent = subcategory.name;
            if (subcategory.id == subcategoryId) {
                option.selected = true;
            }
            subcategorySelect.appendChild(option);
        });

        // Handle image preview
        const editPreviewImage = document.getElementById('editPreviewImage');
        const currentImageText = document.getElementById('currentImageText');

        if (currentImageUrl) {
            editPreviewImage.src = currentImageUrl;
            editPreviewImage.classList.remove('hidden');
            currentImageText.classList.add('hidden');
        } else {
            editPreviewImage.classList.add('hidden');
            currentImageText.classList.remove('hidden');
        }

        document.getElementById('editProductModal').classList.remove('hidden');
    }
// Handle image preview when a new file is selected
document.getElementById('editProductImage').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('editPreviewImage');
    const currentImageText = document.getElementById('currentImageText');
    
    // Check if the selected file is an image and matches the required formats
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        // Check if the file type is valid
        if (allowedTypes.includes(file.type)) {
            // Create a URL for the selected file and set it as the source for the preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                currentImageText.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            alert('Please upload a valid image file (jpeg, png, jpg, gif).');
            // Reset the file input and hide the preview if invalid
            event.target.value = '';
            previewImage.classList.add('hidden');
            currentImageText.classList.remove('hidden');
        }
    } else {
        // If no file is selected, show the default current image text
        previewImage.classList.add('hidden');
        currentImageText.classList.remove('hidden');
    }
});

    
    window.addEventListener('click', function(event) {
    const modal = document.getElementById('editProductModal');
    
    // Check if the clicked area is the background (overlay) and not the modal itself
    if (event.target === modal) {
        modal.classList.add('hidden');
    }
});
// Handle image preview when a new file is selected
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('previewImage');
    const imagePreviewContainer = document.getElementById('imagePreview');

    // Check if the selected file is an image
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result; // Set the source of the preview image
            imagePreviewContainer.classList.remove('hidden'); // Show the preview container
        };
        reader.readAsDataURL(file); // Read the file as a data URL
    } else {
        imagePreviewContainer.classList.add('hidden'); // Hide the preview if no file is selected
    }
});
    </script>
</body>
</html>
