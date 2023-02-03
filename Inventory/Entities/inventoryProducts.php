<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
class inventoryProducts extends Model
{
    protected $table = "inventory_products";
    protected $fillable = ["inventory_id" , "product_id" , "amount_after_inventory" , "Amount_difference",
    "created_at" , "updated_at"];

}
