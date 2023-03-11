<html>
<head><title>KARTU PASIEN</title></head>
    <style>
        body {
            font-family: sans-serif;
        }

        .bodi {
            font-size: 13px;
            font-family: sans-serif;
        }

        .header {
            font-size: 15px;
            font-family: sans-serif;
        }

        #detail {
            border-collapse: collapse;
            width: 100%;
        }

        #detail td, #detail th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        #detail th {
            text-align: left;
            background-color: #3584f2;
            color: white;
        }
    </style>
    <table style="width: 100%">
        <tr>
            <td style="text-align: left"><h5>KARTU PASIEN</h5></td>
            <td style="text-align: right"><h5>ID: {{$id}}</h5></td>
        </tr>
    </table>
    <table id="detail" border="1" width="100%" cellpadding="0" cellspacing="0" class="bodi">
        <tr>
            <td style="white-space:nowrap;">Nama :</td>
            <td style=""> {{$nama_pasien}}</td>
        </tr>
        <tr>
            <td style="white-space:nowrap;">Tanggal lahir :</td>
            <td style=""> {{$tanggal_lahir}}</td>
        </tr>
        <tr>
            <td style="white-space:nowrap;">Jenis Kelamin :</td>
            <td style=""> {{$jenis_kelamin}}</td>
        </tr>
        <tr>
            <td style="white-space:nowrap;">Alamat :</td>
            <td style=""> {{$alamat}}</td>
        </tr>
    </table>
</body>
</html>
