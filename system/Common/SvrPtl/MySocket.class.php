<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/31
 * Time: 19:25
 */
include 'Socket.php';
include 'PHPStream.php';

class MySocket {
    private $socket;
    private $address;
    private $port;

    public function __construct($address,$service_port){
        $this->address = $address;
        $this->port = $service_port;
        $this->socket = ConnectServer($this->address,$this->port);
    }

    /**
     * @param $head 请求头
     * @param $body 参数
     */
    public function request($head,$body){
        $in = $head.$body;
        $in_len = strlen($in);
        socket_write($this->socket, $in, $in_len);
    }

    /**
     * 请求返回
     */
    public function response(){
        $buff = ReadData($this->socket);
        return $buff;
    }
    /**
     * 返回错误信息
     * @return int
     */
    public function error(){
        return socket_last_error($this->socket);
    }
    /**
     * 关闭连接
     */
    public function close(){
        socket_close($this->socket);
    }
}