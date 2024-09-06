<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModuleTypeModel extends Model
{
    use HasFactory;
    protected $table = 'project_module_type';
    protected $fillable = ['id', 'type', 'name', 'slug', 'url', 'icon_class', 'description', 'show_no', 'isShow', 'isDelete', 'isActive', 'created_at', 'updated_at'];

}
