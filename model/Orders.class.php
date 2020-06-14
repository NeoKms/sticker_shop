<?php


class Orders extends Model
{
    protected $table = 'orders';
    protected $dopTables=[
        'user_info'=>'users',
    ];
    protected function setProperties ()
    {

        $this->properties['id'] = ['type' => 'integer', 'name' => 'ИД'];

        $this->properties['id_user'] = ['type' => 'integer', 'name' => 'айди пользователя'];

        $this->properties['code'] = ['type' => 'string', 'name' => 'код уникальный'];

        $this->properties['date'] = ['type' => 'datetime', 'name' => 'описание'];

        $this->properties['status'] = ['type' => 'integer', 'name' => 'описание'];

        $this->maps[$this->table] = ['id' => 'id', 'id_user' => 'id_user', 'code_order' => 'code', 'date_order' => 'date','status'=>'status'];
        $this->maps['users']=[
            'user_id'=>'id',
            'login'=>'login',
            'password'=>'password',
            'email'=>'email',
            'phone'=>'phone',
            'name'=>'name'
        ];
    }

    public static function initBasket(){
        //проверяем зареган ли пользователь
        if ($_SESSION['uid']>0){
            $code=(new Orders(
                ['params'=>[
                    'id_user'=> $_SESSION['uid']
                ]]))->getByUser();
            if ($code!==false && !empty($code))
                $_SESSION['basket']['code']=$code[0]['code_order'];
        }
        //если получили код от зареганного, то берем его, иначе создаем новую карзину
        if (!isset($_SESSION['basket']['code'])) {
            (new Orders([]))->newBasket();
        }else {
            //если код получен, то запрашиваем карзину и забираем ее ид и колво товаров. если ее не нашли, то создаем новую.
            $res=(new Orders(
                ['params'=>[
                    'code'=> $_SESSION['basket']['code'],
                    'status' =>1,
                ]
                ]))->getByCode();
            if (empty($res))
                $res=(new Orders([]))->newBasket();
            if($res[0]!==true){
                $idOrder=$res[0]['id'];
            }else{
                $idOrder=$res[1];
            }
            $_SESSION['basket']['id']=$idOrder;
            $_SESSION['basket']['count']=(new Basket(
                ['params'=>[
                'id_order_basket'=> $idOrder
                ]]))->getCountByIdOrder();
            if (empty($_SESSION['basket']['count'])) $_SESSION['basket']['count']=0;
            else{
                $_SESSION['basket']['count']=count($_SESSION['basket']['count']);
            }
        }
    }

    public function newBasket(){
        $_SESSION['basket']['count'] = 0;
        $_SESSION['basket']['code'] = microtime(true);
        $props=[
            'code_order'=>$_SESSION['basket']['code'],
            'date_order'=>date('Y-m-d H:i:s'),
            'status' => 1,
        ];
        if ($_SESSION['uid']>0)$props['id_user']=$_SESSION['uid'];
        $res=$this->db->upsert($this->table,0,$props,$this->maps[$this->table]);
        if ($res!==false)
            $_SESSION['basket']['id']=$res[1];
        return $res;
    }

