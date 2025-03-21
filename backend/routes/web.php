<?php

use App\Models\product;
use App\Models\products_image;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

Route::get('/products', function () {
  return ['products' => \App\Models\Product::all()];
});

Route::get('/products/{id}', function ($id) {

  return ['product' => \App\Models\Product::find($id)];
});

Route::get('/produimages/{id}', function ($id) {

  return ['produimage' => \App\Models\products_image::find($id)];
});

Route::get('/product-json/{id}', function ($id) {
  $product = product::find($id);

  if (!$product) {
    return response()->json(['error' => 'Product not found'], 404);
  }

  $images = products_image::where('product_id', $id)->get();

  $result = [
    'product' => [
      'id' => $product->id,
      'name' => $product->name,
      'colors' => $product->colors,
      'size' => $product->size,
      'description' => $product->description,
      'price' => $product->price,
      'images' => $images->map(function ($image) {
        return [
          'id' => $image->id,
          'src' => $image->src,
          'isMain' => $image->isMain,
          'product_id' => $image->product_id,
        ];
      }),
    ],
  ];

  return response()->json($result);
});

Route::get('/products-json/all', function () {
  $products = product::all();

  $result = $products->map(function ($product) {
    $images = products_image::where('product_id', $product->id)->get();

    // 將字串形式的陣列轉換為實際PHP陣列
    $colors = json_decode(str_replace("'", '"', $product->colors), true);
    $size = json_decode(str_replace("'", '"', trim($product->size)), true);

    return [
      'id' => $product->id,
      'name' => $product->name,
      'colors' => $colors,
      'size' => $size,
      'description' => $product->description,
      'price' => $product->price,
      'images' => $images->map(function ($image) {
        return [
          'id' => $image->id,
          'src' => $image->src,
          'isMain' => $image->isMain,
          // 'product_id' => $image->product_id,
        ];
      }),
    ];
  });

  return response()->json(['products' => $result]);
});
