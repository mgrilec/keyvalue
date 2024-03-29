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
		return $this->get('projects')['result'];
	}

	public function project_get($project_id) {
		return $this->get('projects/'.$project_id)['result'];
	}

	public function project_create($project_title, $project_description) {
		return $this->post('projects/create', array("project" => array("title" => $project_title, "description" => $project_description)))['result'];
	}

	public function project_update($project) {
		return $this->post('projects/'.$project['id'].'/update', array("project" => $project))['result'];
	}

	public function project_delete($project_id) {
		return $this->post('projects/delete', array("project" => array("id" => $project_id)))['result'];
	}

	public function keys_set($project_id, $keys, $values) {
		return $this->post('keys/set', array("project" => array('id' => $project_id), "keys" => $keys, "values" => $values))['result'];
	}

	public function keys_delete($project_id, $keys) {
		return $this->post('keys/delete', array("project" => array('id' => $project_id), "keys" => $keys))['count'];
	}

	public function keys_count($project_id) {
		return $this->get('keys/'.$project_id.'/count')['result'];
	}

	public function keys_get_all($project_id) {
		return $this->get('keys/'.$project_id)['result'];
	}
}

?>
