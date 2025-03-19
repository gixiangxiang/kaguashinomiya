<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_image extends Model
{
  public $timestamps = false;
  use HasFactory;

  // 定義與 Product 的關聯
  public function product()
  {
      return $this->belongsTo(Product::class);
  }
}
