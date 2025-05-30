<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->string('bank_id', 50)->primary();
            $table->integer('enum_id')->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('taluk_town', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('name_in_invoice', 255)->nullable();
            $table->string('gst_no', 50)->nullable();
            $table->string('invoice_to', 255)->nullable();
            $table->text('bank_address')->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('customer_type', 50)->nullable();
            $table->string('version_type', 50)->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->decimal('business_amount', 15, 2)->nullable();
            $table->decimal('maintenance_amount', 15, 2)->nullable();
            $table->string('maintenance_freq', 50)->nullable();
            $table->string('our_support_person', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
