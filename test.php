<?
/** 
 * test.php
 * <br/> tester hijriahmasehi
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     -
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */
require_once("hijriahmasehi.php");

//contoh skrip
echo('10/9/1364 seharusnya 17/8/1945, tes: ' . Hijriah2MasehiUrfi(10, 9, 1364) . '<br/>');
echo('29/9/1429 seharusnya 29/9/2008, tes: ' . Hijriah2MasehiUrfi(29, 9, 1429) . '<br/>');
echo('17/8/1945 seharusnya 10/9/1364, tes: ' . Masehi2HijriahUrfi(17, 8, 1945) . '<br/>');
echo('29/9/2008 seharusnya 29/9/1429, tes: ' . Masehi2HijriahUrfi(29, 9, 2008) . '<br/>');
$th = 1444;
$H = satuHijriah($th);
echo('Daftar tanggal 1 pada tahun ' . $th . 'H<br/>' . $H . '<br/>');
$rH = reverseSatuHijriah($th);
echo('Kebalikan dari tanggal di atas, seharusnya muncul tanggal 1 semua pada tahun ' . $th . 'H<br/>' . $rH . '<br/>');
echo(json_encode($koreksi));