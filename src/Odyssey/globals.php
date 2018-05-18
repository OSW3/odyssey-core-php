<?php

namespace 
{
	function version()
	{
		return "0.0.1";
	}

	/**
	 * Dump
	 * @param  mixed $var La variable a déboger
	 */
	function dump($var, $type = 'print')
	{
		$dbt = debug_backtrace();

		echo '<pre style="padding: 10px; font-family: Consolas, Monospace; background-color: #000; color: #FFF;">';
		echo '<details style="display:inline-block;">';
		echo '<summary>'.print_r($var, true).'</summary>';
		echo 'File : '.$dbt[0]['file'].'<br>';
		echo 'Line : '.$dbt[0]['line'].'<br><br>';
  		echo '</details>';
		echo '</pre>';
	}

	/**
	 * GEt App
	 * @return \Odyssey\App L'application
	 */
	function getApp()
	{
		if (!empty($GLOBALS['app'])){
			return $GLOBALS['app'];
		}

		return null;
	}
}