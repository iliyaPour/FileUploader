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
    Schema::create('shared_files', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->string('original_name');
        $table->string('file_path');
        $table->unsignedBigInteger('file_size');
        $table->string('mime_type')->nullable();
        $table->timestamp('expires_at');
        $table->unsignedInteger('download_limit')->nullable();
        $table->unsignedInteger('downloads_count')->default(0);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_files');
    }
};
