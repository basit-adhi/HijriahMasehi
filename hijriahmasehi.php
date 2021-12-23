<?php
/** 
 * hijriahmasehi.php
 * <br/> Converter Hijriah ke Masehi dan sebaliknya
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     2021-12-23
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

require_once("koreksihijriah.php");

define("FLAG_OUTPUT_JSON", "json");
define("FLAG_OUTPUT_ARRAY", "array");

define("METODE_URFI", "urfi");
define("METODE_MUHAMMADIYAH", "muhammadiyah");

//sumber utama: http://tarjih.muhammadiyah.or.id/muhfile/tarjih/download/pedoman_hisab_muhammadiyah.pdf
const usiaBulan30Tahun     = 10631; //usia bulan dalam 30 tahun [halaman 19 no 5]
const usiaMatahari4Tahun   = 1461; //usia matahari dalam 4 tahun [halaman 89]
const selisihMasehiHijriah = 227015; //sejak 01-01-01H [halaman 89]
const koreksiPausG13       = 13; //koreksi Paus Gregorius XIII [halaman 89]
const jumlahHari1Bulan     = [ 1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
const namaBulanHijriah     = [ 1 => "Muharram", "Shafar", "Rabi'ul Awwal", "Rabi'ul Akhir", "Jumadil Awwal", "Jumadil Akhir", "Rajab", "Sya'ban", "Ramadhan", "Syawwal", "Dzulqa'dah", "Dzulhijjah" ];
const namaBulanMasehi      = [ 1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];

const koordinatJogja       = [ "lat" =>  -7.8, "long" =>  110.35 ]; //-7 48', 110 21' [halaman 88]

/**
* fungsi untuk konversi dari penanggalan hijriah ke masehi [halaman 88]
* input:
* hijriahtanggal adalah tanggal dalam hijriah
* hijriahbulan adalah bulan dalam hijriah
* hijriahtahun adalah tahun dalam hijriah
* output:
* penanggalan masehi (JSON), misal: {"tanggal":29,"bulan":8,"tahun":2007}
**/
function Hijriah2Masehi($hijritanggal, $hijribulan, $hijritahun, $flag=FLAG_OUTPUT_JSON, $metode=METODE_URFI)
{
   //koreksi
   if ($metode == METODE_MUHAMMADIYAH)
   {
      $harikoreksi = getKoreksi($hijritahun, $hijribulan);
   }
   $lanjut = !($metode == METODE_MUHAMMADIYAH && cekDiluarJangkauan($hijritahun, $hijribulan)) && !is_null($harikoreksi);
   $terbilanghijriah = $hijritanggal." ".namaBulanHijriah[$hijribulan]." ".$hijritahun." H";
   if ($lanjut)
   {
      $tahunSisaDaurBULAN = 0;
      $hariBULAN = ["daur" => 0, "sisadaur_kabisat" => 0, "sisadaur_basitat" => 0, "bulan_ganjil" => 0, "bulan_genap" => 0, "hari" => 0, "koreksidanselisih" => selisihMasehiHijriah + koreksiPausG13];
      $totalHariMasehi = 0;
      $hariSisaDaurMATAHARI = 0;
      $tahunmatahari = 0;
      $hb = [];
      $masehi = ["tanggal" => 0, "bulan" => 0, "tahun" => 1];
      //menghitung hijritahun
      $hijritahun--;
      $hijribulan--;
      $hariBULAN["daur"]                = floor($hijritahun / 30) * usiaBulan30Tahun;
      $tahunSisaDaurBULAN               = $hijritahun % 30;
      $hariBULAN["sisadaur_kabisat"]    = jumlahKabisatBULAN($tahunSisaDaurBULAN) * 355;
      $hariBULAN["sisadaur_basitat"]    = jumlahBasitatBULAN($tahunSisaDaurBULAN) * 354;
      //menghitung hijribulan
      $hariBULAN["bulan_ganjil"]        = ceil($hijribulan / 2) * 30;
      $hariBULAN["bulan_genap"]         = floor($hijribulan / 2) * 29;
      //hijritanggal
      $hariBULAN["hari"]                = $hijritanggal;
      //menghitung masehitahun, masehibulan dan masehihari
      $totalHariMasehi                  = sum($hariBULAN);
      $hariSisaDaurMATAHARI             = $totalHariMasehi % usiaMatahari4Tahun;
      $hb                               = hariKeBulanMATAHARI($hariSisaDaurMATAHARI % 365);
      $masehi["tahun"]                  += $hb["tahun"];
      $masehi["tahun"]                  += floor($totalHariMasehi / usiaMatahari4Tahun) * 4;
      $masehi["tahun"]                  += floor($hariSisaDaurMATAHARI / 365); 
      $masehi["bulan"]                  = $hb["bulan"] + 1;
      $masehi["tanggal"]                = $hb["hari"];
      if ($masehi["tanggal"] <= 0)
      {
         $k                 = 1 - $masehi["tanggal"];
         $masehi["tanggal"] = 1;
         $masehi = intervalTanggal($masehi, -$k);
      }
      if ($metode == METODE_MUHAMMADIYAH && $harikoreksi != 0)
      {
         $masehi = intervalTanggal($masehi, $harikoreksi);
      }
      $masehi["namabulan"]              = namaBulanMasehi[$masehi["bulan"]];
      $masehi["terbilang"]              = $terbilanghijriah." | ".$masehi["tanggal"]." ".$masehi["namabulan"]." ".$masehi["tahun"]." M";
   }
   else
   {
      $masehi = [ "tanggal" => 0, "bulan" => 0, "tahun" => 0, "namabulan" => "", "terbilang" => $terbilanghijriah." | -"];
   }
   return $flag == FLAG_OUTPUT_JSON ? json_encode($masehi) : $masehi;
}

