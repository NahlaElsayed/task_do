<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['title','description','image','price','category_id'];

    public function category()
    {
        return $this->belongsTo(category::class,'category_id');
    }
   
}