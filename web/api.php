<?php

class Api {

	public function __construct($url) {
		$this->url = $url;
		$this->web = Web::instance();
	}

	private function get($route) {
		$url = $this->url . $route;
		$content = $this->web->request($url);
		return json_decode($content['body']);
	}

	public function projects() {
		return $this->get('projects');
	}
}

?>