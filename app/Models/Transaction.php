<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "date",
        "discount",
        "total",
        "bayar",
        "kembali",
        "user_id"
    ];

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
