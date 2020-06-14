<?php


class BasketController extends Controller
{
    public $view = 'basket';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Корзина';
    }

    public function addAJAX($data)
    {
        return (new Basket($data))->addGood();
    }

    public function index($data){
        $result=[];
        if (!empty($data['params'])){
            $reg=[];
            if (isset($data['params']['reg'])) {
                $data['params']['phone']=$data['params']['login'];
                $reg=(new Users($data))->registration();
            }
            if (empty($reg)) {
                $reg['registration'] = 1;
                $_SESSION['basket']=[];
            }
            $result=array_merge($result,$reg);
        }
        $goods=(new Basket($data))->getBasket();
        $result=array_merge($result,['goods'=>$goods]);
        return $result;
    }
}
