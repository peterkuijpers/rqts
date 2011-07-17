<?php

class Application_Model_Cc
{
	protected $_id;
	protected $_assigneeid;
	protected $_approvaldate;
	protected $_verifierid;
	protected $_verifydate;
	protected $_verifystatusid;
	protected $_verifycomments;


	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid cc-item property');
		}
		$this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid cc-item property');
		}
		return $this->$method();
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setAssigneeid( $assigneeid )
	{
		$this->_assigneeid = (int)$assigneeid;
		return $this;
	}

	public function getAssigneeid()
	{
		return $this->_assigneeid;
	}

	public function setApprovaldate( $approvaldate )
	{
		$this->_approvaldate = (string)$approvaldate;
		return $this;
	}

	public function getApprovaldate()
	{
		return $this->_approvaldate;
	}

	public function setVerifierid( $verifierid )
	{
		$this->_verifierid = (int)$verifierid;
		return $this;
	}

	public function getVerifierid()
	{
		return $this->_verifierid;
	}



	public function setVerifydate( $verifydate )
	{
		$this->_verifydate = (string)$verifydate;
		return $this;
	}

	public function getVerifydate()
	{
		return $this->_verifydate;
	}



	public function setVerifystatusid( $verifystatusid )
	{
		$this->_verifystatusid = (string)$verifystatusid;
		return $this;
	}

	public function getVerifystatusid()
	{
		return $this->_verifystatusid;
	}

	public function setVerifycomments( $verifycomments )
	{
		$this->_verifycomments = (string)$verifycomments;
		return $this;
	}

	public function getVerifycomments()
	{
		return $this->_verifycomments;
	}

}


