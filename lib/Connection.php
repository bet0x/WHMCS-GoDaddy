<?php
namespace MichalZa\Registrar;

class Connection{

	protected $curl;
	protected $key;
	protected $secret;

	private $headers;

	const API_URL = 'https://api.ote-godaddy.com';

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

    public function setCurlHeaders()
    {
    	curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
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

        if($method == 'GET' && $data != null)
        {   
        	if(is_array($data))
        	{
            	$url .= '?' . http_build_query($data);
            }
            else
            {
            	$url .= $data;
            }
        }

        switch ($method) 
        {
            case 'GET':
                //noting
                break;

            case 'POST':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            break;

            case 'PATCH':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            break;
        }

  
        
        $this->setUrl($url);
        $this->setCurlHeaders();

        $result = curl_exec($this->curl);
        self::checkCurl($result,$this->curl);

        return $this->parse($result);

    }

    public static function checkCurl($response,$curl)
    {
		if(!$response)
		{
			throw new \Exception(curl_error($curl));
		}  	
    }

    public function parse($json)
    {
    	$response = json_decode($json);
    	return $this->checkErros($response);
    }

    public function checkErros($response)
    {
    	if($response->code)
    	{
    		if($response->message)
    		{
    			throw new \Exception($response->code . ' : ' . $response->message);
    		}
    		elseif($response->fields[0]->message)
    		{
    			throw new \Exception($response->code . ' : ' . $response->fields[0]->message);
    		}
    	}

    	return $response;
    }
}