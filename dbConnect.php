<?php

$servername = "localhost";
$username = "vibhuti";
$password = "vibhuti";
$dbName = "listyfied";


$conn = mysqli_connect($servername, $username, $password, $dbName);

//header('Content-Type: application/json');

function checkDbconnection($servername, $username, $password, $dbName) {

    $conn = mysqli_connect($servername, $username, $password, $dbName);

    if(!$conn){
        echo json_encode(
            array(
                'code' => 500 ,
                'message' => 'DataBase Connection Unsuccessful'
            )
        );
    }
    else {
        echo json_encode(
            array(
                'code' => 200 ,
                'message' => 'Database Connection Successful'
            )
        );

    }
    die();
}

if(strcmp($_SERVER['HTTP_API'],'CheckDB')==0) {
    checkDbconnection($servername, $username, $password, $dbName);
    die();
}
elseif(strcmp($_SERVER['HTTP_API'],'FetchDetails')==0) { 
    $req = "GET";
    //Pass The Request
}
elseif(strcmp($_SERVER['HTTP_API'],'PutDetails')==0) { 
    $req = "PUT";
    // Pass the request    ;
}
else {
    echo json_encode(
        array(
            'code' => 404 ,
            'message' => 'Request Could Not Be Processed'
        )
    );
    die();
}



?>