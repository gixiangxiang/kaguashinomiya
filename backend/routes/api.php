<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\product;
use App\Models\products_image;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\error;

//設定時區
date_default_timezone_set('Asia/Taipei');


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
  $encodeData = json_encode($result, JSON_UNESCAPED_UNICODE);
  error_log("result data:" . $encodeData);
  return response()->json(['products' => $result]);
});

//關鍵字搜尋商品
Route::get('/product/search', function (Request $request) {
  $keyword = $request->input('keyword', '');
  try {
    $products = product::where('name', 'like', '%' . $keyword . '%')->get();
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
  } catch (\Exception $e) {
    return response('Product search failed:' . $e, 500);
  }
  return response()->json(['products' => $result]);
});

#endregion

#region 新增商品

//儲存產品與多個圖片
Route::post('/product/add', function (Request $request) {
  error_log("request data:" . $request);
  // 驗證請求資料
  $validator = Validator::make($request->all(), [
    'jsonData' => 'required|json',
    'mainImage' => 'required|file|mimes:jpeg,png,jpg,gif,webp', // 主圖片驗證
    'images.*' => 'file|mimes:jpeg,png,jpg,gif,webp', // 副圖片驗證
  ]);

  if ($validator->fails()) {
    return response($validator->errors(), 400);
  }
  try {
    // 解析 JSON 資料
    $jsonData = json_decode($request->input('jsonData'), true);
    //商品資料
    $product = new product();
    $product->name = $jsonData['name'];
    $product->colors = json_encode($jsonData['colors']);
    $product->size = json_encode($jsonData['size']);
    $product->description = $jsonData['description'];
    $product->price = $jsonData['price'];
    $product->save();

    $currentId = 0;
    //主圖片
    $imageNames = [];
    $mainImage = $request->file('mainImage');
    $mainImageName = date('YmdHis') . "_$currentId." . $mainImage->extension();
    $mainImage->move(public_path('images'), $mainImageName);
    array_push($imageNames, $mainImageName);
    $currentId++;

    // 儲存副圖片
    $otherImages = $request->file('images');
    if ($otherImages) {
      foreach ($otherImages as $image) {
        $imageName = date('YmdHis') . "_$currentId." . $image->extension();
        $image->move(public_path('images'), $imageName);
        array_push($imageNames, $imageName);
        $currentId++;
      }
    }

    //儲存圖片資料
    foreach ($imageNames as $imageName) {
      $product_image = new products_image();
      $product_image->src = $imageName;
      $product_image->isMain = $imageName == $mainImageName ? 1 : 0;
      $product_image->product_id = $product->id;
      $product_image->save();
    }
    return response('Product added successed', 200);
  } catch (\Exception $e) {
    return response('Product added failed:' . $e, 500);
  }
});

//儲存圖片至本地
Route::post('/product/image/testUpload', function (Request $request) {
  error_log("request data:" . $request);
  $image = $request->file('image');
  $imageName = date('YmdHis') . 'test.' . $image->extension();
  $image->move(public_path('images'), $imageName);
  return response()->json(['image' => $imageName]);
});

//儲存多個圖片
Route::post('/product/images/testUpload', function (Request $request) {
  error_log("request data:" . $request);
  $images = $request->file('images');
  $imageNames = [];
  foreach ($images as $image) {
    $imageName = date('YmdHis') . 'test.' . $image->extension();
    $image->move(public_path('images'), $imageName);
    array_push($imageNames, $imageName);
  }
  return response()->json(['images' => $imageNames]);
});

#endregion

#region 更新商品
//更新商品
Route::post('/product/update', function (Request $request) {
  // 驗證請求資料
  $validator = Validator::make($request->all(), [
    'jsonData' => 'required|json',
    'mainImage' => 'file|mimes:jpeg,png,jpg,gif,webp', // 主圖片驗證
    'images.*' => 'file|mimes:jpeg,png,jpg,gif,webp', // 副圖片驗證
  ]);

  if ($validator->fails()) {
    return response($validator->errors(), 400);
  }
  try {
    // 解析 JSON 資料
    $jsonData = json_decode($request->input('jsonData'), true);

    //商品資料
    $product = product::find($jsonData['id']);
    if (!$product) {
      return response('Product not found', 404);
    }
    $product->name = $jsonData['name'];
    $product->colors = json_encode($jsonData['colors']);
    $product->size = json_encode($jsonData['size']);
    $product->description = $jsonData['description'];
    $product->price = $jsonData['price'];
    $product->save();

    //圖片資料
    $imageNames = [];
    $images = $jsonData['images'];
    $dbImages = products_image::where('product_id', $product->id)->get();
    $lastId = intval(explode('.', explode('_', $dbImages[count($dbImages) - 1])[1])[0]);

    //主圖片處裡
    if ($request->file('mainImage')) {
      $currentId = $lastId + 1;
      $mainImage = $request->file('mainImage');
      $mainImageName = date('YmdHis') . "_$currentId." . $mainImage->extension();
      $mainImage->move(public_path('images'), $mainImageName);
      saveProductImage($product->id, $mainImageName, 1); //儲存圖片資料
      $lastId++;
    } else if (!$request->file('mainImage')) {
      $roriginalImage = products_image::where('product_id', $product->id)->where('isMain', 1)->get();
      if($roriginalImage->src !== $images['originalImage']){        
        cleanMainImage($product->id); //清除原本ismain為1的圖片
        //如果沒有上傳主圖片，則使用原始圖片，ismain設為1
        saveProductImage($product->id, $images['originalImage'], 1);
      }
    }

    // 刪除舊的圖片與資料庫資料    
    $retainedImages = $images['retainedImages']; //保留的圖片名陣列
    $dbSecondaryImages = products_image::where('product_id', $product->id)->where('isMain', 0)->get();
    foreach ($dbSecondaryImages as $dbSecondaryImage) {
      // 檢查此圖片是否在保留列表中
      if (!in_array($dbSecondaryImage->src, $retainedImages)) {
        // 不在保留列表中，需要刪除圖片檔案
        $oldImagePath = public_path('images/' . $dbSecondaryImage->src);
        if (file_exists($oldImagePath)) {
          unlink($oldImagePath); // 刪除實體檔案
        }
        $dbSecondaryImage->delete(); // 刪除資料庫記錄
      }
    }

    // 新副圖片
    if ($request->hasFile('images')) {
      $otherImages = $request->file('images');
      if ($otherImages) {
        foreach ($otherImages as $image) {
          $currentId = $lastId + 1;
          $imageName = date('YmdHis') . "_$currentId." . $image->extension();
          $image->move(public_path('images'), $imageName);
          array_push($imageNames, $imageName);
          $lastId++;
        }
      }
    }
    //儲存副圖片資料
    foreach ($imageNames as $imageName) {
      saveProductImage($product->id, $imageName, 0);
    }
    return response('Product updated successed', 200);
  } catch (\Exception $e) {
    return response('Product updated failed:' . $e, 500);
  }
});


function cleanMainImage($productId)
{
  //原本ismain為1的圖片設為0
  $dbMainImage = products_image::where('product_id', $productId)->where('isMain', 1);
  if ($dbMainImage) {
    $dbMainImage->isMain = 0;
    $dbMainImage->save();
  }
}

function saveProductImage($productId, $imageName, $isMain)
{
  $product_image = new products_image();
  $product_image->src = $imageName;
  $product_image->isMain = $isMain;
  $product_image->product_id = $productId;
  $product_image->save();
}

// 輔助函數：檢查字串是否為 null 或空
function nullOrEmptyString($str)
{
  return ($str === null || trim($str) === '');
}


#endregion
