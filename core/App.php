<?php

namespace core;

class App
{
	public function run()
	{
		$auth = new Auth();

		$router = new Router();
		$route = $router->getRoute();


		$controller = '\\controller\\' . $route->controller;
		if (!class_exists($controller))
			header('Location:/task/index');
		$controller = new $controller($route);

		$action = $route->action;
		if (!method_exists($controller, $action)) {
			throw new \Exception('нет метода');
		}
		$controller->$action();
	}
}