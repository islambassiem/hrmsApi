<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entity::factory()->create([
            'name_en' => 'Care and Science Medical Compnay',
            'name_ar' => 'شركة العناية والعلوم الطبية',
            'code' => '7001482160',
        ]);

        Entity::factory()->create([
            'name_en' => 'Shining Horizons Dental Center',
            'name_ar' => 'مجمع الآفاق لطب الأسنان',
        ]);
    }
}
