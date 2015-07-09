<?php

class Keys {

	public static function ValidateKey($key) {
		// match all of type a_bc.ab_c.abc
		// don't match .asdf or asd..as
		return array('result' => !empty($key) && preg_match("/^(\w|\w\.\w)+$/", $key), 'error' => "Invalid key");
	}

	public static function ValidateValue($value) {
		return array('result' => !empty($value), 'error' => "Invalid value");
	}

	public static function Set($f3, $params) {
		$data = array();

		$keys = $f3->get('REQUEST.keys');
		$values = $f3->get('REQUEST.values');

		// validate project id
		$validation = Projects::ValidateProjectId($f3->get('REQUEST.project.id'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		// get project
		$projectMapper = $f3->get('project')->load(array('@id=?', $f3->get('REQUEST.project.id')));

		// check if project exists
		if ($projectMapper->dry()) {
			$data['error'] = "No such project";
			echo json_encode($data);
			return;
		}

		$count = 0;
		$total = min(count($keys), count($values));
		if ($total > 0) {
			for ($index = 0; $index < $total; $index++) {

				// validate key
				$validation = Keys::ValidateKey($keys[$index]);
				if (!$validation['result'])
					continue;

				// validate key
				$validation = Keys::ValidateValue($values[$index]);
				if (!$validation['result'])
					continue;

				$key = $f3->get('key');
				$key->reset();
				$key->project_id = $f3->get('REQUEST.project.id');
				$key->key = $keys[$index];
				$key->value = $values[$index];
				$key->save();
				$count++;
			}
		}

		$data = array();
		$data['result'] = $count;

		echo json_encode($data);
	}

	public static function Exists($f3, $params) {
		$count = $f3->get('key')->count(array('@project_id=? and @key=?', $params['project_id'], $params['key']));
		$data = array();
		$data['result'] = $count == 1;
		echo json_encode($data);
	}

	public static function Count($f3, $params) {
		$count = $f3->get('key')->count(array('@project_id=?', $params['project_id']));
		$data = array();
		$data['result'] = $count;
		echo json_encode($data);
	}

	public static function Get($f3, $params) {
		$key = $f3->get('key');
		$key->load(array('@project_id=? and @key=?', $params['project_id'], $params['key']));

		$data = array();
		$data['result'] = $key->value;

		echo json_encode($data);
	}

	public static function GetAll($f3, $params) {
		$keys = $f3->get('key')->find(array('@project_id=?', $params['project_id']), array('order' => 'key'));

		$data = array();
		$data['result'] = array();
		foreach ($keys as $key) {

			// turn mapper to array
			$row = $key->cast();
			
			// add row to data
			$data['result'][$row['key']] = $row['value'];
		}

		echo json_encode($data);
	}

	public static function Delete($f3, $params) {
		$keys = $f3->get('key')->find(array('@project_id=? and in_array(@key, ?)', $f3->get('REQUEST.project.id'), $f3->get('REQUEST.keys')));
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