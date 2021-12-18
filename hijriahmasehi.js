/** 
 * hijriahmasehi.js
 * <br/> Converter Hijriah ke Masehi dan sebaliknya
 * <br/> profil  https://id.linkedin.com/in/basitadhi
 * <br/> buat    2021-12-18
 * <br/> rev     -
 * <br/> sifat   open source
 * @author Basit Adhi Prabowo, S.T. <basit@unisayogya.ac.id>
 * @access public
 */

//sumber utama: http://tarjih.muhammadiyah.or.id/muhfile/tarjih/download/pedoman_hisab_muhammadiyah.pdf
const usiaBulan30Tahun     = 10631; //usia bulan dalam 30 tahun [halaman 19 no 5]
const usiaMatahari4Tahun   = 1461; //usia matahari dalam 4 tahun [halaman 89]
const selisihMasehiHijriah = 227015; //sejak 01-01-01H [halaman 89]
const koreksiPausG13       = 13; //koreksi Paus Gregorius XIII [halaman 89]
const jumlahHari1Bulan     = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

const koordinatJogja       = { lat: -7.8, long: 110.35 }; //-7 48', 110 21' [halaman 88]

/**
* fungsi untuk konversi dari penanggalan hijriah ke masehi [halaman 88]
* input:
* hijriahtanggal adalah tanggal dalam hijriah
* hijriahbulan adalah bulan dalam hijriah
* hijriahtahun adalah tahun dalam hijriah
* output:
* penanggalan masehi (JSON), misal: {"tanggal":29,"bulan":8,"tahun":2007}
**/
function Hijriah2MasehiUrfi(hijritanggal, hijribulan, hijritahun)
{
   var tahunSisaDaurBULAN;
   var hariBULAN = {daur:0, sisadaur_kabisat:0, sisadaur_basitat:0, bulan_ganjil:0, bulan_genap:0, hari:0, koreksidanselisih: selisihMasehiHijriah + koreksiPausG13};
   var totalHariMasehi, hariSisaDaurMATAHARI;
   var tahunmatahari, hb;
   var masehi = {tanggal:0, bulan:0, tahun:1};
   //menghitung hijritahun
   hijritahun--;   
   hariBULAN.daur                = Math.floor(hijritahun / 30) * usiaBulan30Tahun;
   tahunSisaDaurBULAN            = hijritahun % 30;
   hariBULAN.sisadaur_kabisat    = jumlahKabisatBULAN(tahunSisaDaurBULAN) * 355;
   hariBULAN.sisadaur_basitat    = jumlahBasitatBULAN(tahunSisaDaurBULAN) * 354;
   //menghitung hijribulan
   hariBULAN.bulan_ganjil        = Math.ceil(hijribulan / 2) * 30;
   hariBULAN.bulan_genap         = Math.floor(hijribulan / 2) * 29;
   //hijritanggal
   hariBULAN.hari                = hijritanggal;
   //menghitung masehitahun, masehibulan dan masehihari
   totalHariMasehi               = sum(hariBULAN);
   hariSisaDaurMATAHARI          = totalHariMasehi % usiaMatahari4Tahun;
   hb                            = hariKeBulanMATAHARI(hariSisaDaurMATAHARI % 365);
   masehi.tahun                  += hb.tahun;
   masehi.tahun                  += Math.floor(totalHariMasehi / usiaMatahari4Tahun) * 4;
   masehi.tahun                  += Math.floor(hariSisaDaurMATAHARI / 365); 
   masehi.bulan                  += hb.bulan + 1;
   if (masehi.bulan > 12)
   {
      masehi.bulan -= 12;
      masehi.tahun += 1;
   }
   masehi.tanggal                = hb.hari;
   return JSON.stringify(masehi);
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
function Masehi2HijriahUrfi(masehitanggal, masehibulan, masehitahun)
{
   var hariMATAHARI = { haritanggal:0, haribulan:0, tahunkabisat:0, sisatahunkabisat:0, koreksidanselisih: -selisihMasehiHijriah -koreksiPausG13 };
   var totalHariMasehi, th, hb;
   var hijriah = {tanggal:0, bulan:0, tahun:0};
   masehitahun--;
   masehibulan--;
   //menghitung masehitanggal
   hariMATAHARI.haritanggal      = masehitanggal;
   //menghitung masehibulan
   hariMATAHARI.haribulan        = bulanKeHariMATAHARI(masehibulan);
   //menghitung masehitahun
   hariMATAHARI.tahunkabisat     = Math.floor(masehitahun / 4) * usiaMatahari4Tahun;
   hariMATAHARI.sisatahunkabisat = (masehitahun % 4) * 365;
   totalHariMasehi               = sum(hariMATAHARI);
   //menghitung hijriahtahun
   hijriah.tahun                 += Math.floor(totalHariMasehi / usiaBulan30Tahun) * 30;
   th                            = hariKeTahunBULAN(totalHariMasehi % usiaBulan30Tahun);
   hijriah.tahun                 += th.tahun + 1;
   hb                            = hariKeBulanBULAN(th.hari, hijriah.tahun % 30);
   hijriah.tahun                 += hb.tahun;
   //menghitung hijriahbulan dan hijriahhari
   hijriah.bulan                 = hb.bulan;
   hijriah.tanggal               = hb.hari;
   return JSON.stringify(hijriah);
}

/**
* fungsi untuk menghitung umur bulan dalam hari untuk rentang <tahun> tersebut
* input:
* tahun adalah berapa tahun yang akan dihitung
* output:
* umur bulan dalam hari (desimal)
**/
function umurBulanDalamHariMATAHARI(tahun)
{
   return roundDesimal(usiaBulan30Tahun/30 * tahun, 2);
}

/**
* fungsi untuk menentukan bahwa <tahun> tersebut kabisat atau tidak [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* true: kabisat, false: basitat
**/
function isKabisatBULAN(tahun)
{
   var k = bagianDesimal(umurBulanDalamHariMATAHARI(tahun));
   return k > 0.5 && k < 0.89;
}

/**
* fungsi untuk menghitung berapa jumlah tahun kabisat hingga <tahun> tersebut [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* berapa jumlah tahun kabisat
**/
function jumlahKabisatBULAN(tahun)
{
   var k = bagianDesimal(umurBulanDalamHariMATAHARI(tahun));
   return Math.floor(tahun*(11/30))+(k > 0.5 ? 1 : 0);
}

/**
* fungsi untuk menghitung berapa jumlah tahun basitat hingga <tahun> tersebut [halaman 19 no 6]
* input:
* tahun adalah tahun dalam rentang 1 sampai dengan 30 tahun
* output:
* berapa jumlah tahun basitat
**/
function jumlahBasitatBULAN(tahun)
{
   return tahun - jumlahKabisatBULAN(tahun);
}

/**
* fungsi untuk konversi dari <hari> ke tahun dan sisa berapa hari untuk penanggalan Hijriah
* input:
* hari adalah jumlah hari
* output:
* jumlah tahun dan sisa hari (Object), misal: {"tahun":8,"hari":10}
**/
function hariKeTahunBULAN(hari)
{
   var tahun = { tahun:0, hari:0 };
   for (var i=1; i <= 30; i++)
   {
      if (hari < (isKabisatBULAN(i) ? 355 : 354))
      {
         tahun.hari = hari;
         return tahun;
      }
      hari -= isKabisatBULAN(i) ? 355 : 354;
      tahun.tahun++;
   }
}

/**
* fungsi untuk konversi dari <hari> ke bulan dan sisa berapa hari untuk penanggalan Hijriah
* input:
* hari adalah jumlah hari
* output:
* jumlah bulan dan sisa hari (Object), misal: {"bulan":8,"hari":10}
**/
function hariKeBulanBULAN(hari, tahun)
{
   var bulan = { tahun:0, bulan:0, hari:0 };
   for (var i=1; i <= 30; i++)
   {
      //if (hari < (i%2==0 ? 29 : 30))
      if (hari <= jumlahHariDalamBulanBULAN(i, tahun))
      {
         bulan.hari = hari;
         if (bulan.bulan == 0) 
         {
            bulan.bulan = 12;
            bulan.tahun = -1;
         }
         if (bulan.hari == 0)
         {
             bulan.hari = 1;
         }
         return bulan;
      }
      //hari -= (i%2==0 ? 29 : 30);
      hari -= jumlahHariDalamBulanBULAN(i, tahun);
      bulan.bulan++;
   }
}

/**[halaman 19 no 8, 9]*/
function jumlahHariDalamBulanBULAN(bulan, tahun)
{
    if (bulan == 12 && isKabisatBULAN(tahun))
    {
        return 30;
    }
    else
    {
        return (bulan%2==0 ? 29 : 30);
    }
}

/**
* fungsi untuk mengambil bagian desimalnya saja dari angka <desimal>
* input:
* desimal adalah angka desimal (dengan koma)
* output:
* bagian desimal, misalnya di dalam angka desimal 4.56, bagian desimalnya dalah 0.56
**/
function bagianDesimal(desimal)
{
   return desimal - Math.floor(desimal);
}

/**
* fungsi untuk membulatkan sejumlah <digit> pada angka <desimal>
* input:
* desimal adalah angka desimal
* digit adalah berapa digit desimal yang dibulatkan
* output:
* angka desimal yang sudah dibulatkan sekian digit, misalnya 4.123456 dibulatkan 3 digit desimal menjadi 4.123
**/
function roundDesimal(desimal, digit)
{
   return Math.round(desimal * Math.pow(10, digit)) / Math.pow(10, digit);
}

/**
* fungsi untuk konversi dari <hari> ke bulan dan sisa berapa hari untuk penanggalan Masehi
* input:
* hari adalah jumlah hari
* output:
* jumlah bulan dan sisa hari (Object), misal: {"tahun": 0, "bulan":8,"hari":10}
**/
function hariKeBulanMATAHARI(hari)
{
   var bulan = { tahun:0, bulan:0, hari:0 };
   for (var i=1; i <= 12; i++)
   {
      if (hari < jumlahHari1Bulan[i])
      {
         bulan.hari  = hari;
         if (bulan.bulan == 0) 
         {
            bulan.bulan = 12;
            bulan.tahun = -1;
         }
         if (bulan.hari == 0)
         {
             bulan.hari = 1;
         }
         return bulan;
      }
      hari -= jumlahHari1Bulan[i];
      bulan.bulan++;
   }
}

/**
* fungsi untuk konversi dari <bulan> ke hari untuk penanggalan Hijriah
* input:
* bulan adalah jumlah bulan
* output:
* jumlah hari
**/
function bulanKeHariMATAHARI(bulan)
{
   var hari = 0;
   for (var i=1; i <= bulan; i++)
   {
      hari += jumlahHari1Bulan[i];
   }
   return hari;
}

/**
* fungsi untuk mendapatkan tanggal 1 setiap bulan pada <tahun> Hijriah
* input:
* tahun adalah tahun dalam Hijriah
* output:
* list tanggal 1 setiap bulan pada tahun tersebut (JSON)
**/
function satuHijriah(tahun)
{
   var listSatuH = [ 0 ];
   for (var i=1; i<=12; i++)
   {
      listSatuH[i] = JSON.parse(Hijriah2MasehiUrfi(1, i, tahun));
   }
   return JSON.stringify(listSatuH);
}

/**
* fungsi untuk menjumlahkan semua elemen di dalam objek, sumber: https://stackoverflow.com/questions/16449295/how-to-sum-the-values-of-a-javascript-object
* input:
* obj adalah objek yang berisi data integer
* output:
* jumlah
**/
function sum( obj ) 
{
  var sum = 0;
  for( var el in obj ) 
  {
    if( obj.hasOwnProperty( el ) ) 
    {
      sum += parseFloat( obj[el] );
    }
  }
  return sum;
}

//TODO:
//Koreksi tanggal Hijriah sesuai dengan pedoman hisab Muhammadiyah, yaitu:
//1. Menghitung Ijtimak
//2. Menghitung Saat Terbenamnya Matahari (untuk Kota Yogyakarta)
//3. Menghitung Tinggi Bulan Saat Terbenam Matahari

//contoh skrip
document.write('10/8/1364 seharusnya 17/8/1945, tes: ' + Hijriah2MasehiUrfi(10, 8, 1364) + '<br/>');
document.write('29/8/1429 seharusnya 29/9/2008, tes: ' + Hijriah2MasehiUrfi(29, 8, 1429) + '<br/>');
document.write('17/8/1945 seharusnya 10/8/1364, tes: ' + Masehi2HijriahUrfi(17, 8, 1945) + '<br/>');
document.write('29/9/2008 seharusnya 29/8/1429, tes: ' + Masehi2HijriahUrfi(29, 9, 2008) + '<br/>');
var th = 1442, H = satuHijriah(th);
document.write('Daftar tanggal 1 pada tahun ' + th + 'H<br/>' + H + '<br/>');
var rH = JSON.parse(H), listRSatuH = [ 0 ];;
for (var i=1; i<=12; i++)
{
   listRSatuH[i] = JSON.parse(Masehi2HijriahUrfi(rH[i].tanggal, rH[i].bulan, rH[i].tahun));
}
document.write('Kebalikan dari tanggal di atas, seharusnya muncul tanggal 1 semua pada tahun ' + th + 'H<br/>' +JSON.stringify(listRSatuH) + '<br/>');
