<?php

class Keys {

	public function Get($f3, $params) {
		$key = $f3->get('key');
		$key->load(array('@project_id=? and @key=?', $params['project_id'], $params['key']));
		echo $key->value;
	}

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

		echo $count;
	}
}

?>