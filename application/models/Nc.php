<?php

class Application_Model_Nc
{
	protected $_id;
	protected $_initiatorid;
	protected $_focalid;
	protected $_qaid;
	protected $_statusid;
	protected $_initdate;
	protected $_summary;
	protected $_details;

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
            throw new Exception('Invalid cat property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid cat property');
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


    public function setInitiatorid($initiatorid)
    {
        $this->_initiatorid = (int)$initiatorid;
        return $this;
    }

    public function getInitiatorid()
    {
        return $this->_initiatorid;
    }

    public function setFocalid($focalid)
    {
        $this->_focalid = (int)$focalid;
        return $this;
    }

    public function getFocalid()
    {
        return $this->_focalid;
    }

    public function setQaid($qaid)
    {
        $this->_qaid = (int)$qaid;
        return $this;
    }

    public function getQaid()
    {
        return $this->_qaid;
    }

    public function setStatusid($statusid)
    {
        $this->_statusid = (int)$statusid;
        return $this;
    }

    public function getStatusid()
    {
        return $this->_statusid;
    }

    public function setInitdate($initdate)
    {
        $this->_initdate = (string)$initdate;
        return $this;
    }

    public function getInitdate()
    {
        return $this->_initdate;
	}


    public function setSummary($summary)
    {
        $this->_summary = (string)$summary;
        return $this;
    }

    public function getSummary()
    {
        return $this->_summary;
    }
    public function setDetails($details)
    {
        $this->_details = (string)$details;
        return $this;
    }

    public function getDetails()
    {
        return $this->_details;
    }

}



