<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
//            action = Column(String, index=True)
            $table->string('action');
//            confirm = Column(Boolean, default=False)
            $table->boolean('confirm')->default(false);
//            club_id = Column(String)
            $table->string('club_id');
//            utoken = Column(String)
            $table->string('utoken');
//            phone = Column(String)
            $table->string('phone');
//            type = Column(String)
            $table->string('type');
//            ticket_id = Column(String)
            $table->string('ticket_id');
//            appointment_id = Column(String)
            $table->string('appointment_id');
//            promocode = Column(String)
            $table->string('promocode');
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
        Schema::dropIfExists('orders');
    }
}
