<?php

namespace Jozenetoz\FilamentPtbrFormFields\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jozenetoz\FilamentPtbrFormFields\FilamentPtbrFormFields
 */
class FilamentPtbrFormFields extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Jozenetoz\FilamentPtbrFormFields\FilamentPtbrFormFields::class;
    }
}
