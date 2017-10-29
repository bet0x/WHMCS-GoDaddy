<?php
require_once 'Model.php';

class DomainUpdate extends Model{

	protected $serializable = [
        'locked',
        'nameServers',
        'renewAuto',
        'subaccountId'
    ];
	protected $locked = false;
	protected $nameServers;
	protected $renewAuto = false;
	protected $subaccountId;

	public function setSerializable($serializable) 
    {
        $this->serializable = $serializable;
        return $this;
    }

    public function setLocked($locked) 
    {
        $this->locked = $locked;
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

    public function setSubaccountId($subaccountId) 
    {
        $this->subaccountId = $subaccountId;
        return $this;
    }

}

