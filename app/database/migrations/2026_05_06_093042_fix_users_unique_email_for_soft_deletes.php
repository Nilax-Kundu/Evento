<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old unique index
            $table->dropUnique(['email']);
            
            // Create a partial unique index (Postgres specific optimization)
            // This allows the same email to exist multiple times if deleted_at is NOT NULL,
            // but only once where deleted_at IS NULL.
            DB::statement('CREATE UNIQUE INDEX users_email_unique_active ON users (email) WHERE deleted_at IS NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('DROP INDEX users_email_unique_active');
            $table->unique('email');
        });
    }
};
