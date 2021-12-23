<?php
if(!ob_start("ob_gzhandler")) ob_start();
/**
 * api.php
 * <br/> API hijriahmasehi
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     2021-12-23
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

require_once("hijriahmasehi.php");

header('content-type: application/json');
$fungsi  = array_key_exists("fungsi", $_GET)  ? filter_input(INPUT_GET, "fungsi", FILTER_SANITIZE_STRING)      : "";
$metode  = array_key_exists("metode", $_GET)  ? filter_input(INPUT_GET, "metode", FILTER_SANITIZE_STRING)      : "";
$tanggal = array_key_exists("tanggal", $_GET) ? filter_input(INPUT_GET, "tanggal", FILTER_SANITIZE_NUMBER_INT) : "";
$bulan   = array_key_exists("bulan", $_GET)   ? filter_input(INPUT_GET, "bulan", FILTER_SANITIZE_NUMBER_INT)   : "";
$tahun   = array_key_exists("tahun", $_GET)   ? filter_input(INPUT_GET, "tahun", FILTER_SANITIZE_NUMBER_INT)   : "";

const mtd = [ METODE_URFI, METODE_MUHAMMADIYAH ];

switch ($fungsi)
{
   case "help":
      $ret = [ "fungsi"  => [ "help"             => "[url:api/help] menampilkan help", 
                              "metode"           => "[url:api/metode] menampilkan metode yang didukung, yaitu ".implode(", ", mtd), 
                              "koreksi"          => "[url:api/koreksi/<metode>/<tahun>] mengembalikan daftar koreksi pada <tahun> yang diminta", 
                              "hijriah2masehi"   => "[url:api/hijriah2masehi/<metode>/<tahun>/<bulan>/<tanggal>] mengembalikan tanggal masehi dari <tanggal>, <bulan> dan <tahun> hijriah yang diminta dengan <metode> yang dikehendaki",
                              "masehi2hijriah"   => "[url:api/masehi2hijriah/<metode>/<tahun>/<bulan>/<tanggal>] mengembalikan tanggal hijriah dari <tanggal>, <bulan> dan <tahun> masehi yang diminta dengan <metode> yang dikehendaki",
                              "satuhijriah"      => "[url:api/satuhijriah/<metode>/<tahun>] mengembalikan daftar tanggal Masehi untuk setiap tanggal 1 hijriah dalam <tahun> Hijriah dengan <metode> yang dikehendaki",
                              "umurbulankoreksi" => "[url:api/umurbulankoreksi/muhammadiyah/<tahun>] mengembalikan daftar umur bulan dalam <tahun> Hijriah dengan metode muhammadiyah"
                            ],
               "metode"  => "metode yang digunakan",
               "tahun"   => "tahun yang diminta",
               "bulan"   => "bulan yang diminta dalam rentang 1-12",
               "tanggal" => "tanggal yang diminta dalam rentang 1-31 untuk masehi dan 1-30 untuk hijriah"
             ];
      break;
   case "koreksi":
      $ret = $koreksi[$tahun];
      break;
   case "metode":
      $ret = [ METODE_URFI, METODE_MUHAMMADIYAH ];
      break;
   case "hijriah2masehi":
      $ret = Hijriah2Masehi($tanggal, $bulan, $tahun, FLAG_OUTPUT_ARRAY, $metode);
      break;
   case "masehi2hijriah":
      $ret = Masehi2Hijriah($tanggal, $bulan, $tahun, FLAG_OUTPUT_ARRAY, $metode);
      break;
   case "satuhijriah":
      $ret = satuHijriah($tahun, FLAG_OUTPUT_ARRAY, $metode);
      break;
   case "umurbulankoreksi":
      $ret = daftarUmurbulanKoreksi($tahun, FLAG_OUTPUT_ARRAY);
      break;
   default:
      $ret = ["err" => "Gunakan metode POST dengan body name:metode, value:help"];
      break;
}

echo json_encode($ret);
