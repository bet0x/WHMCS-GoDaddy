<?php

function autoloader($path)
{
    $prepare = explode('\\', $path);
    $class = end($prepare);
    if($class != 'Api' && $class != 'DomainContact')

    {
    	die(var_dump($class));
    }
    if(strpos($class, 'Domain'))
    {
    	include 'Model' . DS . $class . '.php';
    	return;
   	}  
    
    include $class . '.php';
}

spl_autoload_register('autoloader');