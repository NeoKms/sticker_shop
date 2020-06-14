<?php


class OrdersController extends Controller
{
    public $view = 'order';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Заказы';
    }

    function applyAJAX($data){
        if (!empty($data['params'])){
            $reg=[];
            if (isset($data['params']['reg'])) {
                $reg=(new Users($data))->registration();
                if (!empty($reg) && !isset($reg['error'])) {
                    $reg['registration'] = 1;
                }else {
                    return [false,$reg];
                }
            }
            $order=(new Orders([]))->closeOrder();
            if ($order!==false && $order[0]) {
                $_SESSION['basket'] = [];
                Orders::initBasket();
                return [true, $reg];
            }
        }
        return [false];
    }

}
