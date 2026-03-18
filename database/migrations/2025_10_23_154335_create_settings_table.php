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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'service_charge_enabled',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'service_charge_rate',
                'value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'vat_enabled',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'vat_rate',
                'value' => '10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'table_count',
                'value' => '12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'seats_per_table',
                'value' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
