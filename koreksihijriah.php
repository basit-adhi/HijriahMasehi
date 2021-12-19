<?php
/** 
 * koreksihijriah.php
 * <br/> Koreksi perhitungan Urfi agar menjadi perhitungan Hakiki
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     -
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

/**
* mengembalikan daftar koreksi manual
* output:
* daftar koreksi
*/
function daftarKoreksi($tahun=-1)
{
    $koreksi = [];
         /* bulan ke-      1     2     3     4     5     6     7     8     9     10    11    12  */
    $koreksi[1443] = [1 => null, null, null, null, null, 0,    0,    0,    0,    1,    1,    0   ];
    $koreksi[1444] = [1 => 1,    0,    1,    1,    1,    1,    null, null, null, null, null, null];
    return $tahun == -1 || !array_key_exists($tahun, $koreksi) ? $koreksi : $koreksi[$tahun];
}
