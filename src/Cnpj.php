<?php

namespace Jozenetoz\FilamentPtbrFormFields;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\{Component, TextInput};
use Filament\Forms\Set;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Http};
use Illuminate\Validation\ValidationException;
use Livewire\Component as Livewire;

class Cnpj extends TextInput
{
    public function brasilApi(string $mode = 'suffix', string $errorMessage = 'CNPJ inválido.', array $setFields = [], array $formatters = []): static
    {
        $brasilApiRequest = function ($state, $livewire, $set, $component, $errorMessage, array $setFields, array $formatters) {

            $livewire->validateOnly($component->getKey());
            $state   = preg_replace('/[.\-\/]/', '', $state);
            $request = Http::get(config('filament-ptbr-form-fields.brasil_api.cnpj.url') . $state)->json();

            if (
                blank($request) ||
                Arr::has($request, 'erro') ||
                (isset($request['name']) && $request['name'] === 'BadRequestError')
            ) {
                $msg = Arr::get($request, 'errors.0.message', Arr::get($request, 'message', $errorMessage));

                throw ValidationException::withMessages([
                    $component->getKey() => $msg,
                ]);
            }

            foreach ($setFields as $key => $value) {
                $fieldValue = Arr::get($request, $value);

                if ($value === 'cep') {
                    $fieldValue = preg_replace('/(\d{5})(\d{2})/', '$1-$2', $fieldValue);
                }

                $set($key, ($formatters[$key] ?? fn ($v) => $v)($fieldValue));
            }
        };

        $this
            ->minLength(14)
            ->mask('99.999.999/9999-99')
            ->afterStateUpdated(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest, $formatters) {
                $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields, $formatters);
            })
            ->suffixAction(function () use ($mode, $errorMessage, $setFields, $brasilApiRequest, $formatters) {
                if ($mode === 'suffix') {
                    return Action::make('search-action')
                        ->label('Buscar CNPJ')
                        ->icon('heroicon-o-magnifying-glass')
                        ->action(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest, $formatters) {
                            $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields, $formatters);
                        })
                        ->cancelParentActions();
                }
            })
            ->prefixAction(function () use ($mode, $errorMessage, $setFields, $brasilApiRequest, $formatters) {
                if ($mode === 'prefix') {
                    return Action::make('search-action')
                        ->label('Buscar CNPJ')
                        ->icon('heroicon-o-magnifying-glass')
                        ->action(function ($state, Livewire $livewire, Set $set, Component $component) use ($errorMessage, $setFields, $brasilApiRequest, $formatters) {
                            $brasilApiRequest($state, $livewire, $set, $component, $errorMessage, $setFields, $formatters);
                        })
                        ->cancelParentActions();
                }
            });

        return $this;
    }
}
