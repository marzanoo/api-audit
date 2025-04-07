<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\Karyawan;

class RollingPicArea extends Command
{
    protected $signature = 'rolling:pic';
    protected $description = 'Rolling PIC Area pastikan pic baru tidak sama dengan pic lama';

    public function handle()
    {
        $this->info('Rolling PIC dimulai...');

        // 1. Ambil semua pic_area yang sekarang lagi dipake
        $existingPics = Area::pluck('pic_area')->toArray();

        // 2. Ambil semua emp_id dari karyawan
        $availableKaryawans = Karyawan::whereNotIn('emp_id', $existingPics)->pluck('emp_id')->shuffle();

        $areas = Area::all();

        // 3. Cek jumlah karyawan tersedia cukup atau tidak
        if ($availableKaryawans->count() < $areas->count()) {
            $this->error('Jumlah karyawan baru tidak cukup untuk semua area!');
            return 1;
        }

        // 4. Assign karyawan baru yang belum pernah jadi pic_area
        foreach ($areas as $area) {
            $newPic = $availableKaryawans->pop(); // ambil satu karyawan
            $area->pic_area = $newPic;
            $area->save();
        }

        $this->info('Rolling PIC selesai tanpa ada pic_area yang sama dengan sebelumnya!');
        return 0;
    }
}
