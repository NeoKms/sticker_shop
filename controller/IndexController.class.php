<?php

class IndexController extends Controller
{
    public $view = 'index';
    public $title;

    function __construct ()
    {
        parent::__construct();
        $this->title .= ' | Главная';
    }

    //метод, который отправляет в представление информацию в виде переменной content_data
    function index ($data)
    {
        $data['params']['col']=5;
        $result=['main_photo' => 'img/love_keany.png','main_text'=>"Киану тебя любит!\nНе проходи мимо!\nПокупай лучшие стикеры с Киану в нашем магазине по доступным ценам.","text_2"=>"Данный сайт не является настоящим магазином и не продает товары. Данный сайт является лишь учебным проектом."];
        $carousel=(new Goods($data))->getSpecial();
        if (!empty($carousel)) {
            $result['carousel'] = $carousel;
        }

        return $result;
    }

    function Error404 ()
    {
        return [];
    }

}

