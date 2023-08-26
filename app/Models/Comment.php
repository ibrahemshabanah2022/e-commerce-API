<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    protected $fillable = ['content', 'product_id', 'user_id'];

    use HasFactory;
    public function product()
    {

        return $this->belongsTo(Product::class);
    }
}
