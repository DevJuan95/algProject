<?php

return [
    'custom' => [
        'ingredients.*.name' => [
            'required' => 'El campo nombre es obligatorio',
            'string' => 'El campo nombre debe ser una cadena de texto',
            'max' => 'El campo nombre no puede tener más de :max caracteres',
        ],
        'ingredients.*.quantity' => [
            'required' => 'El campo cantidad es obligatorio',
            'integer' => 'El campo cantidad debe ser un número entero',
            'min' => 'El campo cantidad no puede ser menor a :min',
        ],
        'name' => [
            'required' => 'El campo nombre es obligatorio',
            'string' => 'El campo nombre debe ser una cadena de texto',
            'max' => 'El campo nombre no puede tener más de :max caracteres',
        ],
        'stock' => [
            'required' => 'El campo stock es obligatorio',
            'integer' => 'El campo stock debe ser un número entero',
            'min' => 'El campo stock no puede ser menor a :min',
        ],
        'orderId' => [
            'required' => 'El campo orderId es obligatorio',
            'integer' => 'El campo orderId debe ser un número entero',
        ],
    ],
    'attributes' => [
        'ingredients' => 'ingredientes',
        'ingredients.*.name' => 'nombre',
        'ingredients.*.quantity' => 'cantidad',
    ],
];
