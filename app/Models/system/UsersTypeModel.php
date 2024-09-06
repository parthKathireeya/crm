<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersTypeModel extends Model
{
    use HasFactory;

    protected $table = 'users_types';

    protected $fillable = ['id', 'name', 'chain_flow', 'isDelete', 'isActive', 'created_by', 'created_at', 'updated_at'];
}