    public function getByCode ()
    {
        $param = $this->request['params'];
        if (empty($param['code']))
            return [];
        $filter=['code_order' => $param['code']];
        if (isset($param['status'])) $filter['status']=$param['status'];
        $sql = $this->db->selectValue([],$filter , $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        if($res!==false && !empty($res) && $_SESSION['uid']>0)
            $this->db->upsert($this->table, $res[0]['id'], ['id_user' => $_SESSION['uid']], $this->maps[$this->table]);
        return $res;
    }

    public function getById ()
    {
        $param = $this->request['params'];
        $id = $this->request['id'];
        if ($id < 0)
            return [];
        $sql = $this->db->selectValue([], ['id' => $id], $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }

    public function getByUser()
    {
        $param = $this->request['params'];
        if (empty($param['id_user']))
            return [];
        $sql = $this->db->selectValue(['code_order'], ['id_user' => $param['id_user']], $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'] . 'ORDER BY date DESC LIMIT 1';
        $res = $this->db->query($q);
        return $res;
    }

    public function closeOrder(){
        if ($_SESSION['basket']['id']>0)
            $id=$_SESSION['basket']['id'];
        else
            return false;
        $proprs=['status'=>2];
        if ($_SESSION['uid']>0)
            $proprs['id_user']=$_SESSION['uid'];
        $res=$this->db->upsert($this->table,$id,$proprs,$this->maps[$this->table]);
        return $res;
    }

    private function __getList($param){
        $sql = $this->db->selectValue(
            isset($param['select'])?$param['select']:[],
            isset($param['filter'])?$param['filter']:[],
            $this->maps[$this->table],
            [],
            $this->table
        );
        if (!in_array('only_count',$param['dop_eq']) && in_array('with_users',$param['dop_eq'])){
        $sql=$this->db->innerJoin(
            $sql,
            $this->dopTables['user_info'],
            $this->table,
            'id','id_user',
            $this->maps[$this->dopTables['user_info']],
            ['phone','email','name'],
            []
        );
        }
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['join']. $sql['where'] . "ORDER BY $this->table.date DESC";
        $res = $this->db->query($q);
        if (in_array('only_count',$param['dop_eq'])){
            return count($res);
        }
        $arrOrders = [];
        if ($res!==false) {
            foreach ($res as $oneOrder){
                $oder=[
                    'id'=>$oneOrder['id'],
                    'code'=>$oneOrder['code_order'],
                    'date'=>$oneOrder['date_order'],
                ];
                if (in_array('with_basket',$param['dop_eq'])){
                    $oder['basket']=(new Basket(['params'=>['id_order'=>$oneOrder['id']]]))->getBasket();
                }
                if (!isset($arrOrders[$oneOrder['id_user']])){
                    $arrOrders[$oneOrder['id_user']]=['orders'=>[0=>$oder]];
                    if (in_array('with_users',$param['dop_eq']))
                        $arrOrders[$oneOrder['id_user']]['user']=[
                        'id'=>$oneOrder['id_user'],
                        'email'=>$oneOrder['email'],
                        'phone'=>$oneOrder['phone'],
                        'name'=>$oneOrder['name']
                    ];
                    }
                else{
                    $arrOrders[$oneOrder['id_user']]['orders'][]=$oder;
                }
            }
        }
        return $arrOrders;
    }
    public function getList(){
        $params=$this->request['params'];
        if (isset($this->request['id'])) {
            $params['select']['&id']=$this->request['id'];
        }
        return $this->__getList($params);
    }
    public function getSoldGoods(){
        if ($_SESSION["USER"]['group_id']!=1) die("нет прав");
        $this->setRequest(['params'=>[
            'filter'=>['status'=>2],
            'dop_eq'=>['with_basket'],
        ]]);
        $list=$this->getList();
        $goodsList=[];
        $count=0;
        foreach ($list as $user)
            foreach ($user["orders"] as $order)
                foreach ($order["basket"] as $basket){
                    if (!isset($goodsList[$basket['id_good']])) {
                        $goodsList[$basket['id_good']] = [
                            'id'=>$basket['id_good'],
                            'name'=>$basket['name'],
                            'count'=>$basket['count']
                        ];
                    }else {
                        $goodsList[$basket['id_good']]['count'] += $basket['count'];
                    }
                    $count+=$basket['count'];
                }
        return ['list'=>$goodsList,'all'=>$count];
    }

    public function getSoldGoodsAjax(){
        if ($_SESSION["USER"]['group_id']!=1) die("нет прав");
        $goodsList=$this->getSoldGoods()['list'];
        $names=[];$cols=[];
        foreach ($goodsList as $oneGood){
            $names[]=(string)$oneGood['name'];
            $cols[]=(int)$oneGood['count'];
        }
        return ['names'=>$names,'cols'=>$cols];
    }
}

