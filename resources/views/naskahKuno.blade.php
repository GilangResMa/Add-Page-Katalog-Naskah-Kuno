<?php
include '../config/database.php';
$conn = mysqli_connect('localhost', 'root', '', 'projectKP');
if (!$conn) {
    print "Error";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Test</h1>
    <table border="1" cellpadding="5" cellspacing="0">

        <tr>
            <th>No</th>
            <th>Pemilik</th>
            <th>Alamat</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Isi Naskah</th>
            <th>Media</th>
            <th>Tulisan</th>
            <th>Kondisi</th>
            <th>Jumlah</th>
            <th>Dokumentasi</th>
        </tr>
        <?php
        $naskah = "SELECT * FROM datanaskun";
        $query = mysqli_query($conn, $naskah);

        $no = 1;
        while ($data = mysqli_fetch_array($query)) {
            $idN = $data['idN'];  // dr tabel
            $pemilik = $data['pemilik'];
            $alamat  = $data['alamat'];
            $judul = $data['judul'];
            $penulis = $data['penulis'];
            $isi = $data['isi'];
            $media = $data['media'];
            $tulisan = $data['tulisan'];
            $kondisi = $data['kondisi'];
            $jumlah = $data['jumlah'];
            // $dokumentasi = '<img src="/KaNasKun/img/test.png"> size=50px';
            $no++;

            echo "
                  
        <tr>        
        <td>$idN</td>
        <td>$pemilik</td>
        <td>$alamat</td>
        <td>$judul</td>
        <td>$penulis</td>
        <td>$isi</td>
        <td>$media</td>
        <td>$tulisan</td>
        <td>$kondisi</td>
        <td>$jumlah</td>  
        </tr>
  
            ";
        }
        ?>
    </table>
</body>

</html>

<?php
mysqli_close($conn);
?>