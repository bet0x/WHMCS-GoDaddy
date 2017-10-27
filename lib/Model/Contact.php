<?php
require_once 'Model.php';

class Contact extends Model{

	protected $serializable = [
        'nameFirst',
        'nameMiddle',
        'nameLast',
        'organization',
        'jobTitle',
        'email',
        'phone',
        'fax',
        'addressMailing'
    ];
	protected $nameFirst;
	protected $nameMiddle;
	protected $nameLast;
	protected $organization;
	protected $jobTitle;
	protected $email;
	protected $phone;
	protected $fax;
	protected $addressMailing;

	
public function setNameFirst($nameFirst) {
$this->nameFirst = $nameFirst;
return $this;
}

public function setNameMiddle($nameMiddle) {
$this->nameMiddle = $nameMiddle;
return $this;
}

public function setNameLast($nameLast) {
$this->nameLast = $nameLast;
return $this;
}

public function setOrganization($organization) {
$this->organization = $organization;
return $this;
}

public function setJobTitle($jobTitle) {
$this->jobTitle = $jobTitle;
return $this;
}

public function setEmail($email) {
$this->email = $email;
return $this;
}

public function setPhone($phone) {
$this->phone = $phone;
return $this;
}

public function setFax($fax) {
$this->fax = $fax;
return $this;
}

public function setaddressMailing($addressMailing) {
$this->addressMailing = $addressMailing;
return $this;
}
}

