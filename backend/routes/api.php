<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\product;
use App\Models\products_image;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

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

#region 查詢商品


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

  //搜尋商品
  Route::get('/product/search/{name}', function ($name) {
    $products = product::where('name', 'like', '%' . $name . '%')->get();
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

  $encodeData = json_encode($result, JSON_UNESCAPED_UNICODE);

  error_log("result data:" . $encodeData);
  return response()->json(['products' => $result]);
});

#endregion

#region 新增商品
//新增商品
Route::post('/product/added', function (Request $request) {
  error_log("request data:" . $request);
  try {
    //確認資料是否為空
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'colors' => 'required|array',
      'size' => 'required|array',
      'description' => 'required',
      'price' => 'required|numeric',
      'images' => 'required|array',
      'images.*.src' => 'required',
      'images.*.isMain' => 'required|boolean',
    ]);

    if ($validator->fails()) {
      return response($validator->errors(), 400);
    }
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
    return response('Product added failed:' . $e, 500);
  }
  return response('Product added successed', 200);
});



//儲存圖片至本地
Route::post('/product/image/upload', function (Request $request) {
  error_log("request data:" . $request);
  $image = $request->file('image');
  $imageName = time() . '.' . $image->extension();
  $image->move(public_path('images'), $imageName);
  return response()->json(['image' => $imageName]);
});

//儲存多個圖片
Route::post('/product/images/upload', function (Request $request) {
  error_log("request data:" . $request);
  $images = $request->file('images');
  $imageNames = [];
  foreach ($images as $image) {
    $imageName = time() . '.' . $image->extension();
    $image->move(public_path('images'), $imageName);
    array_push($imageNames, $imageName);
  }
  return response()->json(['images' => $imageNames]);
});

//儲存產品與多個圖片
Route::post('/product/add', function (Request $request) {
  error_log("request data:" . $request);
  $validator = Validator::make($request->all(), [
    'jsonData' => 'required|json',
    'images' => 'required|array',
  ]);

  if ($validator->fails()) {
    return response($validator->errors(), 400);
  }
  try {
    //商品資料
    $jsonData = json_decode($request->jsonData, true);
    $product = new product();
    $product->name = $jsonData->name;
    $product->colors = json_encode($jsonData->colors);
    $product->size = json_encode($jsonData->size);
    $product->description = $jsonData->description;
    $product->price = $jsonData->price;
    $product->save();

    //主圖片
    $imageNames = [];
    $mainImage = $request->file('mainImage');
    error_log("images data:" . $mainImage);
    $mainImageName = time() . '.' . $mainImage->extension();
    $mainImage->move(public_path('images'), $mainImageName);
    array_push($imageNames, $mainImageName);

    //副圖片
    $otherImages = $request->file('otherImages');
    foreach ($otherImages as $image) {
      $imageName = time() . '.' . $image->extension();
      $image->move(public_path('images'), $imageName);
      array_push($imageNames, $imageName);
    }

    //儲存圖片資料
    foreach ($imageNames as $imageName) {
      $product_image = new products_image();
      $product_image->src = $imageName;
      $product_image->isMain = $imageName == $mainImageName ? 1 : 0;
      $product_image->product_id = $product->id;
      $product_image->save();
    }
  } catch (\Exception $e) {
    return response('Product added failed:' . $e, 500);
  }
  return response('Product added successed', 200);
  
});
#endregion
