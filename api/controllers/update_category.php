<?php

$putfp = fopen('php://input', 'r');
$putdata = '';
while($data = fread($putfp, 1024))
	$putdata .= $data;
fclose($putfp);

$put = json_decode($putdata, true);

// get posted data
$name = (isset($put['name']))?$put['name']:'';
$parent_id = (isset($put['parent_id']))?$put['parent_id']:'';	

// make sure data is not empty
if(
	!(empty($name) || empty($parent_id) || $id == 0)
){
	// set ID property of category to be edited
	$class->id = $id;
	  
	// set category property values
	$class->name = $name;
	$class->parent_id = $parent_id;
	  
	// update the ctegory
	if($class->update()){
	  
		// set response code - 200 ok
		http_response_code(200);
	  
		// tell the user
		echo json_encode(array("message" => "Category was updated."));
	}
	  
	// if unable to update the category, tell the user
	else{
	  
		// set response code - 503 service unavailable
		http_response_code(503);
	  
		// tell the user
		echo json_encode(array("message" => "Unable to update category."));
	}
}

// tell the user data is incomplete
else{
  
	// set response code - 400 bad request
	http_response_code(400);
  
	// tell the user
	echo json_encode(array("message" => "Unable to update category. Data is incomplete."));
}	