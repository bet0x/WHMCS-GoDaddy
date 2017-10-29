<?php

function autoloader($path)
{

    $prepare = explode('\\', $path);
    $class = end($prepare);
    
    $try = include $class . '.php';

    if($try == false)
    {
    	include 'Model' . DS . $class . '.php';
    	return;
    }
    
    return;
}

spl_autoload_register('autoloader');