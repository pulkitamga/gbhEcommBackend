<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orderitems extends Model
{
    use HasFactory;
    protected $table='orderitems';
    protected $fillable=[
        'order_id',
        'product_id',
        'qty',
        'price',
      
    ];

    //first fk then pk
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
 
   
    protected $with=['product'];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');  //your model name and path
    }
}
