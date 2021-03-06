<?php

namespace Engine;


use Engine\Core\Router\DispathedRoute;
use Engine\Helper\Common;

class Cms
{
    /**
     * @var
     */
    private $di;
    public $router;
    /**
     * Cms constructor.
     * @param $di
     */
    public function __construct($di)
    {
        $this->di = $di;
        $this->router = $this->di->get('router');
    }
    public function run()
    {
        $this->router->add('HomePage', '/', 'PublicController:index1');
        $this->router->add('NumPage', '/(id:int)', 'PublicController:index2');
        $this->router->add('CheckAuth', '/check', 'PrivateController:check');
        $this->router->add('Auth', '/auth', 'PrivateController:auth');

        $routerDispath = $this->router->dispatch(Common::getMethod(), Common::getPatchUrl());
        if ($routerDispath == null) {
            $routerDispath = new DispathedRoute('CrmController:page404');
        }
        list($class, $action) = explode(':', $routerDispath->getController(), 2);
        $classcontroller = "\\Cms\\Controller\\" . $class;
        call_user_func_array([new $classcontroller($this->di), $action], $routerDispath->getParametrs());
    }
}