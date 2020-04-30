 <?php 

        include_once('./dbConnect.php');

        define('AES_256_CBC', 'aes-256-cbc');
        

        if (strcmp($req,"PUT")==0){

            $link = $_SERVER['REQUEST_URI'];

            $link = explode("/",$link);

            $index = array_search("createAccount.php", $link);

            
            if (count($link)==$index+5){
                $UserID = md5(date("Ymdhis"));
                $FirstName = $link[$index+1];
                $LastName = $link[$index+2];
                $UserName = $link[$index+3];
                $Password = openssl_encrypt($link[$index+4], AES_256_CBC ,$link[$index+4]);
                
                $sql1 = "SELECT * FROM UserCredentials WHERE Username='$UserName'";
                $result = mysqli_query($conn, $sql1);
                

                
                if (mysqli_num_rows($result)==0){
                    // Attempt to execute the prepared statement

                    $sql = "INSERT IGNORE INTO UserCredentials (UserID, FirstName, LastName, UserName, Password) VALUES ( '$UserID','$FirstName','$LastName','$UserName','$Password')";

                    if(mysqli_query($conn, $sql)){
                        $date = date("Y-m-d,h:i:s:a");
                        $sql = "INSERT IGNORE INTO UsersList (UserID,LastDeviceLogin,LastReqSent) VALUES ('$UserID','$date','$date')";


                        if(mysqli_query($conn, $sql)){
                    
                            echo json_encode(
                                array(
                                    'code' => 200 ,
                                    'message' => 'Values Entered',
                                    'UserID' => $UserID,
                                    'Username' => $UserName
                                )
                            );
                        }
                        else{
                            echo json_encode(
                                array(
                                    'code' => 500 ,
                                    'message' => 'Values Not Entered'
                                )
                            );
                        }

                    } 
                    else{
                        echo json_encode(
                            array(
                                'code' => 500 ,
                                'message' => 'Problem Putting The Values'
                            )
                        );
                    }
                }
                else{
                    echo json_encode(
                        array(
                            'code' => 500 ,
                            'message' => 'Duplicate Values'
                        )
                    );
                }



            }
            else {
                echo json_encode(
                    array(
                        'code' => 500 ,
                        'message' => 'Data Inappropiate'
                    )
                );
            }
        }
        else {
            echo json_encode(
                array(
                    'code' => 200 ,
                    'message' => 'Invalid Auth'
                )
            );
        }

        
    mysqli_close($conn);
    die();

?>
