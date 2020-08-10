<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id');
            $table->string('title');
            $table->string('video_id');
            $table->string('thumbnail_source');
            $table->string('thumbnail');
            $table->text('description');
            $table->bigInteger('views_count');
            $table->bigInteger('rating_count');
            $table->string('rating_average');
            $table->smallInteger('is_viewed')->default(0);
            $table->datetime('published');
            $table->datetime('updated');
            $table->smallInteger('viewd_index')->nullable();
            $table->string('audio_file_uri')->nullable();
            $table->smallInteger('split_count')->nullable();
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
        Schema::dropIfExists('entities');
    }
}
