<?php

const HOST     = 'www.index.hu';
const PROTOCOL = 'icmp';
const COUNT    = 3;
const TIMEOUT  = 1;

$protocolNumber = getprotobyname(PROTOCOL);
$socket = socket_create(AF_INET, SOCK_RAW, $protocolNumber);
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => TIMEOUT, 'usec' => 0));
socket_connect($socket, HOST, 0);

for ($i = 1; $i <= COUNT; $i++) {
	$startTime = microtime(true);

	$package  = "\x08\x00\x19\x2f\x00\x00\x00\x00\x70\x69\x6e\x67";
	socket_send($socket, $package, strlen($package), 0);

	if (socket_read($socket, 255)) {
		echo formatTime(microtime(true) - $startTime) . PHP_EOL;
	} else {
		$result = false;
	}
}

function formatTime($time)
{
	return round($time * 1000, 3) . ' ms';
}

socket_close($socket);
