<?php
/** 
 * api.php
 * <br/> API hijriahmasehi
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     -
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

require_once("hijriahmasehi.php");

header('content-type: application/json');
$metode  = array_key_exists("metode", $_POST)  ? filter_input(INPUT_POST, "metode", FILTER_SANITIZE_STRING)      : "";
$tanggal = array_key_exists("tanggal", $_POST) ? filter_input(INPUT_POST, "tanggal", FILTER_SANITIZE_NUMBER_INT) : "";
$bulan   = array_key_exists("bulan", $_POST)   ? filter_input(INPUT_POST, "bulan", FILTER_SANITIZE_NUMBER_INT)   : "";
$tahun   = array_key_exists("tahun", $_POST)   ? filter_input(INPUT_POST, "tahun", FILTER_SANITIZE_NUMBER_INT)   : "";

switch ($metode)
{
   case "help":
      $ret = ["metode" => ["help" => "menampilkan help", "koreksi" => "mengembalikan daftar koreksi pada <tahun> yang diminta", "hijriah2masehiurfi" => "mengembalikan tanggal masehi dari <tanggal>, <bulan> dan <tahun> hijriah yang diminta dengan perhitungan Urfi", "masehi2hijriahurfi" => "mengembalikan tanggal hijriah dari <tanggal>, <bulan> dan <tahun> masehi yang diminta dengan perhitungan Urfi", "satuhijriahurfi" => "mengembalikan daftar tanggal Masehi untuk setiap tanggal 1 hijriah dalam <tahun> Hijriah dengan perhitungan Urfi"], "tanggal" => "tanggal yang diminta", "bulan" => "bulan yang diminta", "tahun" => "tahun yang diminta"];
      break;
   case "koreksi":
      $ret = daftarKoreksi($tahun);
      break;
   case "hijriah2masehiurfi":
      $ret = Hijriah2MasehiUrfi($tanggal, $bulan, $tahun, FLAG_OUTPUT_ARRAY);
      break;
   case "masehi2hijriahurfi":
      $ret = Masehi2HijriahUrfi($tanggal, $bulan, $tahun, FLAG_OUTPUT_ARRAY);
      break;
   case "satuhijriahurfi":
      $ret = satuHijriah($tahun, FLAG_OUTPUT_ARRAY);
      break;
   default:
      $ret = ["err" => "Gunakan metode POST dengan body name:metode, value:help"];
      break;
}

echo json_encode($ret);