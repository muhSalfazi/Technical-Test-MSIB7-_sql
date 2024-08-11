<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class NilaiRTController extends Controller
{
    //
     public function index()
     {
     // SQL Query untuk mengambil data
     $results = DB::select("
     SELECT
     nilai.nama AS nama,
     nilai.nisn AS nisn,
     SUM(CASE WHEN aspek = 'realistic' THEN nilai ELSE 0 END) AS realistic,
     SUM(CASE WHEN aspek = 'investigative' THEN nilai ELSE 0 END) AS investigative,
     SUM(CASE WHEN aspek = 'artistic' THEN nilai ELSE 0 END) AS artistic,
     SUM(CASE WHEN aspek = 'social' THEN nilai ELSE 0 END) AS social,
     SUM(CASE WHEN aspek = 'enterprising' THEN nilai ELSE 0 END) AS enterprising,
     SUM(CASE WHEN aspek = 'conventional' THEN nilai ELSE 0 END) AS conventional
     FROM nilai
     JOIN siswa ON siswa.id = nilai.siswa_id
     WHERE nilai.materi_uji_id = 7
     AND nilai.pelajaran_khusus_id IS NULL
     GROUP BY siswa.nama, siswa.nisn
     ");

     // Mengubah hasil query menjadi collection untuk proses selanjutnya
     $data = collect($results)->map(function ($item) {
     return [
     'nama' => $item->nama,
     'nisn' => $item->nisn,
     'nilaiRT' => [
     'realistic' => $item->realistic,
     'investigative' => $item->investigative,
     'artistic' => $item->artistic,
     'social' => $item->social,
     'enterprising' => $item->enterprising,
     'conventional' => $item->conventional,
     ],
     ];
     });

     return response()->json($data);
     }
}