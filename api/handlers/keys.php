<?php

class Keys {

	public static function ValidateKey($key) {
		return !empty($key);
	}

	public static function ValidateValue($value) {
		return !empty($value);
	}

	public static function Set($f3, $params) {
		$data = array();

		$keys = $f3->get('REQUEST.keys');
		$values = $f3->get('REQUEST.values');

		// validate project id
		$validation = Projects::ValidateProjectId($f3->get('REQUEST.project_id'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		// get project
		$projectMapper = $f3->get('project')->load(array('@id=?', $f3->get('REQUEST.project_id')));

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
				if (!Keys::ValidateKey($keys[$index]))
					continue;

				// validate value
				if (!Keys::ValidateValue($values[$index]))
					continue;

				$key = $f3->get('key');
				$key->reset();
				$key->project_id = $f3->get('REQUEST.project_id');
				$key->key = $keys[$index];
				$key->value = $values[$index];
				$key->save();
				$count++;
			}
		}

		$data = array();
		$data['count'] = $count;

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
		$data['count'] = $count;
		echo json_encode($data);
	}

	public static function Get($f3, $params) {
		$key = $f3->get('key');
		$key->load(array('@project_id=? and @key=?', $params['project_id'], $params['key']));

		$data = array();
		$data['data'] = $key->value;

		echo json_encode($data);
	}

	public static function GetAll($f3, $params) {
		$keys = $f3->get('key')->find(array('@project_id=?', $params['project_id']));

		$data = array();
		$data['data'] = array();
		foreach ($keys as $key) {

			// turn mapper to array
			$row = $key->cast();
			
			// add row to data
			$data['data'][$row['key']] = $row['value'];
		}

		echo json_encode($data);
	}

	public static function Delete($f3, $params) {
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