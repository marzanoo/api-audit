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

        // Departemen dan emp_id yang tidak boleh dipakai
        $excludedDepts = ['GSO', 'ASD'];
        $excludedEmpName = 'VACANT';

        // Ambil karyawan yang dept-nya tidak termasuk excluded dan emp_id-nya bukan VACANT
        $filteredKaryawans = Karyawan::whereNotIn('dept', $excludedDepts)
            ->where('emp_name', '!=', $excludedEmpName)
            ->get();

        // Grouping by dept
        $karyawansByDept = $filteredKaryawans->groupBy('dept');
        $deptCount = $karyawansByDept->count();

        if ($deptCount > $areaCount) {
            $this->error('Jumlah departemen (setelah filter) lebih banyak dari jumlah area. Tidak bisa rolling!');
            return 1;
        }

        // Hitung distribusi area per dept
        $baseQuota = intdiv($areaCount, $deptCount);
        $extra = $areaCount % $deptCount;

        $newAssignments = [];

        foreach ($karyawansByDept as $dept => $karyawans) {
            $shuffled = $karyawans->shuffle();

            $quota = $baseQuota + ($extra > 0 ? 1 : 0);
            $extra--;

            if ($shuffled->count() < $quota) {
                $this->error("Dept $dept tidak punya cukup karyawan untuk memenuhi $quota PIC.");
                return 1;
            }

            for ($i = 0; $i < $quota; $i++) {
                $newAssignments[] = $shuffled->get($i)->emp_id;
            }
        }

        // Ambil pic_area lama
        $existingPics = Area::pluck('pic_area')->toArray();
        $newAssignments = collect($newAssignments)
            ->reject(fn($empId) => in_array($empId, $existingPics))
            ->shuffle();

        if ($newAssignments->count() < $areaCount) {
            $this->error('Tidak cukup karyawan unik (yang belum jadi PIC) untuk rolling semua area!');
            return 1;
        }

        // Rolling ke area
        foreach ($areas as $area) {
            $area->pic_area = $newAssignments->pop();
            $area->save();

            // Logging detail (optional)
            $this->info("Area {$area->nama_area} diisi oleh emp_id {$area->pic_area}");
        }

        $this->info('Rolling PIC selesai dengan pengecualian GSO, ASD, dan VACANT!');
        return 0;
    }
}
