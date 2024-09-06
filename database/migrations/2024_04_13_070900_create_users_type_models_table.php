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
        Schema::create('users_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->bigInteger('chain_flow')->default(0);
            $table->tinyInteger('isDelete')->default(1);
            $table->tinyInteger('isActive')->default(1);
            $table->bigInteger('created_by')->default(0);
            $table->timestamps();
        });

        DB::table('users_types')->insert([
            ['name' => 'Super Admin', 'chain_flow' => 1, 'isDelete' => 1, 'isActive' => 1, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'chain_flow' => 2, 'isDelete' => 1, 'isActive' => 1, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'chain_flow' => 3, 'isDelete' => 1, 'isActive' => 1, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Supervisor', 'chain_flow' => 4, 'isDelete' => 1, 'isActive' => 1, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Employee', 'chain_flow' => 5, 'isDelete' => 1, 'isActive' => 1, 'created_by' => 1, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_types');
    }
};
