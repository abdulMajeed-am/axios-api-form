<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up()
    // {
    //     Schema::create('banks', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('name');
    //         $table->string('address');
    //         $table->string('contact_person');
    //         $table->date('license_expiry_date');
    //         $table->string('support_person');
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
