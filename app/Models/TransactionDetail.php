<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = "transaction_details";
    protected $guarded = ['id'];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}

public function transaction()
{
    return $this->belongsTo(Transaction::class);
}
}
