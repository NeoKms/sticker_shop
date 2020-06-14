<?php

class Users extends Model {
    protected $table = 'users';

    protected function setProperties()
    {

        $this->properties['id'] = [
            'type' => 'integer',
            'name'=>'ИД'
        ];

        $this->properties['login'] = [
            'type' => 'string',
            'name'=>'логин'
        ];

        $this->properties['password'] = [
            'type' => 'string',
            'name'=>'пароль'
        ];

        $this->properties['email'] = [
            'type' => 'string',
            'name'=>'емаил'
        ];

        $this->properties['name'] = [
            'type' => 'string',
            'name'=>'имя'
        ];

        $this->properties['phone'] = [
            'type' => 'string',
            'size' => 12,
            'name'=>'телефон'
        ];
        $this->maps[$this->table]=[
            'id'=>'id',
            'login'=>'login',
            'password'=>'password',
            'email'=>'email',
            'phone'=>'phone',
            'name'=>'name'
        ];
        $this->maps['user_group']=[
            'user_group_id'=>'id',
            'group_id'=>'group_id',
            'user_id'=>'user_id'
        ];
    }

    public function getCount(){
        if ($_SESSION["USER"]['group_id']!=1) die("нет прав");
        $col=$this->db->query("SELECT COUNT(*) as cnt FROM ".$this->table);
        if ($col!==false)
            return $col[0]['cnt'];
        return [];
    }

    public static function init($id=false){
        if ($id>0) $_SESSION['uid']=$id;
        if (!isset($_SESSION['uid']))
            $_SESSION['uid']=-1;
        else
            (new Users(['id'=>$_SESSION['uid']]))->getInfo();
        if ($id<=0)
            Orders::initBasket();
    }

    public function getInfo(){
        $id=$this->request['id'];
        if ($id>0){
            $sql = $this->db->selectValue(
                ['name','login','phone','email'],
                ['id' => $id],
                $this->maps[$this->table],
                [],
                $this->table
            );
            $sql=$this->db->InnerJoin($sql,'user_group',$this->table,'user_id','id',$this->maps['user_group'],['group_id']);
            $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql["join"] .  $sql['where'];
            $res = $this->db->query($q);
            if ($res!==false && !empty($res)) {
                $_SESSION['USER'] = $res[0];
            }
        }
    }
    public function auth()
    {
        $param=$this->request['params'];
        if (!empty($param['login']) && !empty($param['password'])) {
            $id=$this->checkUsr($param['login'],$param['password']);
            if ($id!==false) {
                Users::init($id['id']);
                return ['auth'=>$id['id']];
            }else {
                $_SESSION['uid']=-1;
                return ['error'=>'Неверный логин или пароль'];
            }
        }
        return [];
    }

    private function checkUsr($login,$pass=false){
        $filter=['login' => $login];
        if (!empty($pass))
            $filter['password'] =$this->generateHash($pass);
        $sql = $this->db->selectValue(['id'], $filter, $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        if ($res!==false && !empty($res)){
            return $res[0];
        }
        return false;
    }

    public function registration()
    {
        $_SESSION['uid'] = -1;
        $param=$this->request['params'];
        if (!empty($param['login']) && !empty($param['password'])) {
            $param['password']=$this->generateHash($param['password']);
            $id=$this->checkUsr($param['login']);
            if ($id!==false) {
                return ['error'=>'Такой пользователь уже существует'];
            }else {

                if (!isset($param['phone']) && ((int)$param['login'])>0)
                    $param['phone']=$param['login'];

                foreach ($this->maps[$this->table] as $name => $as){
                    if (isset($param[$name])){
                        $res=$this->checkProperty($name,$param[$name]);
                        if (!$res[0]) return ['error'=>$res[1]];
                    }
                }
                $res=$this->db->upsert($this->table,0,$param,$this->maps[$this->table]);
                if ($res[0]){
                    $_SESSION['uid'] = $res[1];
                    $this->setGroup($_SESSION['uid'],2);
                    return ['res'=>'success'];
                }else{
                    return ['error'=>'Непредвиденная ошибка'];
                }
            }
        }
        return [];
    }

    private function setGroup($id,$gr){
        $this->db->query("INSERT INTO `user_group` (`id`, `user_id`, `group_id`) VALUES (NULL, {$id}, {$gr})",true);
    }
}
