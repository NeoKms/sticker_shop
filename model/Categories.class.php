<?php


class Categories extends Model
{
    protected $table = 'categories';

    protected function setProperties ()
    {

        $this->properties['id'] = ['type' => 'integer', 'name' => 'ИД'];

        $this->properties['name'] = ['type' => 'string', 'name' => 'название'];

        $this->properties['code'] = ['type' => 'string', 'name' => 'код'];


        $this->properties['parent_id'] = ['type' => 'integer', 'name' => 'категория родителя'];

        $this->properties['active'] = ['type' => 'boolean', 'name' => 'емаил'];

        $this->maps[$this->table] = ['id' => 'id', 'name' => 'name', 'parent_id' => 'parent_id', 'active' => 'active','code'=>'code'];
    }

    public function getCategories(){

        $sql = $this->db->selectValue(
            ['id','name'],['active'=>1],
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }
    public function getById(){
        $id=$this->request['id'];
        $filer=['active'=>1,'id'=>$id];
        $sql = $this->db->selectValue(
            ['id','name'],$filer,
            $this->maps[$this->table]
        );
        $q = "SELECT " . $sql['select'] . " FROM " . $this->table . $sql['where'];
        $res = $this->db->query($q);
        return $res;
    }
}

