<?php
/**
 * 更新活动信息
 * @author xuluojiong
 */


$filename = 'Logs/msg.txt';
$fp = fopen($filename,"r");
$data = array();
$k = 0;
if($fp){
    while(!feof($fp)){
        $buffer = fgets($fp,4096);
        $buffer = json_decode($buffer);
        $buffer = (array)$buffer;
        if($buffer){
            $data[$k] = $buffer;
            $k = ($k+1)%20;
        }
    }
}

fclose($fp);
$fp = fopen($filename,"w");
$data[$k]['msg'] = $_POST['msg'];
$data[$k]['time'] = date('Y-m-d',time());
preg_match_all('/\d+/',$data[$k]['msg'],$ret);
$ret = $ret[0];
$n = count($ret);
if($ret[$n-1]>50000000){
    $f = fopen('Logs/prize.txt',"a+");
    fwrite($f,json_encode($data[$k])."\r\n");
    fclose($f);
}
if($fp){
    if($k==0){
        for ($k=0;$k<20;$k++){
            if(isset($data[($k+1)%20])){
                fwrite($fp,json_encode($data[($k+1)%20])."\r\n");
            }   
        }
    }else{
        for ($k=0;$k<20;$k++){
            if(isset($data[$k])){
                fwrite($fp,json_encode($data[$k])."\r\n");
            }
        }        
    }
}
fclose($fp);
?>