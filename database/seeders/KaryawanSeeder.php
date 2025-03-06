<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(storage_path('app/data_karyawan.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        // $header = $csv->getHeader();
        // dd($header);

        foreach ($csv as $row) {
            DB::table('karyawans')->insert([
                'emp_id' => $row['emp_id'],
                'emp_name' => $row['emp_name'],
                'position_title' => $row['position_title'],
                'dept' => $row['dept'],
                'remarks' => $row['remarks'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
