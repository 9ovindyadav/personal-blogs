<?php

use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('messages', function (Blueprint $collection) {
            $collection->id();
            $collection->uuid('conversation_id');
            $collection->unsignedBigInteger('author_id');
            $collection->string('author_name');
            $collection->text('content');
            $collection->enum('content_type',['text','image','video']);
            $collection->enum('status',['sent','delivered','seen']);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('messages');
    }
}
