<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategories</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 m-5">
<a href={{route('categories.index') }} class="mt-10 rounded-lg bg-green-500 p-4 text-white">Categories</a>
<a href={{route('subcategories.index')}} class="mt-10 rounded-lg bg-gray-500 p-4 text-white">Sub-Categories</a>
<a href="{{route('products.index')}}" class="bg-green-500 rounded-lg p-4 mt-10 text-white">Products</a>
<section class="w-full p-6 mt-8">
<h2 class="text-3xl font-semibold text-center text-red-800 mb-6">Subcategories</h2>

   
    <div class="text-left mb-6">
    <button id="addSubcategoryButton" class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
            Add Subcategory
        </button>
    </div>
        <!-- Button to Open the Add Subcategory Modal -->
       

        @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @elseif (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif
        <!-- Table of Subcategories -->
        <table class="w-full bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">
            <thead  class="bg-gray-100">
                <tr>
                <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">S.No.</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Name</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Category</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Status</th>
                    <th class="px-6 py-4 text-lg font-medium text-gray-700 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategories as $index => $subcategory)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-center text-sm text-gray-800">{{ $index + 1 }}.</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-800">{{ $subcategory->name }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-800">{{ $subcategory->category->name }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-800">{{ ucfirst($subcategory->status) }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-800">
                            <!-- Edit Button -->
                            <button 
                                class="bg-blue-400 text-white py-1 px-2 rounded"
                                onclick="openEditModal({{ $subcategory->id }}, '{{ $subcategory->name }}', '{{ $subcategory->status }}')">
                                Edit
                            </button>
                            <!-- Delete Button -->
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" class="inline deleteForm">
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
    </div>
    </section>

    <!-- Add Subcategory Modal -->
    <div id="addSubcategoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl mb-4">Add Subcategory</h2>
            <form action="{{ route('subcategories.store') }}" method="POST">
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
                <div class="mb-4">
                    <label for="name" class="block">Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block">Category</label>
                    <select name="category_id" id="category_id" class="w-full p-2 border border-gray-300 rounded" required>
                        <!-- <option value="">--Select an active category --</option> -->
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="status" class="block">Status</label>
                    <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
            </form>
            <button id="closeAddSubcategoryModalButton" class="bg-red-500 text-white py-2 px-4 rounded mt-4">
                Cancel
            </button>
        </div>
    </div>

    <!-- Edit Subcategory Modal -->
    <div id="editSubcategoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl mb-4">Edit Subcategory</h2>
            <form id="editForm" method="POST">
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
                    <label for="editName" class="block">Name</label>
                    <input type="text" name="name" id="editName" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editCategory" class="block">Category</label>
                    <select name="category_id" id="editCategory" class="w-full p-2 border border-gray-300 rounded" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editStatus" class="block">Status</label>
                    <select name="status" id="editStatus" class="w-full p-2 border border-gray-300 rounded">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
            </form>
            <button id="closeEditSubcategoryModalButton" class="bg-red-500 text-white py-2 px-4 rounded mt-4">
                Cancel
            </button>
        </div>
    </div>

    <script>
        // Add Subcategory Modal
        const addSubcategoryModal = document.getElementById('addSubcategoryModal');
        const addSubcategoryButton = document.getElementById('addSubcategoryButton');
        const closeAddSubcategoryModalButton = document.getElementById('closeAddSubcategoryModalButton');

        addSubcategoryButton.addEventListener('click', () => {
            addSubcategoryModal.classList.remove('hidden');
        });

        closeAddSubcategoryModalButton.addEventListener('click', () => {
            addSubcategoryModal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === addSubcategoryModal) {
                addSubcategoryModal.classList.add('hidden');
            }
        });

        // Edit Subcategory Modal
        const editSubcategoryModal = document.getElementById('editSubcategoryModal');
        const closeEditSubcategoryModalButton = document.getElementById('closeEditSubcategoryModalButton');

        function openEditModal(id, name, status) {
            // Update the form action for the edit form
            const formAction = `{{ route('subcategories.update', ':id') }}`.replace(':id', id);
            document.getElementById('editForm').action = formAction;

            // Set the form values for editing
            document.getElementById('editName').value = name;
            document.getElementById('editStatus').value = status;

            // Show the modal
            editSubcategoryModal.classList.remove('hidden');
        }

        closeEditSubcategoryModalButton.addEventListener('click', () => {
            editSubcategoryModal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === editSubcategoryModal) {
                editSubcategoryModal.classList.add('hidden');
            }
        });

        // Confirm Delete
        const deleteButtons = document.querySelectorAll('.deleteButton');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this subcategory?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
