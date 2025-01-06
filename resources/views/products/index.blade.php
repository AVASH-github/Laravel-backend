<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 m-5">
    <a href="{{route('categories.index')}}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Category</a>
    <a href="{{route('subcategories.index')}}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Sub-Category</a>
    <a href="{{route('products.index')}}" class="bg-gray-500 rounded-lg p-4 mt-10 text-white">Products</a>
    <a href="{{route('galleries.index')}}"  class="mt-10 rounded-lg bg-green-500 p-4 text-white">Gallery</a>

    <section class="w-full mt-8 p-6">
        <h2 class="text-3xl text-center font-semibold text-red-800">Products</h2>

        <div>
            <button id="addProductButton" class="bg-blue-800 rounded-lg px-4 py-4 text-white hover:bg-blue-600">Add Product</button>
        </div>
           <!-- Success Message -->
           @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4 mt-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Product Table -->
        <table class="w-full bg-white border border-red-800 shadow-lg rounded-lg overflow-hidden mt-4 ">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">S.No.</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Name</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Category</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Subcategory</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Description</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Status</th>
                    <th class="text-lg font-medium px-4 py-4 text-gray-700 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product )
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$index+1}}</td>
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$product->name}}</td>
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$product->category->name}}</td>
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$product->subcategory->name}}</td>
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$product->description}}</td>
                  <td class="text-gray-800 text-center text-sm px-6 py-4">{{$product->status}}</td>
                  <td class="px-6 py-4 text-center text-sm text-gray-800">
                    <!-- Edit Button -->
                    <button 
                        class="bg-blue-400 text-white py-1 px-2 rounded"
                        onclick="openEditProductModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->description }}', '{{ $product->status }}', '{{ $product->category_id }}', '{{ $product->subcategory_id }}', '{{ asset('storage/'.$product->image) }}')">
                        Edit
                    </button>
                    <!-- Delete Button -->
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded deleteButton">
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
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden overflow-y-auto" id="addProductModal">
        <div class="bg-white p-6 rounded-lg w-1/3 overflow-y-auto">
            <h2 class="text-xl mb-4">Add Product</h2>
            <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded mb-4">
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
                    <label for="image" class="block text-gray-700 font-medium">Upload Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full mt-1 p-3 border border-gray-300 rounded-lg">

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="previewImage" src="" alt="Image Preview" class="w-full h-48 object-cover rounded-lg">
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mt-1"> Save</button>
            </form>
            <button id="closeAddProductModalButton" type="submit" class="bg-red-500 text-white py-2 px-4 rounded mt-4"> Cancel</button>
        </div>
    </div>

    <!-- EDIT PRODUCT MODAL -->
    <div id="editProductModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl mb-4">Edit Product</h2>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded mb-4">
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
                    <label for="editImage" class="block text-gray-700 font-medium">Upload Image</label>
                    <input type="file" name="image" id="editProductImage" accept="image/jpeg, image/png, image/jpg, image/gif" class="w-full p-3 border border-gray-300 rounded-lg" onchange="previewImage(event)">
                    <div id="editImagePreview" class="mt-4">
                        <img id="editPreviewImage" src="" alt="Preview" class="w-32 h-32 rounded border hidden">
                        <p id="currentImageText" class="mt-2 text-gray-600" class="hidden">No image uploaded</p>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
            </form>
            <button id="closeEditProductModalButton" class="bg-red-500 text-white py-2 px-4 rounded mt-4">
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
