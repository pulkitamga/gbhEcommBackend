<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable=[
        'firstname',
        'lastname',
        'phone',
        'email',
        'address',
        'city',
        'state',
       ' zipcode',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'order_status',
        'remark',
    ];
    protected $with=['orderitems'];
    public function orderitems():HasMany
    {
        return $this->hasMany(Orderitems::class,'order_id','id');
    }

   
}
