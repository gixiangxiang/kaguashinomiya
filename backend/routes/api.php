<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\product;
use App\Models\products_image;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// 指定商品的資料
Route::get('/product-json/{id}', function ($id) {
  $product = product::find($id);

  if (!$product) {
    return response()->json(['error' => 'Product not found'], 404);
  }

  $images = products_image::where('product_id', $id)->get();
  // 將字串形式的陣列轉換為實際PHP陣列
  $colors = json_decode(str_replace("'", '"', $product->colors), true);
  $size = json_decode(str_replace("'", '"', trim($product->size)), true);

  $result = [
    'product' => [
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
          'product_id' => $image->product_id,
        ];
      }),
    ],
  ];

  return response()->json($result);
});

// 所有商品的資料
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

//新增商品
Route::post('/product/add', function (Request $request) {
  try {
    $product = new product();
    $product->name = $request->name;
    $product->colors = json_encode($request->colors);
    $product->size = json_encode($request->size);
    $product->description = $request->description;
    $product->price = $request->price;
    $product->save();

    $images = $request->images;
    foreach ($images as $image) {
      $product_image = new products_image();
      $product_image->src = $image['src'];
      $product_image->isMain = $image['isMain'];
      $product_image->product_id = $product->id;
      $product_image->save();
    }
  } catch (\Exception $e) {
    return response('Product added failed', 500);
  }
  return response('Product added successed', 200);
});
