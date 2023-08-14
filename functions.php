<?php

    //koneksi databse 
    $conn = mysqli_connect( "localhost", "root", "", "db_penjualan" );

    function query($query) {
        global $conn;
        $result = mysqli_query($conn,$query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result) ) {
            $rows[] = $row;
        }
        return $rows;
    }

    function registrasi ($data){
        global $conn;

        $nama_pelanggan = strtolower(stripslashes($data["nama_pelanggan"]));
        $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);

        //cek nama_pelanggan sudah ada atau belum
         $result = mysqli_query($conn,"SELECT nama_pelanggan FROM tb_pelanggan 
             WHERE nama_pelanggan = '$nama_pelanggan' ");
         if (mysqli_fetch_assoc($result)){
             echo "<script>
                    alert('nama_pelanggan yang dipilih sudah terdaftar');
                  </script>";
             return false;
         }
            

        // tambahkan userbaru ke database

        mysqli_query($conn, "INSERT INTO tb_pelanggan VALUES ('','$nama_pelanggan','$no_hp')");
        
        return mysqli_affected_rows($conn);
    }

    function createbarang ($data){
        global $conn;
        $nama_barang = htmlspecialchars($data["nama_barang"]);
        $stok = htmlspecialchars($data["stok"]);
        $harga = htmlspecialchars($data["harga"]);
        
        $image = uploadbarang();
        if (!$image){
            return false;
        }

        $query = "INSERT INTO tb_barang 
                    VALUES (
                '', 
                '$nama_barang', 
                '$stok', 
                '$harga', 
                '$image')";

        mysqli_query ($conn,$query);

        return mysqli_affected_rows($conn);

    }

    function uploadbarang(){

        $namaFile = $_FILES['image']['name'];
        $ukuranFile = $_FILES['image']['size'];
        $error = $_FILES['image']['error'];
        $tmpName = $_FILES['image']['tmp_name'];

        if( $error === 4 ){
            echo "<div class='alert alert-danger' role='alert'>
                        Pilih image terlebih dahulu !
                    </div>
                    
                    ";
            return false;
        }

        //cek apakah yang diuploadlogo image?
        $ekstensiImageValid = ['jpg', 'png', 'jpeg'];
        $ekstensiImage = explode ('.', $namaFile);
        $ekstensiImage = strtolower (end($ekstensiImage));
        if (!in_array($ekstensiImage, $ekstensiImageValid)){
        echo "<div class='alert alert-danger' role='alert'>
                 Yang Anda Upload Bukan image !
            </div>
      
            ";
            return false ;
        }
        if ($ukuranFile > 3000000 ){
            echo "<div class='alert alert-danger' role='alert'>
                        Ukuran Gambar Terlalu Besar ! <strong> Max 1mb </strong>
                    </div>
                    ";
            return false ;
        }
        $namaFileBaru = uniqid ();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiImage;

        move_uploaded_file($tmpName, '../assets/img/barang/' . $namaFileBaru);
        return $namaFileBaru;
    }

    function createnota ($data){
        global $conn;
        $id_pelanggan = htmlspecialchars($data["id_pelanggan"]);
        $tanggal = htmlspecialchars($data["tanggal"]);
        $id_user = htmlspecialchars($data["id_user"]);

        $query = "INSERT INTO tb_nota 
                    VALUES (
                '', 
                '$id_pelanggan', 
                '$tanggal', 
                '$id_user')";

        mysqli_query ($conn,$query);

        return mysqli_affected_rows($conn);

    }

    function createtransaksi ($data){
        global $conn;
        $id_barang = htmlspecialchars($data["id_barang"]);
        $jumlah_beli = htmlspecialchars($data["jumlah_beli"]);
        $id_nota = htmlspecialchars($data["id_nota"]);

        $query = "INSERT INTO tb_transaksi 
                    VALUES (
                '', 
                '$id_barang', 
                '$jumlah_beli', 
                '$id_nota')";

        mysqli_query ($conn,$query);

        return mysqli_affected_rows($conn);

    }

?>