/**
* fungsi untuk konversi dari penanggalan masehi ke hijriah
* input:
* masehitanggal adalah tanggal dalam masehi
* masehibulan adalah bulan dalam masehi
* masehitahun adalah tahun dalam masehi
* output:
* penanggalan hijriah (JSON), misal: {"tanggal":29,"bulan":8,"tahun":1429}
**/
function Masehi2Hijriah($masehitanggal, $masehibulan, $masehitahun, $flag=FLAG_OUTPUT_JSON, $metode=METODE_URFI)
{
   $hariMATAHARI = [ "haritanggal" => 0, "haribulan" => 0, "tahunkabisat" => 0, "sisatahunkabisat" => 0, "koreksidanselisih" =>  -selisihMasehiHijriah -koreksiPausG13 ];
   $totalHariMasehi = 0;
   $th = [];
   $hb = [];
   $hijriah = ["tanggal" => 0, "bulan" => 0, "tahun" => 0];
   $terbilangmasehi = $masehitanggal." ".namaBulanMasehi[$masehibulan]." ".$masehitahun." M";
   //menghitung masehitanggal
   $masehitahun--;
   $masehibulan--;
   $hariMATAHARI["haritanggal"]      = $masehitanggal;
   //menghitung masehibulan
   $hariMATAHARI["haribulan"]        = bulanKeHariMATAHARI($masehibulan);
   //menghitung masehitahun
   $hariMATAHARI["tahunkabisat"]     = floor($masehitahun / 4) * usiaMatahari4Tahun;
   $hariMATAHARI["sisatahunkabisat"] = ($masehitahun % 4) * 365;
   $totalHariMasehi                  = sum($hariMATAHARI);
   //menghitung hijriahtahun
   $hijriah["tahun"]                 += floor($totalHariMasehi / usiaBulan30Tahun) * 30;
   $th                               = hariKeTahunBULAN($totalHariMasehi % usiaBulan30Tahun);
   $hijriah["tahun"]                 += $th["tahun"] + 1;
   $hb                               = hariKeBulanBULAN($th["hari"], $hijriah["tahun"] % 30);
   $hijriah["tahun"]                 += $hb["tahun"];
   //menghitung hijriahbulan dan hijriahhari
   $hijriah["bulan"]                 = $hb["bulan"] + 1;
   $hijriah["tanggal"]               = $hb["hari"];
   //koreksi
   $harikoreksi = getKoreksi($hijriah["tahun"], $hijriah["bulan"]);
   if ($metode == METODE_MUHAMMADIYAH && $harikoreksi != 0)
   {
      $hijriah  = kurangTanggalHijriah($hijriah, $harikoreksi);
   }
   $hijriah["namabulan"]              = namaBulanHijriah[$hijriah["bulan"]];
   $lanjut = !($metode == METODE_MUHAMMADIYAH && cekDiluarJangkauan($hijriah["tahun"], $hijriah["bulan"])) && !is_null($harikoreksi);
   if ($lanjut)
   {
      $hijriah["terbilang"]  = $hijriah["tanggal"]." ".$hijriah["namabulan"]." ".$hijriah["tahun"]." H | ".$terbilangmasehi;
   }
   else
   {
      $hijriah               = [ "tanggal" => 0, "bulan" => 0, "tahun" => 0, "terbilang" => "- | ".$terbilangmasehi];
   }
   return $flag == FLAG_OUTPUT_JSON ? json_encode($hijriah) : $hijriah;
}

