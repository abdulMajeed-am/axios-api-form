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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id', 50);
            $table->unsignedInteger('BranchId');
            $table->string('taluk_town', 255)->nullable();
            $table->text('bank_address')->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->decimal('business_amount', 15, 2)->nullable();
            $table->decimal('maintenance_amount', 15, 2)->nullable();
            $table->string('maintenance_freq', 50)->nullable();
            $table->string('our_support_person', 100)->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('bank_id')->on('banks')->onDelete('cascade');
            $table->unique(['bank_id', 'BranchId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
