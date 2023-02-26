<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('welcome_message')->nullable();
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_zone')->default('Asia/Dhaka');
            $table->tinyInteger('time_format')->default(1)->comment('1 = am/pm,  2 = 24 hr');
            $table->text('available_time')->nullable()->comment('');
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
