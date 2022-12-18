    <?php
        session_start();
        if(isset($_GET['m'])){
            header('Content-Type: text/plane');
            header('Content-Disposition:attachment;filename = "enc.txt');
            header('Content-Length: '.filesize("enc.txt"));
            echo file_get_contents('enc.txt');
            exit;
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
            if(isset($_GET['msg'])){
                echo "<h1>Download your encrypted message!</h1>";
                echo "<a href=".$_SERVER['PHP_SELF']."?m=1>download</a>";
            } 
        ?>
    </div>
    <?php
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $message = $_POST['mes'];
            $keys = $_POST['key'];
            $_SESSION['key'] = $keys;
            $_SESSION['mes'] = $message;

            
            
            if(ctype_lower($message) || ctype_lower($keys)){
                $message =strtoupper($message);
                $keys = strtoupper($keys);
            }
            $mesArray = str_split($message);
            $keyArray = str_split(str_repeat($keys, count($mesArray)));
            print_r($keyArray);
            
            $alphaArray = [];
            for($i=65;$i<91;$i++){
                array_push($alphaArray,chr($i));
            }

            $mesNumArray =[];
            foreach($mesArray as $mes){
                foreach($alphaArray as $idx=>$alpha){
                    if($mes == $alpha){
                        array_push($mesNumArray,$idx);
                    }
                }
            }
    
            $keyNumArray =[];
            foreach($keyArray as $key){
                foreach($alphaArray as $idx=>$alpha){
                    if($key == $alpha){
                        array_push($keyNumArray,$idx);
                    }
                }
            }
    
            $encArray = [];
            for($i=0;$i<count($mesNumArray);$i++){
                if($keyNumArray[$i]+$mesNumArray[$i] < count($alphaArray)){
                    array_push($encArray,$alphaArray[$keyNumArray[$i]+$mesNumArray[$i]]);
                }else{
                    array_push($encArray,$alphaArray[$keyNumArray[$i]+$mesNumArray[$i]-count($alphaArray)]);
                }
            }

            $enc = implode("",$encArray);

                $file = fopen('enc.txt','w');
                fwrite($file,$enc);
                fclose($file);
            
            header("Location:".$_SERVER['PHP_SELF']."?msg=encrypted");
        }
    ?>
</body>
</html>