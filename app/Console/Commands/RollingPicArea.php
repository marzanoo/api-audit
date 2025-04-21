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

        $areas = Area::all();
        $areaCount = $areas->count();

        // 1. Filter karyawan
        $excludedDepts = ['GSO', 'ASD'];
        $excludedEmpName = 'VACANT';

        $filteredKaryawans = Karyawan::whereNotIn('dept', $excludedDepts)
            ->where('emp_name', '!=', $excludedEmpName)
            ->get();

        // 2. Group by dept
        $karyawansByDept = $filteredKaryawans->groupBy('dept');
        $deptCount = $karyawansByDept->count();

        if ($deptCount > $areaCount) {
            $this->error("Jumlah dept ($deptCount) lebih banyak dari jumlah area ($areaCount). Rolling gagal.");
            return 1;
        }

        // 3. Hitung quota per dept
        $baseQuota = intdiv($areaCount, $deptCount);
        $extra = $areaCount % $deptCount;

        // 4. Ambil emp_id yang sudah jadi PIC sebelumnya
        $existingPics = Area::pluck('pic_area')->toArray();

        $newAssignments = [];

        foreach ($karyawansByDept as $dept => $karyawans) {
            // Filter emp_id yang belum pernah jadi PIC
            $available = $karyawans->filter(function ($k) use ($existingPics) {
                return !in_array($k->emp_id, $existingPics);
            })->shuffle();

            // Hitung jatah dept ini
            $quota = $baseQuota + ($extra > 0 ? 1 : 0);
            if ($extra > 0) $extra--;

            // Cek apakah cukup kandidat
            if ($available->count() < $quota) {
                $this->error("Dept $dept tidak punya cukup karyawan baru untuk jatah $quota PIC.");
                return 1;
            }

            // Ambil sejumlah quota
            for ($i = 0; $i < $quota; $i++) {
                $newAssignments[] = $available->get($i)->emp_id;
            }
        }

        // Final check
        if (count($newAssignments) < $areaCount) {
            $this->error("Tidak cukup emp_id unik untuk rolling semua area!");
            return 1;
        }

        // 5. Rolling ke area
        $newAssignments = collect($newAssignments)->shuffle();

        foreach ($areas as $area) {
            $area->pic_area = $newAssignments->pop();
            $area->save();

            $this->info("Area {$area->nama_area} diisi oleh emp_id {$area->pic_area}");
        }

        $this->info("Rolling PIC selesai sukses!");
        return 0;
    }
}
