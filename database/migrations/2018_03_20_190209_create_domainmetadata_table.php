<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainmetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domainmetadata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id','domainmetadata_domain_id_ibfk')
                ->references('id')
                ->on('domains')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('kind',32);
            $table->text('content');
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->index(['domain_id','kind'],'domainmetadata_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domainmetadata');
    }
}
