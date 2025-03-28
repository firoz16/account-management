<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';  // ✅ UUID is a string
    public $incrementing = false;   // ✅ Disable auto-incrementi

    protected $fillable = ['account_id', 'type', 'amount', 'description'];
}
