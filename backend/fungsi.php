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

    function register ($data){
        global $conn;

        $nama_user = strtolower(stripslashes($data["nama_user"]));
        $level = strtolower(stripslashes($data["level"]));
        $password = mysqli_real_escape_string($conn, $data["password"]);

        //cek nama_user sudah ada atau belum
         $result = mysqli_query($conn,"SELECT nama_user FROM tb_user 
             WHERE nama_user = '$nama_user' ");
         if (mysqli_fetch_assoc($result)){
             echo "<script>
                    alert('Username yang dipilih sudah terdaftar');
                  </script>";
             return false;
         }
            
        // enskripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // tambahkan userbaru ke database

        mysqli_query($conn, "INSERT INTO tb_user VALUES ('','$nama_user','$password','$level')");
        
        return mysqli_affected_rows($conn);
    }


?>