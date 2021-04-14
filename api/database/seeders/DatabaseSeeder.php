<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\House;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		House::truncate();
		$csv = file(database_path() . "/seeders/property-data.csv");
		$fields = explode(",", mb_strtolower(trim($csv[0])));
		foreach ($csv as $idx => $line) {
			if ($idx > 0) {
				$data = explode(",", trim($csv[$idx]));
				$create_line = [];
				foreach ($fields as $key => $name) {
					$create_line[$name] = $data[$key];
				}
				House::create($create_line);
			}
		}
	}
}
