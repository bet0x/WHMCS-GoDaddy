<?php
namespace MichalZa\Registrar;

class Connection{

    const API_URL = 'https://api.ote-godaddy.com';

	protected $curl;

	protected $key;
	protected $secret;

	private $headers;

    public function __construct($key,$secret)
    {
    	$this->setParams($key,$secret);
    	$this->setHeaders();
    }

    public function setHeaders()
    {
    	$this->headers = array(
    		"Authorization: sso-key {$this->key}:{$this->secret}",
    		"Content-Type: application/json"
    	);
    }

    public function setParams($key,$secret)
    {
    	$this->key = $key;
    	$this->secret = $secret;
        $this->prepare();
    }

    public function prepare()
    {
    	$this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    }

    public function setUrl($url)
    {
    	curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    public function call($action, $data = null, $method = 'GET',$test = null)
    {
        $url = self::API_URL . $action;

        if($method == 'GET' && !$data == null)
        {     
            $url .= '?' . http_build_query($data);
        }
        
        $this->setUrl($url);

        switch ($method) {
            case 'GET':
                //noting
                break;

            case 'POST':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            break;
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

        $response = curl_exec($this->curl);
        if($test)
        {
             echo '<pre>';
            die(var_dump(json_decode($response)));
        }
        return json_decode($response);
        //echo '<pre>';
        //die(var_dump(json_decode($response)));


    }
}