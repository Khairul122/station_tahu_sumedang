<?php
$host = 'localhost';       
$username = 'root';         
$password = '';             
$database = 'station_tahu_sumedang'; 

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

function closeConnection() {
    global $conn;
    if ($conn) {
        mysqli_close($conn);
    }
}

function escapeString($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

?>