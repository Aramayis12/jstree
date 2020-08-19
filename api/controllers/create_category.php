<?php
// get posted data
$name = (isset($_POST['name']))?$_POST['name']:'';
$parent_id = (isset($_POST['parent_id']) && $_POST['parent_id'] != '#')?$_POST['parent_id']:0;		

// make sure data is not empty
if(
	!(empty($name) || (empty($parent_id) && $parent_id != 0))
){
  
	// set category property values
	$class->name = $name;
	$class->parent_id = $parent_id;
  
	// create the category
	if($id = $class->create()){
  
		// set response code - 201 created
		http_response_code(201);
  
		// tell the user
		echo json_encode(array("message" => "Category was created.", "id" => $id));
	}
  
	// if unable to create the category, tell the user
	else{
  
		// set response code - 503 service unavailable
		http_response_code(503);
  
		// tell the user
		echo json_encode(array("message" => "Unable to create category."));
	}
}
  
// tell the user data is incomplete
else{
  
	// set response code - 400 bad request
	http_response_code(400);
  
	// tell the user
	echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
}	