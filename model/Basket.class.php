<?php


class Basket extends Model
{
    protected $table = 'basket';

    protected function setProperties ()
    {

        $this->properties['id'] = ['type' => 'integer', 'name' => 'ИД'];

        $this->properties['id_good'] = ['type' => 'integer', 'name' => 'айди товара'];

        $this->properties['id_order'] = ['type' => 'integer', 'name' => 'айди заказа'];

        $this->properties['count'] = ['type' => 'integer', 'name' => 'количество товара'];

        $this->maps[$this->table] = ['id'=>'id','id_good' => 'id_good', 'id_order' => 'id_order', 'count' => 'count'];
    }


    public function getCountByIdOrder ()
    {
        $param = $this->request['params'];
        if (empty($param['id_order_basket']))
            return [];
        $sql = $this->db->selectValue([], ['id_order' => $param['id_order_basket']], $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }
    public function addGood(){
        $param = $this->request['params'];
        if ($param['id_good']<0) return [false];
        $sql = $this->db->selectValue(['id','count'], ['id_order' => $_SESSION['basket']['id'],'id_good'=>$param['id_good']], $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        if (!empty($res)){
            $count=(int)$res[0]['count'];
            $count++;
            $res = $this->db->upsert($this->table,$res[0]['id'],['count'=>$count++],$this->maps[$this->table]);
            if ($res[0]){
                return [true,'exist'];
            }
        }else{
            $res = $this->db->upsert($this->table,0,[
                'id_order' => $_SESSION['basket']['id'],
                'id_good'=>$param['id_good'],
                'count'=>1
            ],$this->maps[$this->table]);
            $_SESSION['basket']['count']++;
            if ($res[0]) return [true,'add'];
        }
        return [false];
    }

    private function getBasketByOrder($id){
        $sql = $this->db->selectValue([], ['id_order' => $id], $this->maps[$this->table]);
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        return $this->db->query($q);
    }

    public function getBasket(){
        $param = $this->request['params'];
        if (!empty($param['id_order'])){
            $basket_goods=$this->getBasketByOrder($param['id_order']);
        }else {
            if (!isset($_SESSION['basket']['id'])) return [];
            $basket_goods = $this->getBasketByOrder($_SESSION['basket']['id']);
        }
        $id_goods=[];
        $count_arr=[];
        foreach ($basket_goods as $oneGood){
            $id_goods[]=$oneGood['id_good'];
            $count_arr[$oneGood['id_good']]=$oneGood['count'];
        }
        $goods_arr=(new Goods(['params'=>['ids'=>$id_goods]]))->getList();
        if (empty($goods_arr)) return [];
        foreach ($goods_arr as $key=>$oneGood){
            $req['id']=$goods_arr[$key]['category_id'];
            $categ_info=(new Categories($req))->getById();
            if (!empty($categ_info)) {
                $goods_arr[$key]['category_name'] = $categ_info[0]['name'];
            }
            $goods_arr[$key]['count']=$count_arr[$oneGood['id_good']];
        }
        return $goods_arr;
    }
}

