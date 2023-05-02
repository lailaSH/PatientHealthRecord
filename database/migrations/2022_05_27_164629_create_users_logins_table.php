<?php

use App\Models\Admin;
use App\Models\UsersLogin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('UserID');
            $table->string('email')->unique();
            $table->string('Type');
            $table->timestamps();
        });
        $user=new UsersLogin();
        $user->UserID=1;
        $user->email='admin@gmail.com';
        $user->Type='admin';
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_logins');
    }
}
