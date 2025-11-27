<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'cliente@teste.com'],
            [
                'name' => 'Cliente Teste',
                'password' => Hash::make('password'),
                'is_premium' => false,
            ]
        );

        $product = Product::query()->firstOrCreate(
            ['name' => 'Ebook Laravel'],
            [
                'price' => 100.00,
                'description' => 'Livro sobre Laravel',
            ]
        );

        $this->command->info('Dados de testes criados com sucesso!');
        $this->command->info("User: {$user->email} / Password: password");
        $this->command->info("Product: {$product->name} (R$ {$product->price})");


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
