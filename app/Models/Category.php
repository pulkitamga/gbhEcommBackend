<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
 
    protected $table="category";
    protected $fillable = ['slug','name','description','status','parent_id'];

    
    public function childs()
    {
        return $this->hasMany(Category::class,'parent_id');
    }
 
  //protected $guarded = [];
  protected $with=['category'];
  // this function is used to make relationship in normal larvel for other like js we
  // use $with like above
  public function category()
  {
      return $this->belongsTo(Category::class,'parent_id','id');
  }
  
  
}
