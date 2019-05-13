<?php
//这个文件封装了流化工具，在发包时候需要用到

class PHPStream {
	var $len;
	var $data;

	// 构造函数
	function __construct() {
		//echo "PHPStream<br />";
		$this->len = 0;
		$this->data = '';
	}

	//1个字节长度无符号数字
	function WeireUChar($num) {
		//echo "UChar " . $num . "<br />";
		$this->len += 1;
		$this->data .= pack('C', $num);
	}

	//1个字节长度数字
	function WeireChar($num) {
		//echo "Char " . $num . "<br />";
		$this->len += 1;
		$this->data .= pack('c', $num);
	}

	//2个字节长度无符号数字
	function WeireUShort($num) {
		//echo "UShort " . $num . "<br />";
		$this->len += 2;
		$this->data .= pack('S', $num);
	}

	//2个字节长度数字
	function WeireShort($num) {
		//echo "Short " . $num . "<br />";
		$this->len += 2;
		$this->data .= pack('s', $num);
	}

	//4个字节长度无符号数字
	function WeireULong($num) {
		//echo "ULong " . $num . "<br />";
		$this->len += 4;
		$this->data .= pack('L', $num);
	}

	//4个字节长度数字
	function WeireLong($num) {
		//echo "Long " . $num . "<br />";
		$this->len += 4;
		$this->data .= pack('l', $num);
	}

	//固定长度的字符串
	function WeireString($str, $len) {
		//echo "String " . $str . "<br />";
		$this->len += $len;
		$this->data .= pack('a' . $len, $str);
	}
	function WeireINT64($num) {
		//echo "INT64 " . $num . "<br />";
		$num = floatval($num);
		$numL32 = $num & 0xFFFFFFFF;
		$numH32 = floor($num / pow(2, 32));

		$this->len += 4;
		$this->data .= pack('L', $numL32);
		$this->len += 4;
		$this->data .= pack('L', $numH32);
	}

}
