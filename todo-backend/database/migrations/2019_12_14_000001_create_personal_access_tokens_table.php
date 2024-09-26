<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type', 191);
            $table->unsignedBigInteger('tokenable_id');
            $table->timestamps();
        });

        // Adding the index with specified length after creating the table
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Adding the index with a length limit
            $table->index(['tokenable_type(191)', 'tokenable_id']); // Correct way to specify length in index
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
