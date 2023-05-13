<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    protected $table = 'payments';

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
