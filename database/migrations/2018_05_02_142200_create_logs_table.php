<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'logs',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->bigIncrements('id');
                $table->string('instance')->index();
                $table->string('channel')->index();
                $table->enum('level', [
                    'DEBUG',
                    'INFO',
                    'NOTICE',
                    'WARNING',
                    'ERROR',
                    'CRITICAL',
                    'ALERT',
                    'EMERGENCY'
                ])->default('INFO');
                $table->text('message');
                $table->mediumText('context');
                $table->timestamps();
            }
        );

        if (DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
            DB::statement("ALTER TABLE `logs` comment '操作日志'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logs');
    }
}
