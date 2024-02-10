<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('designation')->nullable();
            $table->string('image')->nullable();
            $table->string('website')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('bio')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('valid')->default(1);
        });

        DB::table('admins')->insert(
            array('id'=> 0, 'name'=> 'Administrator', 'username'=> 'admin', 'email'=> 'admin@gmail.com', 'email_verified_at'=> NULL, 'password'=> '$2y$10$IWGhwRHClTWxTGk91.UXceS8jPB/P2WV3yDEsqo0qz3/GWQgznMjC', 'website'=>'','designation'=>'', 'image'=>'', 'company'=>'', 'address'=>'','city'=>'', 'country'=>'', 'bio'=>'','created_at'=> '2019-10-27 22:19:28', 'updated_at'=> NULL, 'valid' => 1)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
