<?php

test('globals', function () {
    // Proíbe dd(), dump() e ray() no código de produção
    expect(['dd', 'dump', 'ray'])
        ->not->toBeUsed();
});

test('models', function () {
    // Garante que todos os Models estendam a classe base correta
    expect('App\Models')
        ->toBeClasses()
        ->toExtend('Illuminate\Database\Eloquent\Model');
});
