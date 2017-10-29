<?php
require_once 'Model.php';

class DomainPurchase extends Model{

    protected $serializable = [
        'domain',
        'consent',
        'period',
        'nameServers',
        'renewAuto',
        'privacy',
        'contactRegistrant',
        'contactAdmin',
        'contactTech',
        'contactBilling'
    ];
	protected $domain;
	protected $consent;
	protected $period;
	protected $nameServers;
	protected $renewAuto = false;
	protected $privacy = false;
	protected $contactRegistrant;
	protected $contactAdmin;
	protected $contactTech;
	protected $contactBilling;


	public function setDomain($domain) 
    {
        $this->domain = $domain;
        return $this;
    }

    public function setConsent($consent) 
    {
        $this->consent = $consent;
        return $this;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    public function setNameServers($nameServers) 
    {
        $this->nameServers = $nameServers;
        return $this;
    }

    public function setRenewAuto($renewAuto) 
    {
        $this->renewAuto = $renewAuto;
        return $this;
    }

    public function setPrivacy($privacy) 
    {
        $this->privacy = $privacy;
        return $this;
    }

    public function setContactRegistrant($contactRegistrant) 
    {
        $this->contactRegistrant = $contactRegistrant;
        return $this;
    }

    public function setContactAdmin($contactAdmin) 
    {
        $this->contactAdmin = $contactAdmin;
        return $this;
    }

    public function setContactTech($contactTech) 
    {
        $this->contactTech = $contactTech;
        return $this;
    }

    public function setContactBilling($contactBilling) 
    {
        $this->contactBilling = $contactBilling;
        return $this;
    }


}