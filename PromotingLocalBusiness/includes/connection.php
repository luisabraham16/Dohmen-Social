<?php
    $type = "mysql";
    $server = "localhost";
    $db = "phpbook-1";
    $port = "3306";
    $charset = "utf8mb4";


    $username = "root123";
    $password = "root123";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];


    $dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";


    try {
        $pdo = new PDO($dsn, $username, $password, $options);              
    } catch (PDOException $e) {
        throw new PDOException($e -> getMessage(), $e -> getCode());
    }

    $mysqli = new mysqli($server, $username, $password, $db);

    
    // function get_file_extension($file_name) {
    //     return substr(strrchr($file_name,'.'),1);
    // }
    // function create_filename($filename, $upload_path){
    //     $basename = pathinfo($filename, PATHINFO_FILENAME);
    //     $extension = pathinfo($filename, PATHINFO_EXTENSION);
    //     //update basename so it replaces any non alpha or numeric char with a dash
    //     $basename = preg_replace('/[^A-z0-9]/', '-', $basename);
    //     $i=0; //counter to use to avoid duplicate file names
    //     while(file_exists($upload_path . $filename)){
    //         $i=$i+1;
    //         $filename = $basename . $i . '.' . $extension;
    //     }
    //     return $filename;
    // }
?>
