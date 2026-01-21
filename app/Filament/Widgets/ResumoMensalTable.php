<?php

namespace App\Filament\Widgets;

use App\Models\ResumoMensal;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Filament\Exports\ResumoMensalExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;




class ResumoMensalTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getMaxContentWidth(): string|\UnitEnum|null
    {
        return 'full';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getResumoMensalQuery())
            ->defaultSort('id', 'asc')
            ->headerActions([
                ExportAction::make()
                    ->label('Exportar')
                    ->exporter(ResumoMensalExporter::class)
                    ->columnMapping(false),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('mes')
                    ->label('Mês/Ano'),

                Tables\Columns\TextColumn::make('entradas')
                    ->label('Entradas')
                    ->money('BRL'),

                Tables\Columns\TextColumn::make('saidas')
                    ->label('Saídas')
                    ->money('BRL'),

                Tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo')
                    ->money('BRL')
                    ->color(fn ($state) => (float) $state < 0 ? 'danger' : 'success'),
            ])
            ->paginated(false)
            ->headerActions([
            Action::make('exportarPdf')
                ->label('Exportar PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $rows = $this->getResumoMensalQuery()->get();

                    return response()->streamDownload(function () use ($rows) {
                        echo Pdf::loadView('relatorios.resumo-mensal-pdf', [
                            'rows' => $rows,
                        ])->output();
                    }, 'resumo-mensal.pdf');
                }),
            ]);

    }

    private function getResumoMensalQuery(): Builder
    {
        $subQuery = DB::table('entradas')
            ->selectRaw("DATE_FORMAT(data, '%Y%m') as id")
            ->selectRaw("DATE_FORMAT(data, '%m/%Y') as mes")
            ->selectRaw("SUM(valor) as entradas")
            ->selectRaw("0 as saidas")
            ->groupBy('id', 'mes')
            ->unionAll(
                DB::table('gastos')
                    ->selectRaw("DATE_FORMAT(data, '%Y%m') as id")
                    ->selectRaw("DATE_FORMAT(data, '%m/%Y') as mes")
                    ->selectRaw("0 as entradas")
                    ->selectRaw("SUM(valor) as saidas")
                    ->groupBy('id', 'mes')
            );

        return ResumoMensal::query()
            ->fromSub($subQuery, 'movimentos')
            ->selectRaw('id, mes')
            ->selectRaw('SUM(entradas) as entradas')
            ->selectRaw('SUM(saidas) as saidas')
            ->selectRaw('SUM(entradas) - SUM(saidas) as saldo')
            ->groupBy('id', 'mes')
            ->orderByRaw('CAST(id AS UNSIGNED) ASC');

    }
}
