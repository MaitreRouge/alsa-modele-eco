<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $primaryKey = "token";
    protected $keyType = "string";
    public $incrementing = false;

    public function user(): User
    {
        return User::find($this->uid);
    }
}
