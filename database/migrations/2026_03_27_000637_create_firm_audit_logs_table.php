<?php
// database/migrations/2026_03_27_000000_create_firm_audit_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firm_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained('firms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // created, updated, deleted, banned, etc.
            $table->string('field')->nullable(); // Which field changed
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['firm_id', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firm_audit_logs');
    }
};
