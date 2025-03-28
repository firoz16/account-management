<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes , HasUuids;

    protected $keyType = 'string';  // ✅ UUID is a string
    public $incrementing = false;   // ✅ Disable auto-incrementing

    protected $fillable = ['user_id', 'account_name', 'account_number', 'account_type', 'currency', 'balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
