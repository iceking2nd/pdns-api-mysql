<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->bigInteger('id',true)->unsigned();
            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id','records_domain_id_ibfk')
                ->references('id')
                ->on('domains')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name',255)->nullable();
            $table->string('type',10)->nullable();
            $table->string('content',64000)->nullable();
            $table->integer('ttl')->nullable();
            $table->integer('prio')->nullable();
            $table->integer('change_date')->nullable();
            $table->tinyInteger('disabled')->default(0);
            $table->string('ordername',255)->nullable()->collation('latin1_bin');
            $table->tinyInteger('auth')->default(1);
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->index(['name','type'],'nametype_index');
            $table->index('domain_id','domain_id');
            $table->index('ordername','ordername');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
