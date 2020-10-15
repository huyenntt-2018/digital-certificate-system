<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('gender');
            $table->date('birthday');
            $table->unsignedBigInteger('id_number')->unique(); // Số cmnd
            $table->date('id_date'); // Ngày cấp số cmnd.
            $table->string('id_address'); // Địa chỉ cmnd.
            $table->string('permanent_residence'); // Thường trú
            $table->string('staying_address'); // Tạm trú
            $table->string('job');
            $table->string('company');
            $table->string('avatar');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign([
                'role_id',
        ]);
        Schema::dropIfExists('users');
    }
}
