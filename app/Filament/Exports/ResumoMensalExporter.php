<?php

namespace App\Filament\Exports;

use App\Models\ResumoMensal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ResumoMensalExporter extends Exporter
{
    protected static ?string $model = ResumoMensal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('mes')->label('Mês/Ano'),
            ExportColumn::make('entradas')->label('Entradas'),
            ExportColumn::make('saidas')->label('Saídas'),
            ExportColumn::make('saldo')->label('Saldo'),
        ];
    }

    public function getFileName(Export $export): string
    {
        return 'resumo-mensal';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Seu arquivo de exportação do Resumo Mensal está pronto para download.';
    }
}