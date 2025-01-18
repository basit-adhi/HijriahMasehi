<?php
/** 
 * koreksihijriah.php
 * <br/> Koreksi perhitungan Urfi agar menjadi perhitungan Hakiki yang digunakan oleh Muhammadiyah dengan titik hitung di Yogyakarta
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     2021-12-24
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

/*
* koreksi adalah variabel koreksi untuk mendapatkan tanggal hijriah sesuai dengan kalender Muhammadiyah, ada kemungkinan rumus berbeda untuk tahun tertentu
* penting untuk menggunakan variabel untuk menghemat resource penghitungan
* cara mengisi koreksi
* Alternatif 1 [Manual dengan Kalender Muhammadiyah]:
* 1. Sediakan kalender dengan perhitungan Muhammadiyah, bisa yang berbasis Masehi atau Hijriah
* 2. Jalankan service di bawah ini sesuai dengan tahun Hijriahnya
*    https://service.unisayogya.ac.id/kalender/api/satuhijriah/urfi/<tahun_hijriah>
* 3. Catat selisih tanggal Masehi Muhammadiyah - tanggal Masehi Urfi ke dalam variabel $koreksi[<tahun_hijriah>]
*    misalnya: tanggal Masehi Muhammadiyah: 25 Feb 2020, tanggal Masehi Urfi 24 Feb 2020, maka selisihnya adalah 1
* 4. Cocokkkan kalender dengan menjalankan service di bawah ini sesuai dengan tahun Hijriahnya
*    https://service.unisayogya.ac.id/kalender/api/satuhijriah/muhammadiyah/<tahun_hijriah> 
* Alternatif 2 [Otomatis dengan rumus]
* TODO
* https://github.com/cosinekitty/astronomy/blob/master/source/js/astronomy.browser.js
*/
$koreksi = [];
     /* bulan ke-      1     2     3     4     5     6     7     8     9     10    11    12  */
$koreksi[1434] = [1 => 0,   -1,    1,    1,    1,    1,    1,    1,    1,    1,    2,    1   ];
$koreksi[1435] = [1 => 1,    0,    1,    0,    1,    0,    0,    0,    0,    0,    1,    0   ];
$koreksi[1436] = [1 => 1,    1,    1,    1,    1,    1,    1,    0,    1,    0,    1,    0   ];
$koreksi[1437] = [1 => 0,    0,    1,    0,    1,   -1,    0,   -1,   -1,   -1,   -1,   -1   ];
$koreksi[1438] = [1 => -1,  -1,    0,    0,    1,    1,    1,    1,    1,    1,    1,    0   ];
$koreksi[1439] = [1 => 0,    0,    1,    0,    1,    1,    1,    1,    2,    1,    1,    1   ];
$koreksi[1440] = [1 => 0,   -1,    0,    0,    0,    0,    1,    0,    1,    1,    1,    0   ];
$koreksi[1441] = [1 => 1,    0,    0,    0,    0,    0,    1,   -1,    0,    0,    0,    0   ];
$koreksi[1442] = [1 => 0,    0,    0,    0,    0,    0,    1,    0,    1,    1,    2,    1   ];
$koreksi[1443] = [1 => 1,    0,    1,    0,    0,    0,    0,    0,    0,    0,    1,    0   ];
$koreksi[1444] = [1 => 1,    0,    1,    1,    1,    1,    1,    0,    1,    0,    1,    0   ];
$koreksi[1445] = [1 => 1,    0,    1,    1,    2,    1,    2,    1,    1,    0,    0,    0   ];
$koreksi[1446] = [1 =>-1,   -1,   -1,   -1,    0,   -1,    1,    1,    1,    0,    1,    0   ];
$koreksi[1447] = [1 => 0,    0,    0,    0,    1,    0,    1,    null,    null,    null,    null,    null   ];