/**
* fungsi untuk mengurang tanggal dengan interval <hari>
* input:
* tanggal adalah tanggal yang akan ditambahkan berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* hari adalah interval dalam hari, bisa bernilai positif maupun negatif
* output:
* tanggal baru berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
*/
function kurangTanggalHijriah($tanggal, $hari)
{
   $tanggal["tanggal"] -= $hari;
   if ($tanggal["tanggal"] <= 0)
   {
      $tanggal["bulan"]--;
      if ($tanggal["bulan"] <= 0)
      {
         $tanggal["bulan"] = 12;
         $tanggal["tahun"]--;
      }
      global $umurbulan;
      if (!is_null($umurbulan[$tanggal["tahun"]][$tanggal["bulan"]]))
      {
         $tanggal["tanggal"] = $umurbulan[$tanggal["tahun"]][$tanggal["bulan"]] - $tanggal["tanggal"];
      }
   }
  return $tanggal;
}

/**
* fungsi untuk menambah/mengurang tanggal dengan interval <intervaldalamhari>
* input:
* tanggal adalah tanggal yang akan ditambahkan berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* intervaldalamhari adalah interval dalam hari, bisa bernilai positif maupun negatif
* output:
* tanggal baru berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
*/
function intervalTanggal($tanggal, $intervaldalamhari)
{
   $tanggal = buatTanggal($tanggal);
   date_add($tanggal,date_interval_create_from_date_string($intervaldalamhari." days"));
   $ret     = explode("-", date_format($tanggal,"d-m-Y"));
   return [ "tanggal" => (int) $ret[0], "bulan" => (int) $ret[1], "tahun" => (int) $ret[2] ];
}

/**
* fungsi untuk mengetahui berapa hari selisih antara <tanggal1> dengan <tanggal2>, misalnya tanggal 1 hingga tanggal 2 selisihnya adalah 1 hari
* input:
* tanggal1 adalah tanggal awal berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* tanggal2 adalah tanggal akhir berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* output:
* berapa hari selisih antara tanggal pertama dengan tanggal kedua
*/
function selisihTanggal($tanggal1, $tanggal2)
{
   $origin = buatTanggal($tanggal1);
   $target = buatTanggal($tanggal2);
   $interval = date_diff($origin, $target);
   return (int) ($interval->format('%R').($interval->format('%a')));
}

/**
* fungsi untuk mengetahui berapa hari rentang antara <tanggal1> dengan <tanggal2>, misalnya tanggal 1 hingga tanggal 2 rentangnya adalah 2 hari
* input:
* tanggal1 adalah tanggal awal berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* tanggal2 adalah tanggal akhir berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* output:
* berapa hari rentang antara tanggal pertama dengan tanggal kedua
*/
function rentangTanggal($tanggal1, $tanggal2)
{
   return selisihTanggal($tanggal1, $tanggal2) + 1;
}

