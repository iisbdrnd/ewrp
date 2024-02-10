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
            $table->integer('project_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->integer('auth_code')->nullable()->comment = 'For External Login API';
            $table->string('auth_key')->nullable()->comment = 'For general API';
            $table->string('secret_key')->nullable()->comment = 'For general API';
            $table->string('timezones')->nullable()->comment = 'timezones.value';
            $table->integer('timezone_id')->nullable()->comment = 'Foreign key: timezones.id';
            $table->tinyInteger('email_verified')->nullable()->comment = '1=Yes, 2=No';
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('status')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('valid')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        // DB::table('users')->insert(
        //     array('id'=> 1, 'project_id' => 1, 'name'=> 'User', 'email'=> 'user@gmail.com', 'email_verified_at'=> NULL, 'password'=> '$2y$10$IWGhwRHClTWxTGk91.UXceS8jPB/P2WV3yDEsqo0qz3/GWQgznMjC', 'remember_token'=>NULL, 'created_at'=> '2019-10-27 22:19:28', 'updated_at'=> NULL, 'auth_code'=> NULL,'auth_key'=> NULL,'secret_key'=> NULL,'timezones'=> NULL,'timezone_id'=> NULL,'email_verified'=> NULL,'verification_code'=> NULL,'status'=> NULL,'created_by'=> NULL,'created_by_type'=> NULL,'updated_by'=> NULL,'updated_by_type'=> NULL,'deleted_by'=> NULL,'deleted_by_type'=> NULL,'deleted_at'=> NULL,'valid'=> 1)
        // );
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
