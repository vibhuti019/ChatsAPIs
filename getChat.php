<?php
    include_once("./verifyAuthAndSession.php");
    include_once("./dbConnect.php");

    $auth = $_SERVER['HTTP_AUTH'];

    $uri = $_SERVER['REQUEST_URI'];

    verifySizeOfString(32,$auth);

    verifyAuth($auth,$auth,$auth);

    $dataInUrl = ['UserID','Session'];

    $details = fetchDetailFromUrlInJson($uri,2,$dataInUrl,"getChat.php");
  
    $detailsToFetchFromDatabase = ['UserID','LastDeviceLogin','SessionToken'];

    $fetchedDetails = fetchDetailsFromDatabase($conn,"UsersList","SessionToken",$details['Session'],$detailsToFetchFromDatabase);

    echo $fetchedDetails['UserID']."\n".$fetchedDetails['SessionToken'];
    
    verifySessionToken($fetchedDetails->SessionToken,$details->Session);

    echo "\nPrint";


    echo json_encode($fetchedDetails);


?>  