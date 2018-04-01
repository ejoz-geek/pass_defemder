<?php
/*

@###############################@
##                             ##
##    PASS DEFENDER BY EJOZ    ##
##                             ##
@###############################@

Version: 0.1
Stage: pre-alpha
Author: EJOZ
Copyright © 2018 Stas Smirnov. All rights reserved.

This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0. If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.

*/
error_reporting(E_ALL);
if ($argv[1] == 'gen_array')
{
	unset($argv);

	define('CHARS', array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','0','1','2','3','4','5','6','7','8','9','.',',','(',')','[',']','!','?','&','^','%','@','*','$','<','>','|','+','-','{','}','`','~'));
	define('LEN', 4096);

	for ($i = 0; $i < LEN; $i++)
	{
		$index = random_int(0, count(CHARS) - 1);
		@$array .= CHARS[$index];
	}
	unset($index);
	unset($i);

	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'w+b');
	fwrite($f, base64_encode($array));
	unset($array);
	fclose($f);
	unset($f);

	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'rb');
	$array = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/array.dat')));
	fclose($f);
	unset($f);
	if (strlen($array) != LEN)
	{
		print_r('ERROR! PLEASE RETRY.');
		unset($array);
		exit(1);
	}
	elseif (strlen($array) == LEN)
	{
		print_r('SUCCESS!');
		unset($array);
		exit(0);
	}
	else
	{
		print_r('UNKNOWN ERROR!');
		unset($array);
		exit(5);
	}
}
elseif ($argv[1] == 'add_entry')
{	
	$name = $argv[2];
	$login = $argv[3];
	unset($argv);

	if (file_exists(dirname($_SERVER['PHP_SELF']) . '/denoms.dat'))
	{
		$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'a+b');
		$denoms = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/denoms.dat')));
		fclose($f);
		unset($f);
		$denoms = json_decode($denoms, true);
		$max = count($denoms);
		$denoms[$max + 1]['name'] = $name;
		unset($name);
		$denoms[$max + 1]['login'] = $login;
		unset($login);
		for ($i = 0; $i < 5; $i++)
		{
			$int = random_int(0, 1000);
			$denoms[$max + 1]['denoms'][$i] = $int;
		}
		unset($int);
		unset($max);
		$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'w+b');
		fwrite($f, base64_encode(json_encode($denoms, true)));
		unset($denoms);
		fclose($f);
		unset($f);
		print_r('SUCCESS!');
	}
	elseif (!file_exists(dirname($_SERVER['PHP_SELF']) . '/denoms.dat'))
	{
		$denoms[1]['name'] = $name;
		unset($name);
		$denoms[1]['login'] = $login;
		unset($login);
		for ($i = 0; $i < 5; $i++)
		{
			$denoms[1]['denoms'][$i] = random_int(0, 1000);
		}
		unset($int);
		$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'a+b');
		fwrite($f, base64_encode(json_encode($denoms, true)));
		unset($denoms);
		fclose($f);
		unset($f);
		print_r('SUCCESS!');
	}
	exit(0);
}
elseif ($argv[1] == 'decrypt')
{
	unset($argv);

	define('LEN', 4096);
	define('FACTOR', 3);
	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'rb');
	$denoms = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/denoms.dat')));
	$denoms = json_decode($denoms, true);
	fclose($f);
	unset($f);
	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'rb');
	$array = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/array.dat')));
	fclose($f);
	unset($f);

	$max = strlen($array);
	for ($i = 1; $i < count($denoms) + 1; $i++)
	{
		print_r('----------------------' . PHP_EOL);
		print_r('Name: ' . $denoms[$i]['name'] . PHP_EOL);
		print_r('Login: ' . $denoms[$i]['login'] . PHP_EOL);
		for ($f=0; $f < 10; $f++)
		{
			if ($f <= 4){
				$char[$f] = substr($array, (LEN * FACTOR) / $denoms[$i]['denoms'][$f], 1);
			}
			else
			{
				$char[$f] = substr($array, (LEN * FACTOR) / ($denoms[$i]['denoms'][$f - 5] * 2), 1);
			}
		}
		unset($f);
		for ($f=0; $f < 10; $f++)
		{
			@$pass .= $char[$f];
		}
		unset($f);
		unset($char);
		print_r('Pass: ' . $pass . PHP_EOL);
		print_r('----------------------' . PHP_EOL);
		unset($pass);
	}
	unset($pass);
	unset($max);
	unset($denoms);
	unset($array);
	print_r('SUCCESS!');
	exit(0);
}
elseif ($argv[1] == 'clear')
{
	unlink(dirname($_SERVER['PHP_SELF']) . '/denoms.dat');
	unlink(dirname($_SERVER['PHP_SELF']) . '/array.dat');
	unlink($_SERVER['PHP_SELF']);
	if (!file_exists(dirname($_SERVER['PHP_SELF']) . '/denoms.dat') && !file_exists(dirname($_SERVER['PHP_SELF']) . '/array.dat') && !file_exists($_SERVER['PHP_SELF']))
	{
		print_r('SUCCESS!');
	}
	else
	{
		print_r('ERROR! PLEASE RETRY.');
	}
	exit(0);
}
else
{
	exit(1);
}
exit();
?>