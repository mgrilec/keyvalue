<?php
class Projects {

	public function ValidateProjectTitle($title) {
		return array('result' => strlen(trim($title)) > 0, 'error' => "Invalid project title");
	}

	public function ValidateProjectId($id) {
		return array('result' => ctype_digit($id) || is_int($id), 'error' => "Invalid project ID");
	}

	public function Create($f3, $params) {
		$data = array();

		// validate title
		$validation = $this->ValidateProjectTitle($f3->get('REQUEST.project.title'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		$projectMapper = $f3->get('project');

		// create new project entry
		$projectMapper->title = $f3->get('REQUEST.project.title');
		$projectMapper->description = $f3->get('REQUEST.project.description');
		$projectMapper->id = optimus_encode($f3, $projectMapper->_id);
		$projectMapper->save();

		// get data
		$data['result'] = $projectMapper->id;

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
		$data['result'] = array();

		foreach($projects as $p) {

			// turn mapper to array
			$row = $p->cast();

			// delete _id
			unset($row['_id']);

			// add row to data
			$data['result'][] = $row;
		}

		echo json_encode($data);
	}

	public function Update($f3, $params) {
		$data = array();

		// validate project id
		$validation = $this->ValidateProjectId($f3->get('REQUEST.project.id'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		// validate project title
		$validation = $this->ValidateProjectTitle($f3->get('REQUEST.project.title'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		// find project
		$projectMapper = $f3->get('project');
		$projectMapper->load(array('@id=?', $f3->get('REQUEST.project.id')));

		// check if project exists
		if ($projectMapper->dry()) {
			$data['error'] = "No such project";
			echo json_encode($data);
			return;
		}

		// copy fields from request
		$projectMapper->title = $f3->get('REQUEST.project.title');
		$projectMapper->description = $f3->get('REQUEST.project.description');

		// save the modified project
		$projectMapper->save();

		// build data
		$data['result'] = true;
		echo json_encode($data);
	}

	public function Delete($f3, $params) {
		$data = array();

		// validate project id
		$validation = $this->ValidateProjectId($f3->get('REQUEST.project.id'));
		if (!$validation['result']) {
			$data['error'] = $validation['error'];
			echo json_encode($data);
			return;
		}

		$projectMapper = $f3->get('project');
		$projectMapper->load(array('@id=?', $f3->get('REQUEST.project.id')));

		// check if project exists
		if ($projectMapper->dry()) {
			$data['error'] = "No such project";
			echo json_encode($data);
			return;
		}

		
		$projectMapper->erase();
		$f3->get('key')->erase(array('@project_id=?', $f3->get('REQUEST.project.id')));
		$data['result'] = true;

		echo json_encode($data);
	}
} 
?>