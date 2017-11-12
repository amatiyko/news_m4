<?php

class View {
    protected $data;

    protected $path;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }



    protected static function getDefaultViewPath() {
        $router = App::getRouter();
        if ( !$router ) {
            return false;
        }

        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';

        return VIEWS_PATH.DS.$controller_dir.DS.$template_name;
    }

    public function __construct($data = array(), $path = NULL) {
        if ( !$path ) {
            $path = self::getDefaultViewPath();
        }
        if ( !file_exists($path) ) {
            throw new Exception("No template in ${path}");
        }

        $this->path = $path;
        $this->data = $data;
    }

    public function render() {
        $data = $this->data;

        $data['promo'] = APP::$db->query('select * from promo');
        $data['style'] = APP::$db->query('select * from styles where in_use = 1');

        ob_start();
        include($this->path);
        $content = ob_get_clean();

        return $content;
    }
}