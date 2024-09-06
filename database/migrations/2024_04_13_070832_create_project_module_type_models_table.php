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
        Schema::create('project_module_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->tinyInteger('isDelete')->default(1);
            $table->tinyInteger('isActive')->default(1);
            $table->timestamps();
        });

        DB::table('project_module_type')->insert([
            ['name' => 'Admin Module', 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'System Module', 'isDelete' => 1, 'isActive' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_module_type');
    }
};
