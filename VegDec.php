<?php
    session_start();   //start session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decrypt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <style>
        h1{
            color: red;
            font-weight: 600;
        }
    </style>
</head>
<body>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="" class="form-label">Choose file</label>
          <input type="file" class="form-control" name="file" id="" placeholder="" aria-describedby="fileHelpId">
          <div id="fileHelpId" class="form-text"></div>
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Key</label>
          <input type="text"
            class="form-control" name="key" aria-describedby="helpId" placeholder="put your key">
          <small id="helpId" class="form-text text-muted"></small>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
    <?php 
        if($_SERVER['REQUEST_METHOD']=="POST"){     //if request method is post
            $encFile = $_FILES['file'];             //set $encFile as $_File['file']
            $keys = $_POST['key'];                   //set $keys as $_POST['key']

            if($_SESSION['key']!== $keys){                                  //If $key is not same as $_SESSION['key']
                echo "<h1>!!!ERROR!!! DECRYPTION WAS NOT SUCCESS!!!</h1>";  //echo error message
                exit;                                                       //exit from condition
            }

            $keys = str_replace(" ", "", $keys); //replace space to non
            $keys = strtoupper($keys);           //chage $keys into uppercase
            
            $file = fopen($encFile['tmp_name'],'r');                //open temporary file by read mode
            $data = fread($file,filesize($encFile['tmp_name']));    //read the file
            fclose($file);                                          //close the file
            
            $encArray = str_split($data);                               //change $data from strings to array
            $keyArray = str_split(str_repeat($keys, count($encArray))); //repeat $keys

            $alphaArray = [];                                       //set empty array
            for($i=65;$i<91;$i++){                                  //use for loop
            array_push($alphaArray,chr($i));                        //push each alphabet into $alphaArray
            }

            $encNumArray =[];                               //set empty array
            foreach($encArray as $enc){                     //use foreach loop in $encArray
                foreach($alphaArray as $idx=>$alpha){       //use foreach loop in $alphaArray
                    if($enc == $alpha){                     //If $enc equal to $alpha
                        array_push($encNumArray,$idx);      //push the index number into $encNumArray
                    }
                }
            }
    
            $keyNumArray =[];                           //set empty array
            foreach($keyArray as $key){                 //use foreach loop in $keyArray
                foreach($alphaArray as $idx=>$alpha){   //use foreach loop in $alphaArray
                    if($key == $alpha){                 //If $key equal to $alpha
                        array_push($keyNumArray,$idx);  //push the index number into $keyNumArray
                    }
                }
            }
    
            $decArray = [];                                                                 //set empty array
            for($i=0;$i<count($encNumArray);$i++){                                          //use for loop from 0 to amount of $encNumArray and add one
                if($encNumArray[$i] >= $keyNumArray[$i]){                                   //If the each index number of the encrypted message is bigger than he each index number of the key
                    array_push($decArray,$alphaArray[$encNumArray[$i]-$keyNumArray[$i]]);   //push $alphaArray[$encNumArray[$i]-$keyNumArray[$i]] to $decArray (oppoite process when encripto)
                }else{
                    array_push($decArray,$alphaArray[count($alphaArray)-($keyNumArray[$i]-$encNumArray[$i])]); //push $alphaArray[count($alphaArray)-($keyNumArray[$i]-$encNumArray[$i])] to $decArray (opposite process when encrypto)
                }
            }
            echo "<h1 style= color:black;>Decrypt was success! : ".implode("",$decArray)."<h1>"; //echo the decrypted message!!!!
        }
    ?>
</body>
</html>