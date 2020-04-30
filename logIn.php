<?php 

    include_once('./dbConnect.php');

    define('AES_256_CBC', 'aes-256-cbc');
    
    $date=date("y/m/d h:m:s a");

 
    if (strcmp($req,"PUT")==0){

        $link = $_SERVER['REQUEST_URI'];

        $link = explode("/",$link);

        $index = array_search("logIn.php", $link);

        if (count($link)==$index+3){
            $UserName = $link[$index+1];
            $Password = openssl_encrypt($link[$index+2], AES_256_CBC ,$link[$index+2]);
            
            $sql1 = "SELECT Password FROM UserCredentials WHERE UserName='$UserName'";
            $result = mysqli_query($conn, $sql1);

                       
            
            if (mysqli_num_rows($result)>0){
                // Attempt to execute the prepared statement

                while($row = mysqli_fetch_assoc($result)) {
                    $fetchedPassword = $row["Password"];
                }

                if(strcmp($Password,$fetchedPassword)==0){
                    $sql = "SELECT UserID FROM UserCredentials WHERE UserName='$UserName'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result)>0){
                        // Attempt to execute the prepared statement
        
                        while($row = mysqli_fetch_assoc($result)) {
                            $fetchedUserID = $row["UserID"];
                        }

                        $API = md5($_SERVER["HTTP_USER_AGENT"]." ".$date);
                        $Session = md5($API." ".$date." ".$fetchedUserID);
                        $ValidTill = date('Y-m-d', strtotime($Date. ' + 10 days'));
                        
                        

                        $sql = "UPDATE UsersList SET apiID='$API',LastDeviceLogin='$_SERVER[HTTP_USER_AGENT]',SessionToken='$Session',LastReqSent='$date',SessionValidUpto='$ValidTill' WHERE UserID='$fetchedUserID';";
                        $result = mysqli_query($conn, $sql);



                        if (mysqli_query($conn, $sql)){
                            echo json_encode(
                                array(
                                    'code' => 200 ,
                                    'message' => 'Login Successful',
                                    'UserID' => $fetchedUserID,
                                    'UserName' => $UserName,
                                    'API Key' => $API,
                                    'Session' => $Session,
                                    'Auth' => md5($UserName.$UserID)
                                )
                            );
                        }
                        else{
                            echo json_encode(
                                array(
                                    'code' => 500 ,
                                    'message' => 'Internal Error'
                                )
                            ); 
                        }
                    }
                    else{
                        echo json_encode(
                            array(
                                'code' => 200 ,
                                'message' => 'Could Not Login Invalid Credentials'
                            )
                        ); 
                    }
                

                }
                else{
                    echo json_encode(
                        array(
                            'code' => 200 ,
                            'message' => 'Could Not Login Invalid Credentials'
                        )
                    );
                }
            }
            else{
                echo json_encode(
                    array(
                        'code' => 200 ,
                        'message' => 'Could Not Login Invalid Credentials'
                    )
                );
            }


        }
        else{
            echo json_encode(
                array(
                    'code' => 500 ,
                    'message' => 'Data Content Inappropiate'
                )
            ); 
        }
    }
    else {
        echo json_encode(
            array(
                'code' => 200 ,
                'message' => 'Data Inappropiate'
            )
        );
    }

    mysqli_close($conn);
    die();

?>


