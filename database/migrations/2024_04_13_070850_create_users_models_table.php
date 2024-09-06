<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_type')->default(0);
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('mobile_number', 11)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->binary('user_name', 30);
            $table->binary('password');
            $table->string('profile_picture', 255)->nullable();
            $table->tinyInteger('personal_rights')->default(0);
            $table->integer('createdBy')->default(0);
            $table->text('uper_chain_ids')->nullable();
            $table->text('uper_chain')->nullable();
            $table->integer('isDelete')->default(1);
            $table->integer('isActive')->default(1);
            $table->timestamps();
        });

        // Inserting default data
        DB::table('users')->insert([
            'user_type' => 1,
            'name' => 'parth',
            'surname' => 'katheeriya',
            'mobile_number' => '7016962218',
            'email' => 'parth@gmail.com',
            'address' => 'Rajkot',
            'user_name' => 'Parth',
            'password' => '$2y$10$8tin6f6kZYc1i5myZ9iA8.72vz6APRIm9Z2c9ltI6KhN3D0u4yFxa',
            'profile_picture' => '',
            'personal_rights' => 0,
            'createdBy' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
