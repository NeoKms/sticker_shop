<?php

abstract class Model {

    protected $request;
    protected $table;
    protected $properties = [];
    protected $maps=[];
    protected $db;
    private $salt = 'Fallus in frontem est mors momentana';

    public function __construct($body_request)
    {
        $this->setProperties();
        $this->setRequest($body_request);
        $this->db = DBase::getInstance();
    }

    protected function setRequest($req){
        $this->request = !empty($req) ? $req : [];
        return true;
    }

    protected function generateHash($pass)
    {
        $s1 = md5(md5($this->salt . $pass));
        $s2 = md5($pass . $s1 . $this->salt . $pass);
        $result = (substr($s2, 0, 16) . $s1 . substr($s2, -16));
        return $result;
    }

    /**
     * Вызывается в конструкторе и при генерации, чтобы дополнить базовый набор свойств
     */
    protected function setProperties()
    {
        return true;
    }

    protected final function tableExists()
    {
        return count(Dbase::getInstance()->query('SHOW TABLES LIKE "' . static::$table . '"')) > 0;
    }

    protected final function checkProperty($name,$val)
    {
       if ($this->properties[$name]['type']==gettype($val)){
           if (isset($this->properties[$name]['size'])){
                if (strlen((string)$val)>$this->properties[$name]['size']){
                    return [false,"Поле {$this->properties[$name]['name']} не может быть длинее {$this->properties[$name]['size']}"];
                }
                return [true,''];
           }else{
               return [true,''];
           }
       }
       return [false,"Неправильный тип поля {$this->properties[$name]['name']}"];
    }
    public function __call($name, $arguments) {
        return [];
    }
}
