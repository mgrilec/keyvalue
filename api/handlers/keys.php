<?php

class Keys {

	public function Set($f3, $params) {

		$project_id = $f3->get('REQUEST.project_id');
		$keys = $f3->get('REQUEST.keys');
		$values = $f3->get('REQUEST.values');

		$count = min(count($keys), count($values));
		for ($index = 0; $index < $count; $index++) {
			$key = $f3->get('key');
			$key->reset();
			$key->project_id = $f3->get('REQUEST.project_id');
			$key->key = $keys[$index];
			$key->value = $values[$index];
			$key->save();
		}

		$data = array();
		$data['count'] = $count;

		echo json_encode($data);
	}

	public function Exists($f3, $params) {
		$count = $f3->get('key')->count(array('@project_id=? and @key=?', $params['project_id'], $params['key']));
		$data = array();
		$data['result'] = $count == 1;
		echo json_encode($data);
	}

	public function Count($f3, $params) {
		$count = $f3->get('key')->count(array('@project_id=?', $params['project_id']));
		$data = array();
		$data['count'] = $count;
		echo json_encode($data);
	}

	public function Get($f3, $params) {
		$key = $f3->get('key');
		$key->load(array('@project_id=? and @key=?', $params['project_id'], $params['key']));

		$data = array();
		$data['data'] = $key->value;

		echo json_encode($data);
	}

	public function GetAll($f3, $params) {
		$keys = $f3->get('key')->find(array('@project_id=?', $params['project_id']));

		$data = array();
		$data['data'] = array();
		foreach ($keys as $key) {

			// turn mapper to array
			$row = $key->cast();

			// delete _id
			unset($row['_id']);
			
			// add row to data
			$data['data'][] = $row;
		}

		echo json_encode($data);
	}

	public function Delete($f3, $params) {
		$keys = $f3->get('key')->find(array('@project_id=? and in_array(@key, ?)', $f3->get('REQUEST.project_id'), $f3->get('REQUEST.keys')));
		$count = count($keys);
		foreach ($keys as $key) {
			$key->erase();
		}

		$data = array();
		$data['count'] = $count;
		echo json_encode($data);
	}
}

?>