/**
* fungsi untuk membuat tanggal dari senarai <tanggal> dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* input:
* tanggal adalah tanggal yang akan dibuat berupa senarai dengan indeks "tahun", "bulan", "tanggal", misal: [ "tahun" => 1970, "bulan" => 1, "tanggal" => 1 ]
* output:
* tanggal dengan format date
*/
function buatTanggal($tanggal)
{
   return date_create($tanggal["tahun"]."-".$tanggal["bulan"]."-".$tanggal["tanggal"]);
}

/**
* fungsi untuk menghitung umur bulan dalam hari untuk rentang <tahun> tersebut
* input:
* tahun adalah berapa tahun yang akan dihitung
* output:
* umur bulan dalam hari (desimal)
**/
function umurBulanDalamHariMATAHARI($tahun)
{
   return roundDesimal(usiaBulan30Tahun/30 * $tahun, 2);
}

/**
* fungsi untuk menentukan bahwa <tahun> tersebut kabisat atau tidak [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* true: kabisat, false: basitat
**/
function isKabisatBULAN($tahun)
{
   $k = bagianDesimal(umurBulanDalamHariMATAHARI($tahun));
   return $k > 0.5 && $k < 0.89;
}

/**
* fungsi untuk menghitung berapa jumlah tahun kabisat hingga <tahun> tersebut [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* berapa jumlah tahun kabisat
**/
function jumlahKabisatBULAN($tahun)
{
   $k = bagianDesimal(umurBulanDalamHariMATAHARI($tahun));
   return floor($tahun*(11/30))+($k > 0.5 ? 1 : 0);
}

/**
* fungsi untuk menghitung berapa jumlah tahun basitat hingga <tahun> tersebut [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* berapa jumlah tahun basitat
**/
function jumlahBasitatBULAN($tahun)
{
   return $tahun - jumlahKabisatBULAN($tahun);
}

/**
* fungsi untuk konversi dari <hari> ke tahun dan sisa berapa hari untuk penanggalan Hijriah
* input:
* hari adalah jumlah hari
* output:
* jumlah tahun dan sisa hari (Object), misal: {"tahun":8,"hari":10}
**/
function hariKeTahunBULAN($hari)
{
   $tahun = [ "tahun" => 0, "hari" => 0 ];
   for ($i=1; $i <= 30; $i++)
   {
      if ($hari <= (isKabisatBULAN($i) ? 355 : 354))
      {
         $tahun["hari"] = $hari;
         return $tahun;
      }
      $hari -= isKabisatBULAN($i) ? 355 : 354;
      $tahun["tahun"]++;
   }
}

/**
* fungsi untuk konversi dari <hari> ke bulan dan sisa berapa hari untuk penanggalan Hijriah
* input:
* hari adalah jumlah hari
* output:
* jumlah bulan dan sisa hari (Object), misal: {"bulan":8,"hari":10}
**/
function hariKeBulanBULAN($hari, $tahun)
{
   $bulan = [ "tahun" => 0, "bulan" => 0, "hari" => 0 ];
   for ($i=1; $i <= 12; $i++)
   {
      //if (hari < (i%2==0 ? 29 : 30))
      if ($hari <= jumlahHariDalamBulanBULAN($i, $tahun))
      {
         $bulan["hari"] = $hari;
         return $bulan;
      }
      //hari -= (i%2==0 ? 29 : 30);
      $hari -= jumlahHariDalamBulanBULAN($i, $tahun);
      $bulan["bulan"]++;
   }
}

/**fungsi untuk menampilkan jumlah hari dalam <bulan> pada <tahun> dengan metode URFI [halaman 19 no 8, 9]
* input:
* bulan adalah bulan yang akan diketahui jumlah harinya
* tahun adalah tahun dari bulan yang akan diketahui jumlah harinya
* output:
* jumlah hari
*/
function jumlahHariDalamBulanBULAN($bulan, $tahun)
{
    if ($bulan == 12 && isKabisatBULAN($tahun))
    {
        return 30;
    }
    else
    {
        return ($bulan%2==0 ? 29 : 30);
    }
}

