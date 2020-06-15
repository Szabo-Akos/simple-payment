<?php

class App {
	private $controller = 'payment';
	private $method = 'index';
	private $params = [];
	private $url;

	public function __construct() {
		$this->parseUrl();
		$this->setController();
		$this->setMethod();
		$this->setParams();
		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	private function parseUrl() {
		if (isset($_GET['url'])) {
			$this->url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}

	private function setController() {
		if (file_exists('../app/controllers/' . $this->url[0] . '.php' )) {
			$this->controller = $this->url[0];
			unset($this->url[0]);
		}
		require_once '../app/controllers/' . $this->controller . '.php';
		$this->controller = new $this->controller;
	}

	private function setMethod() {
		if (isset($this->url[1])) {
			if(method_exists($this->controller, $this->url[1])) {
				$this->method = $this->url[1];
				unset($this->url[1]);
			}
		}
	}

	private function setParams() {
		$this->params = $this->url ? array_values($this->url) : [];
	}
}
