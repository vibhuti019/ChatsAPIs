<?php

    //header('Content-Type: application/json');

    include_once("./dbConnect.php");

    function verifyAuth($auth,$userid,$username){

        // $verifyAuth=md5($userid.$username);

        // if (strcmp($auth,$verifyAuth)!=0){
        //     echo json_encode(
        //         array(
        //             'code' => 1 ,
        //             'message' => 'Invalid Auth'
        //         )
        //     );
        //     die();
        // }

        return true;
    }

    function checkSessionValidity($sessionJSONString){

        $session = json_decode($sessionJSONString);

        $date = strtotime($session->ValidTill);
        $Currentdate = date("Y-m-d");

        if($date>$Currentdate){
            echo json_encode(
                array(
                    'code' => 2 ,
                    'message' => 'Invalid Auth'
                )
            );
            die();
        }

        return true;
    }

    function verifySessionToken($sessionJSONString,$session){

        
        verifySizeOfString(32,$session);

        $sessionJSONString = json_decode($sessionJSONString);

        $key = $sessionJSONString->Session;

        if(strcmp($key,$session)){
            echo json_encode(
                array(
                    'code' => 3 ,
                    'message' => 'Invalid Session Token'
                )
            );
            die();
        }

        checkSessionValidity($sessionJSONString);

        return true;
    }


    function checkArraySize($sizeAccepted,$Array){
    
        $sizeOfArray = count($Array);
        
        if ($sizeOfArray != $sizeAccepted){
            
            echo json_encode(
                array(
                    'code' => 4 ,
                    'message' => 'Data Inappropiate'
                )
            );

            die();

        }

        return true;

    }


    function fetchDetailFromUrlInJson($fullUriLink,$noOfDataVariablesPresent,$detailsToBeFetched,$pageName){

            $uriArray = explode("/",$fullUriLink);

            $indexOfThePageName = array_search($pageName,$uriArray);

            $allowedSize = $indexOfThePageName + $noOfDataVariablesPresent + 1;
            checkArraySize($allowedSize,$uriArray);

            $returnArray = [
                'RequestedPage' => $uriArray[$indexOfThePageName]
            ];
            
            $j = 0;
            for($i=$indexOfThePageName+1;$i<$allowedSize;$i++){
                
                $returnArray[$detailsToBeFetched[$j]] = $uriArray[$i];
                $j = $j + 1;
            }


        return $returnArray;
    }

    function verifySizeOfString($allowedSizeOfTheString, $string){

        

        $sizeOfTheString = strlen($string);
        
        if($allowedSizeOfTheString != $sizeOfTheString){
            echo json_encode(
                array(
                    'code' => 5 ,
                    'message' => 'Invalid Tokens'
                )
            );
            die();
        }
        
        return true;    
    }


    function fetchDetailsFromDatabase($AccessConnectionToDatabase,$tableName,$fieldToSearch,$valueToSearch,$detailsToBeFetched){


        $query = "SELECT * FROM ".$tableName." WHERE ".$fieldToSearch." = \"".$valueToSearch."\";";

        
        $result = mysqli_query($AccessConnectionToDatabase, $query);

            if (mysqli_num_rows($result)>0){
                // Attempt to execute the prepared statement
                $j=0;
                $j= "ID:".$j;
                while($row = mysqli_fetch_assoc($result)) {

                     
                    for($i=0;$i<count($detailsToBeFetched);$i++){
                        
                        $foundRow[$detailsToBeFetched[$i]] = $row[$detailsToBeFetched[$i]];
                
                    }

                    $returnArray[$j] = $foundRow;
                    $j=$j++;
                }

            }
            echo json_encode($returnArray);
        return $returnArray;
    }

?>  