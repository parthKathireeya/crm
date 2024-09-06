<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRightsModel extends Model
{
    use HasFactory;
    protected $table = 'user_rights';
    protected $fillable = ['id', 'user_name', 'user_id', 'rights', 'isDelete', 'isActive', 'created_by', 'created_at', 'updated_at'];
}
