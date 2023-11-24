<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         كوبونات الخصم للطلبه
         */
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('coupon');
            $table->enum('discount_type', array('per','value'))->comment('نوع الخصم');
            $table->double('discount_amount',12);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('total_usage')->comment('عدد مستخدمين هذا لكوبون');
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
        Schema::dropIfExists('discount_coupons');
    }
}
