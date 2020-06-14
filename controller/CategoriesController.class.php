<?php


class CategoriesController extends Controller
{
    public $view = 'catalog';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Каталог товаров';
    }

    function index ($data)
    {
        $result=[];
        $categ_info=(new Categories($data))->getById();
        if ($categ_info==false) return [];
        $categ_info=$categ_info[0];
        $result['titel']=$categ_info['name'];
        $goods=(new Goods($data))->getGoodsOnCategories();
        if (!empty($goods)) {
            $result['goods'] = $goods;
        }
        return $result;
    }

}
