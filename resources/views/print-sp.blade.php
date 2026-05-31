<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $suratPanggilan->jenis_sp }} - {{ $suratPanggilan->santri->nama }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.3; 
            color: #000; 
            max-width: 21cm; 
            margin: 0 auto; 
            padding: 30px 20px;
            background: #fff;
            box-sizing: border-box;
        }
        .header-container { border-bottom: 3px solid #000; margin-bottom: 2px; padding-bottom: 8px; }
        .header { text-align: center; }
        .header-text h2 { margin: 0; font-size: 14pt; font-weight: normal; letter-spacing: 1px; }
        .header-text h1 { margin: 5px 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-text p { margin: 2px 0; font-size: 11pt; }
        .header-border-thin { border-bottom: 1px solid #000; margin-bottom: 20px; }
        .surat-title { text-align: center; font-weight: bold; font-size: 14pt; margin: 20px 0 15px 0; text-decoration: underline; text-transform: uppercase; }
        .content { margin-bottom: 20px; }
        .table-data { margin: 15px 0 15px 30px; }
        .table-data td { padding: 4px 10px 4px 0; vertical-align: top; }
        .ttd-container { display: flex; justify-content: space-between; margin-top: 40px; text-align: center; }
        .ttd-box { width: 40%; }
        .ttd-space { height: 70px; }
        
        /* Hilangkan elemen yang tidak perlu saat print */
        @media print {
            @page { margin: 0; size: A4 portrait; }
            body { padding: 1.5cm 2cm; height: 100%; overflow: hidden; } 
        }
    </style>
</head>
<body onload="setTimeout(function(){ window.print(); }, 500);">

    <div class="header-container">
        <div class="header">
            <div class="header-text">
                <h2>YAYASAN PENDIDIKAN ISLAM ASH-SHIDDIIQI</h2>
                <h1>PONDOK PESANTREN ASH-SHIDDIIQI</h1>
                <p>Jl. Jambi – Ma. Bulian KM 36, Kel. Jembatan Mas, Kec. Pemayung, Kab. Batanghari, Jambi</p>
                <p>Website: ash-shiddiiqi.sch.id | Email: info@ash-shiddiiqi.sch.id</p>
            </div>
        </div>
    </div>
    <div class="header-border-thin"></div>

    <div class="surat-title">SURAT PERINGATAN / PANGGILAN WALI SANTRI ({{ $suratPanggilan->jenis_sp }})</div>

    <div class="content">
        <p><em>Assalamu’alaikum Warahmatullahi Wabarakatuh,</em></p>
        <p>Berdasarkan catatan kedisiplinan dan tata tertib asrama IBS Ash-Shiddiiqi, bersama surat ini kami sampaikan bahwa santri yang identitasnya tercantum di bawah ini:</p>

        <table class="table-data">
            <tr><td>Nama</td><td>:</td><td><strong>{{ $suratPanggilan->santri->nama }}</strong></td></tr>
            <tr><td>NIS</td><td>:</td><td>{{ $suratPanggilan->santri->nis }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>{{ $suratPanggilan->santri->kelas->nama_kelas ?? '-' }}</td></tr>
        </table>

        <p>Telah melakukan beberapa kali pelanggaran sehingga akumulasi sanksi yang bersangkutan telah mencapai <strong>{{ $suratPanggilan->total_poin }} poin</strong>.</p>
        
        <p>Sesuai dengan ketentuan yang berlaku, pencapaian poin tersebut mengakibatkan turunnya <strong>{{ $suratPanggilan->jenis_sp }}</strong>. Oleh karena itu, kami mengharap kehadiran Bapak/Ibu Wali Santri pada waktu yang akan disepakati bersama dengan pihak pengasuhan untuk membahas tindak lanjut dari surat peringatan ini.</p>

        @if($suratPanggilan->catatan_ustadz)
        <p><strong>Catatan:</strong><br>{{ $suratPanggilan->catatan_ustadz }}</p>
        @endif

        <p>Demikian surat peringatan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan jazakumullah khairan katsiran.</p>
        <p><em>Wassalamu’alaikum Warahmatullahi Wabarakatuh.</em></p>
    </div>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Mengetahui,<br>Wali Santri</p>
            <div class="ttd-space"></div>
            <p>( .................................... )</p>
        </div>
        <div class="ttd-box">
            <p>Dikeluarkan pada: {{ $suratPanggilan->tanggal_terbit->format('d F Y') }}<br>Kepala Pengasuhan / Ustadz</p>
            <div class="ttd-space"></div>
            <p>( .................................... )</p>
        </div>
    </div>

</body>
</html>
