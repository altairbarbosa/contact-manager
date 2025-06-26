<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::factory()->create([
            'name' => 'John Doe',
            'contact' => '123456789',
            'email' => 'john.doe@example.com',
        ]);

        Contact::factory()->create([
            'name' => 'Jane Smith',
            'contact' => '987654321',
            'email' => 'jane.smith@example.com',
        ]);

        Contact::factory()->count(10)->create(); // Generate 10 more random contacts
    }
}