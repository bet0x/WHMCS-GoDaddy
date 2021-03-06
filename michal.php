<?php
require_once 'lib/autoloader.php';

use WHMCS\Database\Capsule as DB;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function michal_MetaData()
{
    return array(
        'DisplayName' => 'Michal registrar',
        'APIVersion' => '1.1',
    );
}

function michal_getConfigArray()
{
    return [
        'FriendlyName' => [
            'Type'  => 'System',
            'Value' => 'Michal ',
        ],
        'ApiKey'     => [
            'FriendlyName' => 'Api Key',
            'Type'         => 'password',
            'Size'         => '50',
            'Default'      => ''
        ],
        'ApiSecret'     => [
            'FriendlyName' => 'Api Secret',
            'Type'         => 'password',
            'Size'         => '50',
            'Default'      => '',
        ],
         'Test'           => array
            (
            'Type'        => 'yesno',
            'Description' => 'Check to enable test mode'
        ),

    ];
}


function michal_RegisterDomain($params)
{
	$api = new Api($params['ApiKey'],$params['ApiSecret']);

	$agreeData = array(
		'tlds'		=>	$params['tld'],
		'privacy'	=>	false	
	);

	try
	{
		$agree = $api->get('/v1/domains/agreements',$agreeData);
	}
	catch(Exception $ex)
	{
		return array('error' => $ex->getMessage());
	}

	$consent = array();

	foreach($agree as $agreement)
	{
		$consent[] = $agreement->agreementKey;
	}

	$contactRegistrant = new DomainContact();
	$contactRegistrant 	->setNameFirst($params['firstname'])
						->setNameMiddle($params['firstname'])
						->setNameLast($params['lastname'])
						->setOrganization($params['companyname'])
						->setJobTitle($params['companyname'])
						->setEmail($params['email'])
						->setPhone($params['phonenumberformatted'])
						->setAddressMailing(array(
							'address1'	=>	$params['address1'],
							'address2'	=>	$params['address2'],
							'city'		=>	$params['city'],
							'state'		=>	$params['state'],
							'postalCode'=>	$params['postcode'],
							'country'	=>	$params['countrycode']
			));

	$contactRest = new DomainContact();
	$contactRest->setNameFirst($params['adminfirstname'])
				->setNameMiddle($params['adminfirstname'])
				->setNameLast($params['adminlastname'])
				->setOrganization($params['admincompanyname'])
				->setJobTitle($params['admincompanyname'])
				->setEmail($params['adminemail'])
				->setPhone($params['adminfullphonenumber'])
				->setAddressMailing(array(
					'address1'	=>	$params['adminaddress1'],
					'address2'	=>	$params['adminaddress2'],
					'city'		=>	$params['admincity'],
					'state'		=>	$params['adminstate'],
					'postalCode'=>	$params['adminpostcode'],
					'country'	=>	$params['admincountry']
			));

	$nameServers = array();

	for($i = 1;$i <= 5;$i++)
	{
		if($params['ns'.$i] != null)
		{
			$nameServers[] = $params['ns'.$i];
		}
	}

	$row = DB::table('tbldomains')	->where('tbldomains.id','=',$params['domainid'])
									->join('tblorders','tblorders.id','=','tbldomains.orderid')
									->first();

	$domainPurchase = new DomainPurchase();
	$domainPurchase ->setDomain($params['domainname'])
					->setConsent(array(
						'agreementKeys'	=> $consent,
						'agreedBy' 		=> $row->ipaddress,
						'agreedAt' 		=> date("Y-m-d\TH:i:s\Z")
					))
					->setPeriod((int)$params['regperiod'])
					->setNameServers($nameServers)
					->setContactRegistrant($contactRegistrant)
					->setContactAdmin($contactRest)
					->setContactTech($contactRest)
					->setContactBilling($contactRest);

	$domain = json_encode($domainPurchase);

	try
	{
		$api->post('/v1/domains/purchase',$domain);
	}
	catch(Exception $ex)
	{
		return array('error' => $ex->getMessage());
	}

	return 'success';
}

function michal_GetNameServers($params)
{
	$api = new Api($params['ApiKey'],$params['ApiSecret']);

	try
	{
		$domainInfo = $api->get('/v1/domains/',$params['domainname']);
	}
	catch(Exception $ex)
	{
		return array('error' => $ex->getMessage());
	}
	
	$nameServers = array();

	foreach($domainInfo->nameServers as $key => $val)
	{
		$nameServers['ns' . ($key+1)] = $val;
	}

	return $nameServers;											
}

function michal_SaveNameServers($params)
{

	$api = new Api($params['ApiKey'],$params['ApiSecret']);

	try
	{
		$domainInfo = $api->get('/v1/domains/',$params['domainname']);
	}
	catch(Exception $ex)
	{
		return array('error' => $ex->getMessage());
	}

	$nameServers = array();

	for($i = 1;$i <= 5;$i++)
	{
		if($params['original']['ns'.$i] != null)
		{
			$nameServers[] = $params['original']['ns'.$i];
		}
	}

	$domainUpdate = new DomainUpdate();
	$domainUpdate 	->setLocked($domainInfo->locked)
					->setNameServers($nameServers)
					->setRenewAuto($domainInfo->renewAuto);

	$domain = json_encode($domainUpdate);

	try
	{
		$a = $api->patch('/v1/domains/'.$params['domainname'] ,$domain);
	}
	catch(Exception $ex)
	{
		return array('error'	=>	$ex->getMessage());
	}

	return 'success';
}

function michal_GetRegistrarLock($params)
{
	$api = new Api($params['ApiKey'],$params['ApiSecret']);

	try
	{
		$domainInfo = $api->get('/v1/domains/',$params['domainname']);
	}
	catch(Exception $ex)
	{
		return array('error' => $ex->getMessage());
	}

	return $domainInfo->locked; 
}

