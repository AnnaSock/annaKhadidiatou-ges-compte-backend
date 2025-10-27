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
        Schema::table('comptes', function (Blueprint $table) {
            $table->string('motif_blocage')->nullable()->after('statut_compte');
            // after('statut_compte') → optionnel, pour placer la colonne après statut_compte
        });
    }

    public function down(): void
    {
        Schema::table('comptes', function (Blueprint $table) {
            $table->dropColumn('motif_blocage');
        });
    }
};
