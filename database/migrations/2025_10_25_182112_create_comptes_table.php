<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutCompte;
use App\Enums\TypeCompte;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numero_compte')->unique();
            $table->date('date_creation')->index();
            $table->string('devise')->default('XOF');
            $table->string('statut_compte')->default(StatutCompte::Actif->value)->index();
            $table->string('type_compte')->default(TypeCompte::Cheque->value)->index();
            $table->integer('version')->default(1);
            $table->uuid('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
