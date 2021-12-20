<?php
/** 
 * test.php
 * <br/> tester hijriahmasehi
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     2021-12-20
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */
require_once("hijriahmasehi.php");

//contoh skrip
$metode = [ METODE_URFI, METODE_MUHAMMADIYAH ];
$th = 1443;
foreach ($metode as $m)
{
   echo '<br/><br/>METODE: '.$m.'<br/>';
   echo('9/9/1364 seharusnya 17/8/1945, tes: ' . Hijriah2Masehi(9, 9, 1364, FLAG_OUTPUT_JSON, $m) . '<br/>');
   echo('28/9/1429 seharusnya 29/9/2008, tes: ' . Hijriah2Masehi(28, 9, 1429, FLAG_OUTPUT_JSON, $m) . '<br/>');
   echo('17/8/1945 seharusnya 9/9/1364, tes: ' . Masehi2Hijriah(17, 8, 1945, FLAG_OUTPUT_JSON, $m) . '<br/>');
   echo('29/9/2008 seharusnya 28/9/1429, tes: ' . Masehi2Hijriah(29, 9, 2008, FLAG_OUTPUT_JSON, $m) . '<br/>');
   $H = satuHijriah($th, FLAG_OUTPUT_JSON, $m);
   echo('Daftar tanggal 1 pada tahun ' . $th . ' H<br/>' . $H . '<br/>');
   $rH = reverseSatuHijriah($th, FLAG_OUTPUT_JSON, $m);
   echo('Kebalikan dari tanggal di atas, seharusnya muncul tanggal 1 semua pada tahun ' . $th . ' H<br/>' . $rH . '<br/>');
}
echo("<br/>Daftar koreksi<br/>".json_encode($koreksi));
echo("<br/>Daftar umur bulan $th H<br/>".daftarUmurbulanKoreksi($th));