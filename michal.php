<?php
require_once 'lib/Api.php';
require_once 'lib/Model/DomainPurchase.php';
require_once 'lib/Model/Contact.php';


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
            'Type'         => 'text',
            'Size'         => '25',
            'Default'      => ''
        ],
        'ApiSecret'     => [
            'FriendlyName' => 'Api Secret',
            'Type'         => 'text',
            'Size'         => '55',
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

	$agree = $api->get('/v1/domains/agreements',$agreeData);
	$accepted = array();

	foreach($agree as $agreement)
	{
		$accepted[] = $agreement->agreementKey;
	}

	$contact = new Contact();
	$contact->setNameFirst($params['firstname'])
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

	
	$domainPurchase = new DomainPurchase();
	$domainPurchase ->setDomain($params['domainname'])
					->setConsent(array(
						'agreementKeys'	=> $accepted,
						'agreedBy' 		=> '10.10.10.10',
						'agreedAt' 		=> gmdate("Y-m-d\TH:i:s\Z")
					))
					->setPeriod((int)$params['regperiod'])
					->setNameServers(array($params['ns1'],$params['ns2'],$params['ns3'],$params['ns4'],$params['ns5']))
					->setContactRegistrant($contact)
					->setContactAdmin($contact)
					->setContactTech($contact)
					->setContactBilling($contact);
	
	$domain = json_encode($domainPurchase);

	$api->post('/v1/domains/purchase',$domain,1);
}

function michal_RenewDomain($params)
{

}