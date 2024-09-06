<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsertypeRightsModel extends Model
{
    use HasFactory;
    protected $table = 'usertype_rights';
    protected $fillable = ['id', 'user_type', 'user_type_id', 'rights', 'isDelete', 'isActive', 'created_by', 'created_at', 'updated_at'];
}
