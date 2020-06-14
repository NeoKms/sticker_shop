<?php


class Goods extends Model {
    protected $table = 'goods';

    protected function setProperties()
    {

        $this->properties['id'] = [
            'type' => 'integer',
            'name'=>'ИД'
        ];

        $this->properties['name'] = [
            'type' => 'string',
            'name'=>'название'
        ];

        $this->properties['price'] = [
            'type' => 'integer',
            'name'=>'цена'
        ];

        $this->properties['active'] = [
            'type' => 'boolean',
            'name'=>'активность'
        ];

        $this->properties['special'] = [
            'type' => 'boolean',
            'name'=>'спец. предложение'
        ];

        $this->properties['category_id'] = [
            'type' => 'integer',
            'name'=>'категория'
        ];

        $this->properties['picture'] = [
            'type' => 'string',
            'name'=>'превью'
        ];

        $this->properties['description'] = [
            'type' => 'string',
            'name'=>'описание'
        ];

        $this->maps[$this->table]=[
            'id_good'=>'id',
            'name'=>'name',
            'price'=>'price',
            'active'=>'active',
            'special'=>'special',
            'category_id'=>'category_id',
            'picture'=>'picture',
            'description'=>'description'
        ];
    }

    public function getSpecial(){
        $param=$this->request['params'];
        $sql = $this->db->selectValue(
            [],
            ['special' => 1,'active'=>1],
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        if (!empty($param['col'])) $q.=" LIMIT {$param['col']}";
        $res = $this->db->query($q);
        return $res;
    }

    public function getGoodsOnCategories(){
        $param=$this->request['params'];
        $id=$this->request['id'];
        if ($id<0) return [];
        $sql = $this->db->selectValue(
            [],
            ['active'=>1,'category_id'=>$id],
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }

    public function getById(){
        $id=$this->request['id'];
        if ($id<0) return [];
        $sql = $this->db->selectValue(
            [],
            ['active'=>1,'id_good'=>$id],
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }

    public function getList(){
        $param=$this->request['params'];
        $sql = $this->db->selectValue(
            [],
            ['active'=>1,'&id_good'=>$param['ids']],
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        return $this->db->query($q);
    }

}

