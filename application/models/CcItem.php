<?php

class Application_Model_CcItem
{
	protected $_id;
	protected $_ncid;
	protected $_description;
	protected $_ownerid;
	protected $_duedate;
	protected $_completiondate;

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


    public function setNcid($ncid)
    {
        $this->_ncid = (int)$ncid;
        return $this;
    }

	public function getNcid( )
	{
		return $this->_ncid;
	}

    public function setOwnerid($ownerid)
    {
        $this->_ownerid = (int)$ownerid;
        return $this;
    }

    public function getOwnerid()
    {
        return $this->_ownerid;
    }

    public function setCompletiondate($completiondate)
    {
        $this->_completiondate = (string)$completiondate;
        return $this;
    }

    public function getCompletiondate()
    {
        return $this->_completiondate;
    }

    public function setDuedate($duedate)
    {
        $this->_duedate = (string)$duedate;
        return $this;
    }

    public function getDuedate()
    {
        return $this->_duedate;
    }

	public function setDescription($description)
    {
        $this->_description = (string)$description;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

}

