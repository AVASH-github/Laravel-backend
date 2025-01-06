<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body class="m-5">
   

    <a href={{route('categories.index') }} class="mt-10 rounded-lg bg-green-500 p-4 text-white">Categories</a>
    <a href={{route('subcategories.index')}} class="mt-10 rounded-lg bg-green-500 p-4 text-white">Sub-Categories</a>
    <a href="{{ route('products.index') }}" class="mt-10 rounded-lg bg-green-500 p-4 text-white">Products</a>
    <a href="{{route('galleries.index')}}"  class="mt-10 rounded-lg bg-green-500 p-4 text-white">Gallery</a>
  

</body>
</html>