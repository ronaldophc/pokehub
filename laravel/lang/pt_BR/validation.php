<?php

return [
    'required'  => 'O campo :attribute é obrigatório.',
    'email'     => 'Informe um :attribute válido.',
    'string'    => 'O campo :attribute deve ser um texto.',
    'min'       => [
        'string'  => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
    ],
    'max'       => [
        'string'  => 'O campo :attribute pode ter no máximo :max caracteres.',
        'numeric' => 'O campo :attribute pode ser no máximo :max.',
    ],
    'confirmed' => 'A confirmação de :attribute não confere.',
    'unique'    => 'Este :attribute já está em uso.',
    'boolean'   => 'O campo :attribute deve ser verdadeiro ou falso.',
    'integer'   => 'O campo :attribute deve ser um número inteiro.',
    'nullable'  => '',

    'current_password' => 'A senha atual está incorreta.',

    'attributes' => [
        'email'           => 'e-mail',
        'password'        => 'senha',
        'name'            => 'nick',
        'species'         => 'espécie',
        'notes'           => 'observações',
        'house_name'      => 'nome da house',
        'invite_code'     => 'código de convite',
        'current_password' => 'senha atual',
        'new_password'    => 'nova senha',
        'password_confirmation' => 'confirmação de senha',
    ],
];
