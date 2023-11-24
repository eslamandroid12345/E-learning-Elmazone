<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('اسم الطالب');
            $table->date('birth_date')->comment('تاريخ الميلاد');
            $table->boolean('login_status')->comment('حاله الدخول من الاجهزه اللوحيه');
            $table->unsignedBigInteger('season_id')->comment('الفصل الدراسي');
            $table->unsignedBigInteger('country_id')->comment('اسم المدينه');
            $table->string('phone')->comment('رقم هاتف الطالب');
            $table->string('father_phone')->comment('رقم هاتف ولي الامر');
            $table->longText('image')->comment('صوره الطالب')->nullable();
            $table->enum('center',['in','out'])->default('in');
            $table->enum('user_status',['active','not_active'])->default('active');
            $table->text('user_status_note')->nullable();
            $table->string('code')->comment('كود الطالب')->unique();
            $table->date('date_start_code')->comment('تاريخ بدايه الاشتراك');
            $table->date('date_end_code')->comment('تاريخ نهايه الاشتراك');
            $table->json('subscription_months_groups')->comment('شهور الاشتراك');
            $table->longText('access_token')->comment('توكن الدخول من خلال الاجهزه اللوحيه');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
