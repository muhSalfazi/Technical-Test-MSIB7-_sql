<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function nilaiRT()
    {
        // Mengambil data dengan COUNT per pelajaran
        $results = DB::table('nilai')
            ->select(
                'nama',
                'nisn',
                'nama_pelajaran',
                DB::raw('COUNT(*) AS jumlah_pelajaran')
            )
            ->where('materi_uji_id', 7)
            ->where('nama_pelajaran', '<>', 'pelajaran khusus')
            ->groupBy('nama', 'nisn', 'nama_pelajaran')
            ->orderBy('nama')
            ->orderBy('nisn')
            ->get();

        // Mengelompokkan data berdasarkan 'nama' dan 'nisn'
        $groupedResults = $results->groupBy(function ($item) {
            // Menggunakan nama dan nisn sebagai key gabungan
            return $item->nama . '|' . $item->nisn;
        })->map(function ($items, $key) {
            // Memecah key menjadi nama dan nisn
            list($namaSiswa, $nisnSiswa) = explode('|', $key);

            // Mengubah data menjadi format yang diinginkan
            return [
            'nomor' => $items->keys()->first() + 1, 
            'nama' => $namaSiswa, // 1. Nama Siswa
            'nilaiRt' => $items->map(function ($item) {
            return $item->nama_pelajaran . ":" .  $item->jumlah_pelajaran; 
            })->values()->all(),
            'nisn' => $nisnSiswa, // 5. NISN Siswa
            ];

        });

        return response()->json($groupedResults->values());
    }

   public function nilaiST()
   {
   $result = DB::table('nilai')
   ->select(
   'nama',
   'nisn',
   DB::raw('SUM(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) AS verbal'),
   DB::raw('SUM(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) AS kuantitatif'),
   DB::raw('SUM(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) AS penalaran'),
   DB::raw('SUM(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) AS figural'),
   DB::raw('
   SUM(CASE
   WHEN pelajaran_id = 44 THEN skor * 41.67
   WHEN pelajaran_id = 45 THEN skor * 29.67
   WHEN pelajaran_id = 46 THEN skor * 100
   WHEN pelajaran_id = 47 THEN skor * 23.81
   ELSE 0
   END
   ) AS total')
   )
   ->where('materi_uji_id', 4)
   ->groupBy('nisn', 'nama')
   ->orderBy('total', 'DESC')
   ->get();

   //menyusun ulang hasilnya agar sesuai dengan format JSON 
   $formattedResult = $result->map(function ($item, $index) {
   return [
   'nomor' => $index + 1, 
   'nama' => $item->nama, 
   'listNilai' => [
   'verbal' => $item->verbal, 
   'kuantitatif' => $item->kuantitatif,
   'penalaran' => $item->penalaran, 
   'figural' => $item->figural, 
   ],
   'nisn' => $item->nisn,
   'total' => $item->total, 
   ];
   });

   return response()->json($formattedResult);
   }

}
