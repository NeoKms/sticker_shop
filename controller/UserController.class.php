<?php

class UserController extends Controller
{
    public $view = 'user';
    public $title;

    function auth ($data)
    {
        if ($data['id']==1) {
            App::resetSess();
            return ['exit'=>1];
        }
        return array_merge((new Users($data))->auth(),$data['params']);
    }
    function registration ($data)
    {
        $result=[];
        if (!empty($data['params'])) {
            $result = array_merge((new Users($data))->registration(), $data['params']);
        }
        return $result;
    }
    function cabinet($data){
        $result=[];
        $result['user']=$_SESSION['USER'];
        $result['orders']=(new Orders([
            'params'=>[
                'filter'=>['status'=>2,'id_user'=>$_SESSION['uid']],
                'dop_eq'=>['with_basket'],
            ],
        ]))->getList();
        return $result;
//        echo '<pre>';
//        var_dump($result);
//        echo '</pre>';
    }
}
