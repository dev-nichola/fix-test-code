<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = [
        "product_name",
        "product_description",
        "product_price_capital",
        "product_price_sell"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'user_id', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id', 'id');
    }
    
}
