<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Updated</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-green-600">{{ $productName }} has been Updated To!</h1>
        <p class="mt-4"><strong class="font-semibold">Category:</strong> {{ $productCategory }}</p>
        <p><strong class="font-semibold">Subcategory:</strong> {{ $productSubcategory }}</p>
        <p><strong class="font-semibold">Description:</strong> {{ $productDescription }}</p>
        <p><strong class="font-semibold">Status:</strong> {{ $productStatus }}</p>
    </div>
</body>
</html>