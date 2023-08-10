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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('billing_profile_id');
            $table->integer('response_id');
            $table->string('name');
            $table->text('url');
            $table->string('key');
            $table->integer('level');
            $table->string('crm_id');
            $table->integer('related_bonds_counts');
            $table->string('parent');
            $table->text('parent_url');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
