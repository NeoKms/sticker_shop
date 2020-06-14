<?php


class AdminController extends Controller
{
    public $view = 'admin';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Админка';
    }

    function orders($data){
        return ['orders'=>(new Orders([
            'params'=>[
                'filter'=>['status'=>2],
                'dop_eq'=>['with_basket','with_users'],
            ],
        ]))->getList()];
    }

    function index ($data)
    {
        $result=[];
        $orders=(new Orders([
            'params'=>[
                'filter'=>['status'=>2],
                'dop_eq'=>['only_count'],
            ],
        ]));
        $users=(new Users([]));
        $result['orders']=$orders->getList();
        $result['users']=$users->getCount();
        $result['goods_sold']=$orders->getSoldGoods();
//        echo '<pre>';
//        var_dump($result);
//        echo '</pre>';
        return $result;
    }

    function goodsListAjax($data){
        return (new Orders([
            'params'=>[
                'filter'=>['status'=>2],
                'dop_eq'=>['only_count'],
            ],
        ]))->getSoldGoodsAjax();
    }
}
