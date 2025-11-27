<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit
| test case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// AQUI ESTÁ O SEGREDO:
// Isso diz: "Para todos os testes na pasta Unit e Feature, carregue o Laravel (TestCase)
// e limpe o banco de dados a cada teste (RefreshDatabase)."
uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeMoney', function () {
    return $this->toBeNumeric()->toBeGreaterThanOrEqual(0);
});
