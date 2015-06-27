<?php
class Projects {

	public function All($f3, $params) {
		$project = $f3->get('project');
		$projects = $project->find();
		$data = array();

		foreach($projects as $p) {

			// turn mapper to array
			$row = $p->cast();

			// unset _id
			unset($row['_id']);

			// set to data
			$data[] = $row;
		}

		echo json_encode($data);
	}

	public function Create($f3, $params) {
		$project = $f3->get('project');

		// create new project entry
		$project->reset();
		$project->title = $f3->get('REQUEST.project_title');
		$project->save();

		$project->id = optimus_encode($f3, $project->_id);
		$project->save();

		// return project id
		echo $project->id;
	}

	public function Delete($f3, $params) {
		$data = array();
		$project = $f3->get('project')->load(array('@id=?', $f3->get('REQUEST.project_id')));
		if ($project->dry()) {
			$data['result'] = false;
		}
		else {
			$project->erase();
			$data['result'] = true;
		}

		echo json_encode($data);
	}
} 
?>