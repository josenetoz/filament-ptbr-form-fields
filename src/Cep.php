<?php

namespace Jozenetoz\FilamentPtbrFormFields;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Component as Livewire;

class Cep extends TextInput
{
    public function brasilApi(string $mode = 'suffix', string $errorMessage = 'CEP inválido.', array $setFields = []): static
    {
        $brasilApiRequest = function ($state, $livewire, $set, $component, $errorMessage, array $setFields) {
            $livewire->validateOnly($component->getKey());

            $request = Http::get(config('filament-ptbr-form-fields.brasil_api.cep.url') . $state)->json();

            if (
                blank($request) ||
                Arr::has($request, 'erro') ||
                (isset($request['name']) && $request['name'] === 'CepPromiseError')
            ) {
                $msg = Arr::get($request, 'errors.0.message', Arr::get($request, 'message', $errorMessage));
                throw ValidationException::withMessages([
                    $component->getKey() => $msg,
                ]);
            }
            
            foreach ($setFields as $key => $value) {
                $set($key, Arr::get($request, $value));
            }
        };

        $this
            ->minLength(9)
            ->mask('99999-999')
            ->afterStateUpdated(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest) {
                $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields);
            })
            ->suffixAction(function () use ($mode, $errorMessage, $setFields, $brasilApiRequest) {
                if ($mode === 'suffix') {
                    return Action::make('search-action')
                        ->label('Buscar CEP')
                        ->icon('heroicon-o-magnifying-glass')
                        ->action(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest) {
                            $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields);
                        })
                        ->cancelParentActions();
                }
            })
            ->prefixAction(function () use ($mode, $errorMessage, $setFields, $brasilApiRequest) {
                if ($mode === 'prefix') {
                    return Action::make('search-action')
                        ->label('Buscar CEP')
                        ->icon('heroicon-o-magnifying-glass')
                        ->action(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest) {
                            $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields);
                        })
                        ->cancelParentActions();
                }
            });

        return $this;
    }
}
