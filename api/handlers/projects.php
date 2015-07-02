<?php
class Projects {

	public function ValidateProjectTitle($title) {
		return array('result' => strlen(trim($title)) > 0, 'error' => "Invalid project title");
	}

	public function ValidateProjectId($id) {
		return array('result' => ctype_digit($id), 'error' => "Invalid project ID");
	}

	public function Create($f3, $params) {
		$data = array();

		// validate title
		$validation = $this->ValidateProjectTitle($f3->get('REQUEST.project_title'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		$projectMapper = $f3->get('project');

		// create new project entry
		$projectMapper->reset();
		$projectMapper->title = $f3->get('REQUEST.project_title');
		$projectMapper->description = $f3->get('REQUEST.project_description');
		$projectMapper->id = optimus_encode($f3, $projectMapper->_id);
		$projectMapper->save();

		// get data
		$data['id'] = $projectMapper->id;

		// output
		echo json_encode($data);
	}

	public function Exists($f3, $params) {
		$data = array();

		// validate project id
		$validation = $this->ValidateProjectId($params['project_id']);
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		$count = $f3->get('project')->count(array('@id=?', $params['project_id']));
		$data['result'] = $count == 1;
		echo json_encode($data);
	}

	public function Get($f3, $params) {
		$data = array();

		// validate project id
		$validation = $this->ValidateProjectId($params['project_id']);
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		// find the project
		$projectMapper = $f3->get('project');
		$projectMapper->load(array('@id=?', $params['project_id']));

		// check if project exists
		if ($projectMapper->dry()) {
			$data['error'] = "No such project";
			echo json_encode($data);
			return;
		}

		// convert to data
		$data['result'] = $projectMapper->cast();
		unset($data['result']['_id']);

		// output
		echo json_encode($data);
	}

	public function GetAll($f3, $params) {
		$project = $f3->get('project');
		$projects = $project->find();
		$data = array();
		$data['data'] = array();

		foreach($projects as $p) {

			// turn mapper to array
			$row = $p->cast();

			// delete _id
			unset($row['_id']);

			// add row to data
			$data['data'][] = $row;
		}

		echo json_encode($data);
	}

	public function Update($f3, $params) {

		// find project
		$project = $f3->get('project');
		$project->load(array('@id=?', $f3->get('REQUEST.project.id')));

		// copy fields from request
		$project->copyfrom('REQUEST.project');

		// save the modified project
		$project->save();

		// build data
		$data = array();
		$data['result'] = true;
		echo json_encode($data);
	}

	public function Delete($f3, $params) {
		$data = array();
		$project = $f3->get('project');
		$project->load(array('@id=?', $f3->get('REQUEST.project_id')));

		if ($project->dry()) {
			$data['result'] = false;
		}
		else {
			$project->erase();
			$f3->get('key')->erase(array('@project_id=?', $f3->get('REQUEST.project_id')));
			$data['result'] = true;
		}

		echo json_encode($data);
	}
} 
?>