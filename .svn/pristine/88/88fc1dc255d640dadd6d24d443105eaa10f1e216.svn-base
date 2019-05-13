<?php
$dirPath='d:';


recursion_readdir($dirPath);

function forChar($char='-',$times=0){
  $result='';
  for($i=0;$i<$times;$i++){
    $result.=$char;
  }
  return $result;
}
 

function recursion_readdir($dirPath,$Deep=0){
 $resDir=opendir($dirPath);
 while($basename=readdir($resDir)){
   $path=$dirPath.'/'.$basename;
   if(is_dir($path) AND $basename!='.' AND $basename!='..'){
    
    echo forChar('-',$Deep).$basename.'/<br/>';
    $Deep++;
    recursion_readdir($path,$Deep);
   }else if(basename($path)!='.' AND basename($path)!='..'){
     
     echo forChar('-',$Deep).basename($path).'<br/>';
   }
 
 }
 closedir($resDir);
}
