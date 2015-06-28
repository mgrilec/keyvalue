<?php

class Api {

	public function __construct($url) {
		$this->url = $url;
		$this->web = Web::instance();
	}

	private function get($route) {
		$url = $this->url . $route;
		$content = $this->web->request($url);
		return json_decode($content['body'], true);
	}

	private function post($route, $params) {
		$url = $this->url . $route;
		$options = array(
			'method' => 'POST',
			'content' => http_build_query($params)
		);

		$content = $this->web->request($url, $options);
		return json_decode($content['body'], true);
	}

	public function projects() {
		return $this->get('projects');
	}

	public function project_create($title, $description) {
		return $this->post('projects/create', array("project_title" => $title, "project_description" => $description));
	}

	public function project_delete($id) {
		return $this->post('projects/delete', array("project_id" => $id));
	}
}

?>