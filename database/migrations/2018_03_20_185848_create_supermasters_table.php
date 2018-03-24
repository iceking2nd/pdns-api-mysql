<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupermastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supermasters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip',64);
            $table->string('nameserver',255);
            $table->string('account')->charset('utf8')->collation('utf8_unicode_ci');
            $table->index(['ip','nameserver']);
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supermasters');
    }
}
