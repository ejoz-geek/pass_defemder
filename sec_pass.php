<?php
/*
Main repository https://github.com/ejoz-geek/pass_defender
When the first start, update the file start.bat
You may download PHP from https://php.net/downloads.php


------------------------------------------------------------------------
Copyright 2018 Stas Smirnov

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
------------------------------------------------------------------------

*/
error_reporting(E_ALL);
define('VER', '0.1.0-pre-alpha' . PHP_EOL);

start:
$command = readline('>');
switch ($command)
{
	case 'help':
		print_r('HELP' . PHP_EOL);
		goto start;
		break;
	case 'ver':
		print_r(VER);
		goto start;
		break;
	case 'sha1':
		print_r('SHA1: ' . sha1_file($_SERVER['PHP_SELF']) . PHP_EOL);
		goto start;
		break;
	case 'md5':
		print_r('MD5: ' . md5_file($_SERVER['PHP_SELF']) . PHP_EOL);
		goto start;
		break;
	case 'gen_array':
		gen_array();
		goto start;
		break;
	case 'add_record':
		add_record();
		goto start;
		break;
	case 'decrypt':
		decrypt();
		goto start;
		break;
	case 'exit':
		exit('Exit. Called by user...');
		break;
	default:
		print_r('Unknown command!' . PHP_EOL);
		goto start;
		break;
}
function gen_array ()
{
	gen_array_start:
	print_r('Type array length...' . PHP_EOL);
	$len = readline('>');
	if (!is_int($len) && ($len < 1000 || $len > 100000))
	{
		print_r('Invalid value. The length must be a number. Not less than 1000 and not more than 100000' . PHP_EOL);
		goto gen_array_start;
	}
	else
	{
		$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','0','1','2','3','4','5','6','7','8','9','.',',','(',')','[',']','!','?','&','^','%','@','*','$','<','>','|','+','-','{','}','`','~');
		for ($i = 0; $i < $len; $i++)
		{
			$index = random_int(0, count($chars) - 1);
			@$array .= $chars[$index];
		}
		$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'w+b');
		fwrite($f, base64_encode($array));
		fclose($f);
		unset($chars);
	
		$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'rb');
		$array = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/array.dat')));
		fclose($f);
		if (strlen($array) != $len)
		{
			print_r('Error! Please retry.' . PHP_EOL);
			return;

		}
		elseif (strlen($array) == $len)
		{
			print_r('Success!' . PHP_EOL);
			return;
		}
		else
		{
			print_r('Unkown error!' . PHP_EOL);
			return;
		}
	}
}
function add_record ()
{
	add_record_start:
	print_r('Type password length...' . PHP_EOL);
	$pass_len = readline('>');
	if (!is_int($pass_len) && ($pass_len < 6 || $pass_len > 32))
	{
		print_r('Invalid value. The length must be a number. Not less than 6 and not more than 32' . PHP_EOL);
		goto add_record_start;
	}
	else
	{
		print_r('Type service name...' . PHP_EOL);
		$name = readline('>');
		print_r('Type login for service...' . PHP_EOL);
		$login = readline('>');
		if (file_exists(dirname($_SERVER['PHP_SELF']) . '/denoms.dat'))
		{
			$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'a+b');
			$denoms = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/denoms.dat')));
			fclose($f);
			$denoms = json_decode($denoms, true);
			$max = count($denoms);
			$denoms[$max + 1]['name'] = $name;
			$denoms[$max + 1]['login'] = $login;
			for ($i = 0; $i < $pass_len; $i++)
			{
				$int = random_int(0, 1000);
				$denoms[$max + 1]['denoms'][$i] = $int;
			}
			$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'w+b');
			fwrite($f, base64_encode(json_encode($denoms, true)));
			fclose($f);
			print_r('Success!' . PHP_EOL);
		}
		elseif (!file_exists(dirname($_SERVER['PHP_SELF']) . '/denoms.dat'))
		{
			$denoms[1]['name'] = $name;
			$denoms[1]['login'] = $login;
			for ($i = 0; $i < $pass_len; $i++)
			{
				$denoms[1]['denoms'][$i] = random_int(0, 1000);
			}
			$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'a+b');
			fwrite($f, base64_encode(json_encode($denoms, true)));
			fclose($f);
			print_r('Success!' . PHP_EOL);
		}
		return;
	}
}
function decrypt ()
{
	print_r('Type your pin...' . PHP_EOL);
	$pin = readline('>');
	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/denoms.dat', 'rb');
	$denoms = json_decode(base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/denoms.dat'))), true);
	fclose($f);
	$f = fopen(dirname($_SERVER['PHP_SELF']) . '/array.dat', 'rb');
	$array = base64_decode(fread($f, filesize(dirname($_SERVER['PHP_SELF']) . '/array.dat')));
	$len = strlen($array);
	fclose($f);

	for ($i = 0; $i < count($denoms); $i++)
	{
		print_r('----------------------' . PHP_EOL);
		print_r('Name: ' . $denoms[$i + 1]['name'] . PHP_EOL);
		print_r('Login: ' . $denoms[$i + 1]['login'] . PHP_EOL);
		for ($f = 0; $f < count($denoms[$i + 1]['denoms']); $f++)
		{
			$char[$f] = substr($array, round(($len * ($pin / 100)) / $denoms[$i + 1]['denoms'][$f]), 1);
		}
		unset($f);
		for ($f = 0; $f < count($denoms[$i + 1]['denoms']); $f++)
		{
			@$pass .= $char[$f];
		}
		unset($f);
		unset($char);
		
		print_r('Pass: ' . $pass . PHP_EOL);
		print_r('----------------------' . PHP_EOL);

		unset($pass);
	}
	print_r('Success!' . PHP_EOL);
	return;
}