/*
* umurbulan adalah berapa hari dalam bulan tersebut (Hijriah)
* cara mengisi umur bulan
* 1. Pastikan sudah mengisi koreksi untuk <tahun_hijriah>
* 2. Jalankan service di bawah ini sesuai dengan tahun Hijriahnya
*    https://service.unisayogya.ac.id/kalender/api/umurbulankoreksi/muhammadiyah/<tahun_hijriah>
* 3. Catat umur bulan ke dalam variabel $umurbulan[<tahun_hijriah>]
*/
$umurbulan = [];
       /* bulan ke-      1     2     3     4     5     6     7     8     9     10    11    12  */
$umurbulan[1434] = [1 => 29,   30,   30,   29,   30,   29,   30,   29,   30,   30,   29,   30];
$umurbulan[1435] = [1 => 29,   30,   29,   30,   29,   29,   30,   29,   30,   30,   29,   30];
$umurbulan[1436] = [1 => 30,   29,   30,   29,   30,   29,   29,   30,   29,   30,   29,   30];
$umurbulan[1437] = [1 => 30,   30,   29,   30,   29,   30,   29,   29,   30,   29,   30,   29];
$umurbulan[1438] = [1 => 30,   30,   30,   29,   30,   29,   30,   29,   30,   29,   29,   29];
$umurbulan[1439] = [1 => 30,   30,   29,   30,   30,   29,   30,   30,   29,   29,   30,   29];
$umurbulan[1440] = [1 => 29,   30,   30,   29,   30,   30,   29,   30,   30,   29,   29,   30];
$umurbulan[1441] = [1 => 29,   29,   30,   29,   30,   30,   29,   30,   30,   29,   30,   29];
$umurbulan[1442] = [1 => 30,   29,   30,   29,   29,   30,   29,   30,   30,   30,   29,   30];
$umurbulan[1443] = [1 => 29,   30,   29,   29,   30,   29,   30,   29,   30,   30,   29,   30];
$umurbulan[1444] = [1 => 29,   30,   30,   29,   30,   29,   29,   30,   29,   30,   29,   30];
$umurbulan[1445] = [1 => 29,   30,   30,   30,   29,   30,   29,   29,   30,   29,   30,   29];
$umurbulan[1446] = [1 => 30,   29,   30,   30,   29,   30,   30,   29,   29,   30,   29,   29];
$umurbulan[1447] = [1 => 30,   29,   30,   30,   29,   30,   null,   null,   null,   null,   null,   null];

/**
* mengembalikan koreksi tanggal pada <bulan> <tahun>
* input:
* tahun adalah tahun hijriah
* bulan adalah bulan hijriah
* output:
* koreksi
*/
function getKoreksi($tahun, $bulan)
{
   global $koreksi;
   if (array_key_exists(''.$tahun, $koreksi))
   {
      return (array_key_exists(''.$bulan, $koreksi[$tahun])) ? $koreksi[$tahun][$bulan] : (int) 0;
   }
   else
   {
      return (int) 0;
   }
}

/**
* mengembalikan umur bulan tanggal pada <bulan> <tahun>
* input:
* tahun adalah tahun hijriah
* bulan adalah bulan hijriah
* output:
* umur bulan
*/
function getUmurbulan($tahun, $bulan)
{
   global $umurbulan;
   if (array_key_exists(''.$tahun, $umurbulan))
   {
      return (array_key_exists(''.$bulan, $umurbulan[$tahun])) ? $umurbulan[$tahun][$bulan] : (int) 0;
   }
   else
   {
      return (int) 0;
   }
}

/**
* fungsi untuk memeriksa apakah tanggal hijriah masuk dalam jangkauan dengan metode yang digunakan oleh Muhammadiyah
* input:
* tahun adalah tahun hijriah
* bulan adalah bulan hijriah
* output:
* status pemeriksaan, true: di luar jangkauan, false: di dalam jangkauan
*/
function cekDiluarJangkauan($tahun, $bulan)
{
   global $koreksi;
   if (array_key_exists(''.$tahun, $koreksi))
   {
      return (array_key_exists(''.$bulan, $koreksi[$tahun])) ? false : true;
   }
   else
   {
      return true;
   }
}
