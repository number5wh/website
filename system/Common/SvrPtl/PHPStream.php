<?php
//����ļ���װ���������ߣ��ڷ���ʱ����Ҫ�õ�

class PHPStream {
	var $len;
	var $data;

	// ���캯��
	function __construct() {
		//echo "PHPStream<br />";
		$this->len = 0;
		$this->data = '';
	}

	//1���ֽڳ����޷������
	function WeireUChar($num) {
		//echo "UChar " . $num . "<br />";
		$this->len += 1;
		$this->data .= pack('C', $num);
	}

	//1���ֽڳ�������
	function WeireChar($num) {
		//echo "Char " . $num . "<br />";
		$this->len += 1;
		$this->data .= pack('c', $num);
	}

	//2���ֽڳ����޷������
	function WeireUShort($num) {
		//echo "UShort " . $num . "<br />";
		$this->len += 2;
		$this->data .= pack('S', $num);
	}

	//2���ֽڳ�������
	function WeireShort($num) {
		//echo "Short " . $num . "<br />";
		$this->len += 2;
		$this->data .= pack('s', $num);
	}

	//4���ֽڳ����޷������
	function WeireULong($num) {
		//echo "ULong " . $num . "<br />";
		$this->len += 4;
		$this->data .= pack('L', $num);
	}

	//4���ֽڳ�������
	function WeireLong($num) {
		//echo "Long " . $num . "<br />";
		$this->len += 4;
		$this->data .= pack('l', $num);
	}

	//�̶����ȵ��ַ�
	function WeireString($str, $len) {
		//echo "String " . $str . "<br />";
		$this->len += $len;
		$this->data .= pack('a' . $len, $str);
	}
	//8个字节长度数字
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
