<?php

namespace App\Models;

use App\Models\Color;
use App\Models\Category;
use App\Models\Variation;
use App\Models\Orderitems;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory,Sluggable;
    protected $table='products';
    protected $fillable=[
        'category_id',
        'slug',
        'name',
        'description',
        'brand',
        'selling_price',
        'original_price',
        'qty',
        'image',
        'status',
    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
   
    
    protected $with=['category'];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    //protected $with=['productColors'];
    public function productColors():HasMany
    {
        return $this->hasMany(Variation::class,'product_id','id');
    }
    public function colors():HasMany
    {
        return $this->hasMany(Color::class,'color_id','id');
    }
   
    
   /* public function orderproduct() {
        return $this->hasMany(Orderitems::class,'product_id','id');
    }*/
   
    public function orderproduct() {
        return $this->belongsTo(Orderitems::class,'product_id','id');
    }
}
