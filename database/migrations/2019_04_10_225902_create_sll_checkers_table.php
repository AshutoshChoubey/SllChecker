<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSllCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sll_checkers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain_name')->unique();
            $table->string('ssl_expiry')->nullable();
            $table->string('ssl_issuer')->nullable();
            // $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sll_checkers');
    }
}
