<?php

class App
{
    public static $twig;
    public static function Init()
    {
        date_default_timezone_set('Europe/Moscow');
        DBase::getInstance()->Connect(Config::get('db_user'), Config::get('db_password'), Config::get('db_base'));
        Users::init();
        $loader = new Twig_Loader_Filesystem(Config::get('path_templates'));
        self::$twig = new Twig_Environment($loader);
        self::$twig->addGlobal('session', $_SESSION);
        //route
        if (php_sapi_name() !== 'cli' && isset($_SERVER) && isset($_GET)) {
            self::web($_GET['path'] ??  '');
        }
    }

    protected static function web($url)
    {
        $url = explode("/", $url);
        $_GET['id']=false;
        if (!empty($url[0])) {
            $_GET['page'] = $url[0];//Часть имени класса контроллера
            if (isset($url[1])) {
                if (is_numeric($url[1])) {
                    $_GET['id'] = $url[1];
                } else {
                    $_GET['action'] = $url[1];//часть имени метода
                }
                if (isset($url[2])) {//формальный параметр для метода контроллера
                    $_GET['id'] = $url[2];
                }
            }
        } else {
            $_GET['page'] = 'index';
        }
        if (isset($_GET['page'])) {
            $errors = [];
            $controllerName = ucfirst($_GET['page']) . 'Controller';
            $methodName = isset($_GET['action']) ? $_GET['action'] : 'index';
            //проверка на доступность контроллера и существования метода для вьюхи
            //если не существует, то берет индексовый с ошибкой 404
            if (!class_exists($controllerName) || !method_exists($controllerName, $methodName)) {
                $controllerName = 'IndexController';
                $methodName = 'Error404';
                $errors[] = '1';
            }
            $controller = new $controllerName();
            //преобразуем данные в запрос для метода. + прогоним аякс правильно
            if (isset($_POST['data'])) {
                $tmp=[];
                foreach ($_POST['data'] as $oneParam) {
                    $tmp[$oneParam['name']] = $oneParam['value'];
                }
                unset($_POST['data']);
                $_POST=array_merge($_POST,$tmp);
            }
            $req=['params' => $_POST, 'id' => $_GET['id']];
            //
            if (isset($_GET['asAjax'])) {
                die(json_encode($controller->$methodName($req)));
            }
            $data = [
                'content_data' => $controller->$methodName($req),
                'title' => $controller->title,
                'views_goods'=>(new Pages([]))->getGoods(),
            ];
            //ставим категории в меню
            $categories=(new Categories($req))->getCategories();
            if ($categories!==false) $data['categories']=$categories;
            //
            $view = $controller->view . '/' . $methodName . '.html';
            self::setPageSession();
            //проверка на существования вьюхи
            if (!file_exists('../../templates/' . $view) || !is_file('../../templates/' . $view)) {
                $view = 'index/Error404.html';
                $errors[] = '2';
            }
            //если были ошибки - вывести
            //коды ошибок 1 - нет метода или класса. 2 - нет вьюхи.
            if (!empty($errors))
                $controller->ConsoleAlert($errors);
            self::$twig->addGlobal('cur_page', 'p-'.strtolower($controllerName.'-'.$methodName));
            $template = self::$twig->loadTemplate($view);
            echo $template->render($data);
        }
    }

    protected static function setPageSession(){
//        $_SESSION['pages']=[];
        if (isset($_GET['page'])) {
            if (!isset($_SESSION['pages']))
                $_SESSION['pages'] = [];
            if (!isset($_SESSION['pages'][$_GET['page']]))
                $_SESSION['pages'][$_GET['page']] = [];
            if (isset($_GET['action'])) {
                if (!isset($_SESSION['pages'][$_GET['page']][$_GET['action']]))
                    $_SESSION['pages'][$_GET['page']][$_GET['action']] = [];
                if (isset($_GET['id']))
                    if (!in_array($_GET['id'],$_SESSION['pages'][$_GET['page']][$_GET['action']])) {
                        (new Pages(['id'=>$_GET['id']]))->setPage();
                        $_SESSION['pages'][$_GET['page']][$_GET['action']][] = $_GET['id'];
                    }
            }
        }
        self::$twig->addGlobal('session', $_SESSION);
    }
    protected static function Auth(){
        if (isset($_SESSION['uid']) && $_SESSION['uid']>0){
            return true;
        }
        return false;
    }

    public static function resetSess(){
        $_SESSION['pages']=[];
        $_SESSION['basket']=[];
        $_SESSION['USER']=[];
        $_SESSION['uid']=-1;
        Orders::initBasket();
    }
}
