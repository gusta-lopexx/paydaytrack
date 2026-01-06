<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();

            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data');

            // Mesmas categorias das despesas
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();

            // Reutiliza tipos_despesa (Fixo / Variável / Eventual)
            $table->foreignId('tipo_despesa_id')
                ->constrained('tipo_despesas')
                ->cascadeOnDelete();

            // Recorrência (ex: salário 12 meses)
            $table->uuid('recorrencia_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
