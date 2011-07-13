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

    public function getNickname()
    {
        return $this->_nickname;
    }

    public function setPassword($password)
    {
        $this->_password = (string)$password;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setFirstname($firstname)
    {
        $this->_firstname = (string)$firstname;
        return $this;
    }

    public function getFirstname()
    {
        return $this->_firstname;
    }

    public function setLastname($lastname)
    {
        $this->_lastname = (string)$lastname;
        return $this;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }

    public function setEmail($email)
    {
        $this->_email = (string)$email;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
	}
}

