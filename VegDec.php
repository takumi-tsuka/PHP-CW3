<?php
    session_start();
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
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $encFile = $_FILES['file'];
            $keys = $_POST['key'];

            if($_SESSION['key']!== $keys){
                echo "<h1>!!!ERROR!!! DECRYPTION WAS NOT SUCCESS!!!</h1>";
                exit;
            }

            if(ctype_lower($keys)){
                $keys = strtoupper($keys);
            }
            
            $file = fopen($encFile['tmp_name'],'r');
            $data = fread($file,filesize($encFile['tmp_name']));
            fclose($file);
            
            $encArray = str_split($data);
            $keyArray = str_split(str_repeat($keys, count($encArray)));

            $alphaArray = [];
            for($i=65;$i<91;$i++){
                array_push($alphaArray,chr($i));
            }

            $encNumArray =[];
            foreach($encArray as $enc){
                foreach($alphaArray as $idx=>$alpha){
                    if($enc == $alpha){
                        array_push($encNumArray,$idx);
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
    
            $decArray = [];
            for($i=0;$i<count($encNumArray);$i++){
                if($encNumArray[$i] >= $keyNumArray[$i]){
                    array_push($decArray,$alphaArray[$encNumArray[$i]-$keyNumArray[$i]]);
                }else{
                    array_push($decArray,$alphaArray[count($alphaArray)-($keyNumArray[$i]-$encNumArray[$i])]);
                }
            }
            echo "<h1 style= color:black;>Decrypt was success! : ".implode("",$decArray)."<h1>";
        }
    ?>
</body>
</html>