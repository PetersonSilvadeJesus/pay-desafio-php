<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'encoded_image_pix')) {
                $table->longText('encoded_image_pix')->nullable();
            }
            if (!Schema::hasColumn('payments', 'payload_code_pix')) {
                $table->longText('payload_code_pix')->nullable();
            }
            if (!Schema::hasColumn('payments', 'expiration_date_pix')) {
                $table->dateTime('expiration_date_pix')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'encoded_image_pix')) {
                $table->dropColumn('encoded_image_pix');
            }
            if (Schema::hasColumn('payments', 'payload_code_pix')) {
                $table->dropColumn('payload_code_pix');
            }
            if (Schema::hasColumn('payments', 'expiration_date_pix')) {
                $table->dropColumn('expiration_date_pix');
            }
        });
    }
};
