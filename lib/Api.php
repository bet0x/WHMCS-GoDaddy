<?php
require_once 'Connection.php';
use MichalZa\Registrar\Connection;

class Api extends Connection
{

	public function __construct($key,$secret)
	{
		parent::__construct($key,$secret);
	}

    public function get($url,$data = null)
    {
  		return $this->call($url,$data,'GET');  	
    }

    public function post($url,$data)
    {
    	return $this->call($url,$data,'POST');
    }

    public function patch($url,$data)
    {
    	return $this->call($url,$data,'PATCH',1);
    }
}
