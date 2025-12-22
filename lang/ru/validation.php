<?php

return [
    'required' => 'Поле :attribute необходимо заполнить',
    'string' => 'Поле :attribute должно быть строкой',
    'max' => [
        'array' => 'The :attribute field must not have more than :max items.',
        'file' => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string' => 'The :attribute Максимальная длинна :max символов',
    ],
    'unique' => 'The :attribute has already been taken.',
    'min' => [
        'array' => 'The :attribute field must have at least :min items.',
        'file' => 'The :attribute field must be at least :min kilobytes.',
        'numeric' => 'The :attribute field must be at least :min.',
        'string' => ':attribute минимальная длина :min символов',
    ],
    'same' => 'The :attribute field must match :other.',
    'in' => 'The selected :attribute is invalid.',
];
