<?php

const HOST     = 'index.hu';
const PROTOCOL = 'icmp';
const COUNT    = 3;
const TIMEOUT  = 1;

$ipAddress      = getIpAddress();
$protocolNumber = getprotobyname(PROTOCOL);
$socket         = socket_create(AF_INET, SOCK_RAW, $protocolNumber);
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => TIMEOUT, 'usec' => 0));
socket_connect($socket, HOST, 0);

echo sprintf('PING %s (%s):', HOST, $ipAddress) . PHP_EOL;

for ($i = 0; $i < COUNT; $i++) {
	$startTime = microtime(true);

	$package  = "\x08\x00\x19\x2f\x00\x00\x00\x00\x70\x69\x6e\x67";
	socket_send($socket, $package, strlen($package), 0);

	if (socket_read($socket, 255)) {
		printLine($i, $startTime);
	} else {
		echo 'Request timed out.';
	}
}

function getIpAddress()
{
	$ipAddress = gethostbyname(HOST);
	if ($ipAddress === HOST) {
		echo sprintf('ping: cannot resolve %s: Unknown host', HOST) . PHP_EOL;
		exit();
	}

	return $ipAddress;
}

function printLine($seq, $startTime)
{
	echo sprintf('icmp_seq=%d time=%s', $seq, formatTime(microtime(true) - $startTime)) . PHP_EOL;
}

function formatTime($time)
{
	return sprintf('%.3f ms', round($time * 1000, 3));
}

socket_close($socket);
