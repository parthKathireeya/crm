<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usertype_rights', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 60);
            $table->integer('user_type_id');
            $table->longText('rights')->charset('utf8mb4')->collation('utf8mb4_bin')->comment('view rights = [view = 1], personal data view rights = [view_type_ = 1], chainwish data view rights  = [view_type_ = 2], all data view rights  = [view_type_ = 3], add rights = [add = 1], edit rights = [edit = 1], delete rights = [delete = 1]');
            $table->tinyInteger('isDelete')->default(1);
            $table->tinyInteger('isActive')->default(1);
            $table->bigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertype_rights');
    }
};
