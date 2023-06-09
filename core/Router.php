<?php

namespace core;

class Router
{
	private $route;
	private $patterns = [];
	private $url;

	public function __construct()
	{
		$this->url = trim($_SERVER['REQUEST_URI'],'?');
		$this->skipAssets($this->url);
		$this->patterns = require __DIR__ . '/routes.php';
		$this->route = new Route();
		$this->parse();
	}

	protected function skipAssets($url){
		if (preg_match('[\.css|\.js|\.ico]',$url)){
			exit();
		}
	}

	public function getRoute()
	{
		$this->route->controller = ucfirst($this->route->controllerName).'Controller';
		return $this->route;
	}

	public function parse()
	{
		foreach ($this->patterns as $pattern) {
			if (preg_match("#$pattern#i", $this->url, $matches)) {

				$this->route->pattern = $pattern;

				foreach ($matches as $k => $v) {
					if (!is_numeric($k)) {
						$this->route->$k = $v;
					}
				}
			}
		}
	}
}