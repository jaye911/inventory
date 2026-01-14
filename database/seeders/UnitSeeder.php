<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Meter', 'abbreviation' => 'm'],
            ['name' => 'Centimeter', 'abbreviation' => 'cm'],
            ['name' => 'Piece', 'abbreviation' => 'pcs'],
            ['name' => 'Liter', 'abbreviation' => 'L'],
            ['name' => 'Milliliter', 'abbreviation' => 'mL'],
            ['name' => 'Box', 'abbreviation' => 'box'],
            ['name' => 'Dozen', 'abbreviation' => 'dz'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['abbreviation' => $unit['abbreviation']],
                $unit
            );
        }
    }
}
