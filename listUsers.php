<?php 

    include_once('./dbConnect.php');

    define('AES_256_CBC', 'aes-256-cbc');
    

    if (strcmp($req,"PUT")==0){

        $link = $_SERVER['REQUEST_URI'];

        $link = explode("/",$link);

        $index = array_search("listUsers.php", $link);

    }
    else{

    }



    mysqli_close($link);
    die();

?>
