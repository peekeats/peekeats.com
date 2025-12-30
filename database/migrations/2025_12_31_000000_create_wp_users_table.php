<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wordpress')->create('users', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('user_login', 60)->default('');
            $table->string('user_pass', 255)->default('');
            $table->string('user_nicename', 50)->default('');
            $table->string('user_email', 100)->default('');
            $table->string('user_url', 100)->default('');
            // Use nullable to avoid strict-mode issues with '0000-00-00 00:00:00'
            $table->dateTime('user_registered')->nullable();
            $table->string('user_activation_key', 255)->default('');
            $table->integer('user_status')->default(0);
            $table->string('display_name', 250)->default('');

            $table->index('user_login', 'user_login_key');
            $table->index('user_nicename');
            $table->index('user_email');

            // Match WP defaults where practical
            $table->engine = 'MyISAM';
            $table->charset = 'utf8mb3';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('wordpress')->dropIfExists('users');
    }
};
