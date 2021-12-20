<?php
/** 
 * koreksihijriah.php
 * <br/> Koreksi perhitungan Urfi agar menjadi perhitungan Hakiki
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-19
 * <br/> rev     2021-12-20
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

$koreksi = [];
     /* bulan ke-      1     2     3     4     5     6     7     8     9     10    11    12  */
$koreksi[1443] = [1 => null, null, null, null, null, 0,    0,    0,    0,    0,    1,    0   ];
$koreksi[1444] = [1 => 1,    0,    1,    1,    1,    1,    null, null, null, null, null, null];

$umurbulan = [];
       /* bulan ke-      1     2     3     4     5     6     7     8     9     10    11    12  */
$umurbulan[1443] = [1 => null, null, null, null, null, 29,     30,   29,   30,   30,   29,   30];
$umurbulan[1444] = [1 => 29,   30,   30,   29,   30,   null, null, null, null, null, null, null];

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