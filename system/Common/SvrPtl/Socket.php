<?php

function ConnectServer($address, $port) {
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
		echo "socket_create() failed. address: $address, port: $port, Reason: " . socket_strerror(socket_last_error()) . "\n";
	}

	//socket_set_timeout($socket, 1, 0);
	//echo "Attempting to connect to '$address' on port '$service_port'...";
	$result = socket_connect($socket, $address, $port);
	if ($result === false) {
		//echo "socket_connect() failed. address: $address, port: $port, Reason: " . socket_strerror(socket_last_error($socket)) . "\n";
	}
	return $socket;
}

function ReadData($socket) {
	//echo "ReadData";
	$buff = "";
	$out = "";
	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 10, "usec" => 0));
	while ($out = socket_read($socket, 1024, PHP_BINARY_READ)) {
		//echo "data: ". $out;
		$buff .= $out;
	}
	//socket_close($socket);
	return $buff;
};
