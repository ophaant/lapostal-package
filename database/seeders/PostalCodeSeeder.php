<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Ophaant\Lapostal\Models\City;
use Ophaant\Lapostal\Models\Province;
use Ophaant\Lapostal\Models\Subdistrict;
use Ophaant\Lapostal\Models\Village;

class PostalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = Storage::disk('local')->get('postal_code/json/postal-code.json');
        $jsonString = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $jsonData);
        $json = json_decode($jsonString, true);

        $this->command->getOutput()->progressStart(count($json));
        foreach ($json as $data) {
            $province = Province::firstOrCreate([
                'province_code' => $data['province_code'],
                'province_name' => $data['province_name'],
                // Add other province fields as needed
            ]);

            $city = City::firstOrCreate([
                'city_code' => $data['city_code'],
                'city_name' => $data['city_name'],
                'province_id' => $province->id,
                // Add other city fields as needed
            ]);

            $subdistrict = Subdistrict::firstOrCreate([
                'subdistrict_code' => $data['subdistrict_code'],
                'subdistrict_name' => $data['subdistrict_name'],
                'city_id' => $city->id,
                // Add other subdistrict fields as needed
            ]);

            Village::create([
                'village_code' => $data['village_code'],
                'village_name' => $data['village_name'],
                'subdistrict_id' => $subdistrict->id,
                // Add other village fields as needed
            ]);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();

    }
}
