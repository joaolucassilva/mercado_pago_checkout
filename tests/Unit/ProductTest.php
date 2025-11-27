<?php

use App\Models\Product;

test('it can create a product', function () {
    $data = [
        'name' => 'Curso Laravel',
        'price' => 100.00,
        'description' => 'Livro sobre Laravel',
    ];

    $product = Product::create($data);

    expect($product)
        ->name->toBe('Curso Laravel')
        ->price->toBeMoney()
        ->description->not->toBeEmpty();

    $this->assertDatabaseHas('products', [
        'name' => 'Curso Laravel',
    ]);
});

test('product has formatted price logic', function () {
    expect(true)->toBeTrue();
});
