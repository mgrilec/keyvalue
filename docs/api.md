# Api

**Create a project** 
`POST @project_create: /projects/create`: 

Parameters:

- `project_title`: Should be longer than 1
- `project_description`: No validation

Returns:

- `error`: Invalid project title
- `id`: Newly created project ID

----

**Check if a project exists**
`GET @project_exists: /projects/@project_id/exists`

Parameters:

- project_id: Should be a valid project id

Returns:

- error: Invalid project ID
- result: true or false

 ----

**Get project data**
`GET @project_get: /projects/@project_id`

Parameters:

- project_id

Returns:

- error: Invalid project ID | No such project
- result: _Project_

----

**Get all project data**
`GET @projects: /projects`

Parameters: None

Returns:

- error: 

----

**Update a project**
`POST @project_update: /projects/@project_id/update`

Parameters: 

- 

Returns:

- error:

 ----

** Delete a project **

`POST @project_delete: /projects/delete`

Parameters:
- 

Returns:
- error: