<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = ['id', 'user_type', 'name', 'surname', 'mobile_number', 'email', 'address', 'user_name', 'password', 'profile_picture', 'personal_rights', 'createdBy', 'uper_chain_ids', 'uper_chain', 'isDelete', 'isActive', 'created_at', 'updated_at'];

}
