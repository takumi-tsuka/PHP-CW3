    <?php
        session_start();                                        //start session
        if(isset($_GET['m'])){                                  //if there is $_GET['m]
            header('Content-Type: text/plane');                 //set type
            header('Content-Disposition:attachment;filename = "enc.txt'); //display bar and set filename after download
            header('Content-Length: '.filesize("enc.txt"));     //set filesize
            echo file_get_contents('enc.txt');                  //output file
            exit;                                               //end 
        }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Encrypt</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="mb-3">
          <label for="" class="form-label">message</label>
          <textarea class="form-control" name="mes" placeholder="Put your message"></textarea>
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
            if(isset($_GET['msg'])){                                //if there is $_GET['msg']
                echo "<h1>Download your encrypted message!</h1>";   //echo message
                echo "<a href=".$_SERVER['PHP_SELF']."?m=1>download</a>"; //echo <a>tag and href is this page and querystrings m=1
            } 
        ?>
    </div>
    <?php
        if($_SERVER['REQUEST_METHOD']=="POST"){     //ifmethod in form is post,
            $message = $_POST['mes'];               //set $message as $_POST['mes]
            $keys = $_POST['key'];                  //set $keys as $_POST['key']
            $_SESSION['key'] = $keys;                   //set $SESSION['key'] as $keys
            $_SESSION['mes'] = $message;            //set $SESSION['mes'] as $message
            $keys = str_replace(" ", "", $keys);    //remove space from $keys
            $message = str_replace(" ", "", $message); //remve from $message

            $message = strtoupper($message);        //change $message from lowercase to uppercase
            $keys = strtoupper($keys);               //change $keys from lowercase to uppercase
            
            $mesArray = str_split($message);                             //change $message from strings to array
            $keyArray = str_split(str_repeat($keys, count($mesArray)));  //repeat $keys in $mesArray's number and change it from strings to array
            
            $alphaArray = [];                       //set empty array
            for($i=65;$i<91;$i++){                  //use for loop
                array_push($alphaArray,chr($i));    //push chr($i) each time
            }

            $mesNumArray =[];                                   //set empty array
            foreach($mesArray as $mes){                         //use foreach loop in $mesArray
                foreach($alphaArray as $idx=>$alpha){           //useforeach loop in $alphaArray
                    if($mes == $alpha){                         //if $mes equal to $alpha
                        array_push($mesNumArray,$idx);          //push index number to $mesNumArray each time
                    }
                }
            }
    
            $keyNumArray =[];                               //set empty array
            foreach($keyArray as $key){                     //use foreach loop in $keyArray
                foreach($alphaArray as $idx=>$alpha){       //use foreach loop in $alphaAray
                    if($key == $alpha){                     //if $key equal to $alpha
                        array_push($keyNumArray,$idx);      //push index number to $keyNumArray each time
                    }
                }
            }
    
            $encArray = [];                                                                                 //set empty array
            for($i=0;$i<count($mesNumArray);$i++){                                                          //use for loop in the number of amount of $mesNumArray
                if($keyNumArray[$i]+$mesNumArray[$i] < count($alphaArray)){                                 //if $keyNumArray[$i] plus $mesNumArray[$i] is smaller than amount of $alphaArray
                    array_push($encArray,$alphaArray[$keyNumArray[$i]+$mesNumArray[$i]]);                   //push $alphaArray[$keyNumArray[$i]+$mesNumArray[$i]] to $encArray
                }else{                                                                                      //else == if $keyNumArray[$i] plus $mesNumArray[$i] is bigger than amount of $alphaArray
                    array_push($encArray,$alphaArray[$keyNumArray[$i]+$mesNumArray[$i]-count($alphaArray)]); //push $alphaArray[$keyNumArray[$i]+$mesNumArray[$i]-count($alphaArray) to $encArray
                }
            }

            $enc = implode("",$encArray);  //change $encArray from array to strings

            $file = fopen('enc.txt','w');  //open 'enc.txt' in write mode
            fwrite($file,$enc);            //write $enc in 'enc.txt'
            fclose($file);                 //close the file
            
            header("Location:".$_SERVER['PHP_SELF']."?msg=encrypted");  //move to this page with querystrings
        }
    ?>
</body>
</html>