/**
* fungsi untuk mengambil bagian desimalnya saja dari angka <desimal>
* input:
* desimal adalah angka desimal (dengan koma)
* output:
* bagian desimal, misalnya di dalam angka desimal 4.56, bagian desimalnya dalah 0.56
**/
function bagianDesimal($desimal)
{
   return $desimal - floor($desimal);
}

/**
* fungsi untuk membulatkan sejumlah <digit> pada angka <desimal>
* input:
* desimal adalah angka desimal
* digit adalah berapa digit desimal yang dibulatkan
* output:
* angka desimal yang sudah dibulatkan sekian digit, misalnya 4.123456 dibulatkan 3 digit desimal menjadi 4.123
**/
function roundDesimal($desimal, $digit)
{
   return round($desimal * pow(10, $digit)) / pow(10, $digit);
}

/**
* fungsi untuk konversi dari <hari> ke bulan dan sisa berapa hari untuk penanggalan Masehi
* input:
* hari adalah jumlah hari
* output:
* jumlah bulan dan sisa hari (Object), misal: {"tahun": 0, "bulan":8,"hari":10}
**/
function hariKeBulanMATAHARI($hari)
{
   $bulan = [ "tahun" => 0, "bulan" => 0, "hari" => 0 ];
   for ($i=1; $i <= 12; $i++)
   {
      if ($hari <= jumlahHari1Bulan[$i])
      {
         $bulan["hari"]  = $hari;
         return $bulan;
      }
      $hari -= jumlahHari1Bulan[$i];
      $bulan["bulan"]++;
   }
}

/**
* fungsi untuk konversi dari <bulan> ke hari untuk penanggalan Hijriah
* input:
* bulan adalah jumlah bulan
* output:
* jumlah hari
**/
function bulanKeHariMATAHARI($bulan)
{
   $hari = 0;
   for ($i=1; $i <= $bulan; $i++)
   {
      $hari += jumlahHari1Bulan[$i];
   }
   return $hari;
}

/**
* fungsi untuk mendapatkan tanggal Masehi dari setiap tanggal 1 Hijriah semua bulan pada <tahun> Hijriah
* input:
* tahun adalah tahun dalam Hijriah
* output:
* daftar tanggal Masehi untuk setiap tanggal 1 Hijriah setiap bulan pada tahun tersebut (JSON)
* contoh output: [{"tanggal":28,"bulan":7,"tahun":2022},{"tanggal":27,"bulan":8,"tahun":2022},{"tanggal":25,"bulan":9,"tahun":2022},{"tanggal":25,"bulan":10,"tahun":2022},{"tanggal":23,"bulan":11,"tahun":2022},{"tanggal":23,"bulan":12,"tahun":2022},{"tanggal":22,"bulan":1,"tahun":2023},{"tanggal":21,"bulan":2,"tahun":2023},{"tanggal":21,"bulan":3,"tahun":2023},{"tanggal":20,"bulan":4,"tahun":2023},{"tanggal":19,"bulan":5,"tahun":2023},{"tanggal":18,"bulan":6,"tahun":2023}]
**/
function satuHijriah($tahun, $flag=FLAG_OUTPUT_JSON, $metode=METODE_URFI)
{
   $daftarSatuH = [ ];
   for ($i=1; $i <= 12; $i++)
   {
      $daftarSatuH[$i] = Hijriah2Masehi(1, $i, $tahun, FLAG_OUTPUT_ARRAY, $metode);
   }
   return $flag == FLAG_OUTPUT_JSON ? json_encode($daftarSatuH) : $daftarSatuH;
}

