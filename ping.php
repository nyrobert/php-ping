#!/usr/bin/env php
<?php

const PROTOCOL = 'icmp';

function ping()
{
	$ipAddress      = getIpAddress();
	$protocolNumber = getprotobyname(PROTOCOL);
	$socket         = socket_create(AF_INET, SOCK_RAW, $protocolNumber);
	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
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

		if ($i < (COUNT - 1)) {
			sleep(INTERVAL);
		}
	}

	socket_close($socket);
}

function parseOptions($argv)
{
	$options = getopt('c:i:');

	define('COUNT', isset($options['c']) ? (int) $options['c'] : 3);
	define('INTERVAL', isset($options['i']) ? (int) $options['i'] : 1);

	$host = array_pop($argv);
	if (count($argv) === 0 || empty($host)) {
		echo 'ping: empty host' . PHP_EOL;
		exit();
	}

	define('HOST', $host);
}

function getIpAddress()
{
	$ipAddress = gethostbyname(HOST);
	var_dump($ipAddress);
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

parseOptions($argv);
ping();
