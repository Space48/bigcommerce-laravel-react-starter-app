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
        Schema::create('bigcommerce_stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_hash');
            $table->string('scope');
            $table->text('access_token');
            $table->unique('store_hash');
            $table->boolean('installed')->default(true);
            $table->string('domain')->nullable();
            $table->string('name')->nullable();
            $table->string('country')->nullable();
            $table->string('currency')->nullable();
            $table->string('plan_name')->nullable();
            $table->string('plan_level')->nullable();
            $table->string('plan_is_trial')->nullable();
            $table->string('status')->nullable();
            $table->integer('store_id')->nullable();
            $table->string('timezone_name')->nullable();
            $table->integer('timezone_raw_offset')->nullable();
            $table->integer('timezone_dst_offset')->nullable();
            $table->boolean('timezone_dst_correction')->nullable();
            $table->index('installed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bigcommerce_stores');
    }
};
