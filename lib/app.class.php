<?php

class App
{
    protected static $router;

    public static $db;

    /**
     * @return mixed
     */
    public static function getRouter() {
        return self::$router;
    }

    public static function run($uri) {
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'), Config::get('db.login'), Config::get('db.password'), Config::get('db.db_name'));

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        $layout = self::$router->getRoute();
        if ( $layout == 'admin' && Session::get('role') != 'admin' ) {
            if ( $controller_method != 'admin_login' ) {
                Router::redirect('/admin/users/login/');
            }
        }

        $controller_object = new $controller_class;

        if ( method_exists($controller_object, $controller_method) ) {
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception("Method ${controller_method} of class ${controller_class} does not exist");
        }

//        $popup_object = new PopupController();
//        $popup_view_path = $popup_object->index();
//        $popup_view_object = new View(($popup_object->getData()), $popup_view_path);
//        $popup_content = $popup_view_object->render();
//        $popup_layout_path = VIEWS_PATH.DS.'popup'.DS.'index.html';
//        $popup_layout_view_object = new View(compact('popup_content'), $popup_layout_path);
//        echo $popup_layout_view_object->render();



        $layout = self::$router->getRoute();
        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();



    }
}