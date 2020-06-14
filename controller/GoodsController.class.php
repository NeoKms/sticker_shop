<?php


class GoodsController extends Controller
{
    public $view = 'goods';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Товар';
    }

    public function detail($data)
    {
        $result=[];
        $good=(new Goods($data))->getById();
        if (!empty($good)) {
            $good=$good[0];
            $data['id']=$good['category_id'];
            $categ_info=(new Categories($data))->getById();
            if (!empty($categ_info)) {
                $good['category_name'] = $categ_info[0]['name'];
            }
            $result['good'] = $good;
        }
        return $result;
    }

}
