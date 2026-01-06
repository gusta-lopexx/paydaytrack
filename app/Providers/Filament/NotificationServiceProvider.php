<?php

use App\Models\Gasto;
use Filament\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        DatabaseNotifications::pollingInterval('30s');

        DatabaseNotifications::getNotificationsUsing(function () {
            return Gasto::atrasadas()
                ->orderBy('data')
                ->get()
                ->map(function ($gasto) {
                    return Notification::make()
                        ->title('Despesa atrasada')
                        ->body(
                            "{$gasto->descricao} - venc. " .
                            $gasto->data->format('d/m/Y')
                        )
                        ->danger()
                        ->actions([
                            ActionsAction::make('ver')
                                ->label('Ver despesas')
                                ->url(route('filament.admin.resources.gastos.index')),
                        ]);
                });
        });
    }
}
