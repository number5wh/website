<?php
$FilePath = isset($_GET['FilePath']) ? $_GET['FilePath'] : '';
if(!empty($FilePath))
{
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$FilePath\"");  
	$data = file_get_contents($FilePath);  
	echo $data;
}
?>