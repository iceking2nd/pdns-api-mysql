<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id','comments_domain_id_ibfk')
                ->references('id')
                ->on('domains')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name',255);
            $table->string('type',10);
            $table->integer('modified_at');
            $table->string('account',40)->nullable()->charset('utf8')->collation('utf8_unicode_ci');
            $table->text('comment')->charset('utf8');
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->index(['name','type'],'comments_name_type_idx');
            $table->index(['domain_id','modified_at'],'comments_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
