<?php


class Pages extends Model {
    protected $table = 'pages';

    protected function setProperties()
    {

        $this->properties['id'] = [
            'type' => 'integer',
            'name'=>'ИД'
        ];

        $this->properties['user_id'] = [
            'type' => 'integer',
            'name'=>'ид юзера'
        ];

        $this->properties['good_id'] = [
            'type' => 'integer',
            'name'=>'код товара'
        ];

        $this->properties['basket_code'] = [
            'type' => 'integer',
            'name'=>'код карзины'
        ];

        $this->properties['datetime'] = [
            'type' => 'date',
            'name'=>'время'
        ];

        $this->maps[$this->table]=[
            'id'=>'id',
            'user_id'=>'user_id',
            'good_id'=>'good_id',
            'basket_code'=>'basket_code',
            'datetime'=>'datetime'
        ];
    }

    public function getGoods(){
        if (isset($_SESSION['pages']['goods']['detail'])) {
            $ids=$this->getIds();
            $goods=(new Goods(['params'=>['ids'=>$ids]]))->getList();
            return $goods;
        }
        return [];
    }

    public function setPage(){
        $id=$this->request['id'];
        if ($id>0){
            $props=[
                'datetime'=>date('Y-m-d H:i:s'),
                'basket_code'=>$_SESSION['basket']['code'],
                'good_id'=>$id,
            ];
            if ($_SESSION['uid']>0)
                $props['user_id']=$_SESSION['uid'];
            $this->db->upsert($this->table,0,$props,$this->maps[$this->table]);
        }
    }
    public function getIds(){
        $filter=[];
        if (!isset($_SESSION['basket']['code'])) return [];
        if ($_SESSION['uid']>0)
            $filter['user_id']=$_SESSION['uid'];
        else
            $filter['basket_code']=$_SESSION['basket']['code'];

        $sql = $this->db->selectValue(
            ['good_id'], $filter,
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " .  $this->table . $sql['where']. " ORDER BY datetime DESC LIMIT 5";
        $ids=[];
        $res=$this->db->query($q);
        foreach ($res as $oneElem){
            $ids[]=(int)$oneElem['good_id'];
        }
        return $ids;
    }
}

