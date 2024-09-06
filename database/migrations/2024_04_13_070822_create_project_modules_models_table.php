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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(0);
            $table->string('name', 50)->unique()->collation('utf8mb4_general_ci');
            $table->text('slug');
            $table->string('url', 200)->unique()->collation('utf8mb4_general_ci')->nullable();
            $table->string('icon_class', 50);
            $table->string('description', 255)->nullable();
            $table->integer('show_no')->nullable();
            $table->integer('isShow')->default(0);
            $table->integer('isDelete')->default(1);
            $table->integer('isActive')->default(1);
            $table->timestamps();
        });

        DB::table('system_modules')->insert([
            ['type' => 2, 'name' => 'System Modules','slug' => 'system_modules', 'url' => 'modules', 'icon_class' => 'fas fa-cubes', 'description' => 'modules', 'show_no' => null, 'isShow' => 0, 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 2, 'name' => 'Users','slug' => 'users', 'url' => 'users', 'icon_class' => 'fas fa-user', 'description' => 'users', 'show_no' => 1, 'isShow' => 1, 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 2, 'name' => 'Rights','slug' => 'rights', 'url' => 'rights', 'icon_class' => 'fas fa-key', 'description' => 'rights', 'show_no' => 5, 'isShow' => 0, 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 2, 'name' => 'Profile','slug' => 'profile', 'url' => null, 'icon_class' => 'fas fa-user fa-sm fa-fw mr-2 text-gray-400', 'description' => 'profile', 'show_no' => null, 'isShow' => 0, 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('system_modules');
    }
};
