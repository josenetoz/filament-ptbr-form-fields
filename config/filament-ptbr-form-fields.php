<?php

return [
    'brasil_api' => [
        'url' => env('BRASIL_API_URL', 'https://brasilapi.com.br/api/'),
        'cep' => [
            'url' => env('BRASIL_API_CEP_URL', 'https://brasilapi.com.br/api/cep/v2/'),
        ],
        'cnpj' => [
            'url' => env('BRASIL_API_CNPJ_URL', 'https://brasilapi.com.br/api/cnpj/v1/'),
        ],
    ],
];
