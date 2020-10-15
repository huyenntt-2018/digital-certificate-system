<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('pkcs12')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->longText('certificate');
            $table->longText('serial_number');
            $table->integer('type');
            $table->date('valid_from_time');
            $table->date('valid_to_time');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
                'user_id',
        ]);
        Schema::dropIfExists('certificates');
    }
}