/**
* fungsi untuk mendapatkan tanggal 1 Hijriah semua bulan pada <tahun> Hijriah dari tanggal Masehi untuk 1 Hijriah tersebut (untuk check rumus)
* input:
* tahun adalah tahun dalam Hijriah
* output:
* daftar tanggal 1 setiap bulan pada tahun tersebut (JSON)
* contoh output: [{"tanggal":1,"bulan":1,"tahun":1444},{"tanggal":1,"bulan":2,"tahun":1444},{"tanggal":1,"bulan":3,"tahun":1444},{"tanggal":1,"bulan":4,"tahun":1444},{"tanggal":1,"bulan":5,"tahun":1444},{"tanggal":1,"bulan":6,"tahun":1444},{"tanggal":1,"bulan":7,"tahun":1444},{"tanggal":1,"bulan":8,"tahun":1444},{"tanggal":1,"bulan":9,"tahun":1444},{"tanggal":1,"bulan":10,"tahun":1444},{"tanggal":1,"bulan":11,"tahun":1444},{"tanggal":1,"bulan":12,"tahun":1444}]
*/
function reverseSatuHijriah($tahun, $flag=FLAG_OUTPUT_JSON, $metode=METODE_URFI)
{
   $rH = satuHijriah($tahun, FLAG_OUTPUT_ARRAY, $metode);
   $daftarRSatuH = [ ];
   for ($i=1; $i <= 12; $i++)
   {
      $daftarRSatuH[$i] = Masehi2Hijriah($rH[$i]["tanggal"], $rH[$i]["bulan"], $rH[$i]["tahun"], FLAG_OUTPUT_ARRAY, $metode);
   }
   return $flag == FLAG_OUTPUT_JSON ? json_encode($daftarRSatuH) : $daftarRSatuH;
}

/**
* fungsi untuk mendapatkan umur bulan pada <tahunhijriah>
* input:
* tahunhijriah adalah tahun pada bulan hijriah
* output:
* daftar jumlah bulan pada tahun tersebut (JSON)
* contoh output:
* {"1":29,"2":30,"3":30,"4":29,"5":30,"6":null,"7":null,"8":null,"9":null,"10":null,"11":null,"12":null}
*/
function daftarUmurbulanKoreksi($tahunhijriah, $flag=FLAG_OUTPUT_JSON)
{
   global $koreksi;
   $ret = [];
   //umur didapatkan dari selisih tanggal 1 bulan hijriah target (dalam masehi) dengan tanggal 1 bulan berikutnya (dalam masehi)
   foreach ($koreksi[$tahunhijriah] as $ikoreksi => $koreksibulan)
   {
      if (!is_null($koreksibulan) && !is_null($koreksi[($ikoreksi<12)?$tahunhijriah:$tahunhijriah+1][($ikoreksi<12)?$ikoreksi+1:1]))
      {
         $m1 = Hijriah2Masehi(1, $ikoreksi, $tahunhijriah, FLAG_OUTPUT_ARRAY, METODE_MUHAMMADIYAH);
         $m2 = Hijriah2Masehi(1, ($ikoreksi<12)?$ikoreksi+1:1, ($ikoreksi<12)?$tahunhijriah:$tahunhijriah+1, FLAG_OUTPUT_ARRAY, METODE_MUHAMMADIYAH);
         $ret[$ikoreksi] = selisihTanggal($m1, $m2);
      }
      else
      {
        $ret[$ikoreksi] = null;
      }
   }
   return $flag == FLAG_OUTPUT_JSON ? json_encode($ret) : $ret;
}

/**
* fungsi untuk menjumlahkan semua elemen di dalam objek, sumber: https://stackoverflow.com/questions/16449295/how-to-sum-the-values-of-a-javascript-object
* input:
* obj adalah objek yang berisi data integer
* output:
* jumlah
**/
function sum($obj) 
{
  $sum = 0;
  foreach( $obj as $el ) 
  {
    $sum += $el;
  }
  return $sum;
}

//TODO:
//Koreksi tanggal Hijriah sesuai dengan pedoman hisab Muhammadiyah, yaitu:
//1. Menghitung Ijtimak
//2. Menghitung Saat Terbenamnya Matahari (untuk Kota Yogyakarta)
//3. Menghitung Tinggi Bulan Saat Terbenam Matahari
