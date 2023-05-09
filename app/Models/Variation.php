<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $table='variation';
    protected $fillable=[
        'product_id',
        'color_id',
        'size',
        'quantity'
    ];
    public function variationColors()
    {
        return $this->belongsTo(Color::class,'product_id','color_id','id');
    }
}
