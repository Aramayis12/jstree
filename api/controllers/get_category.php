<?php 
// get posted data
$parent_id = (isset($_GET['parent_id']) && $_GET['parent_id'] != '#')?(int) $_GET['parent_id']:0;

$class->parent_id = $parent_id;	

$stmt = $class->get();
$num = $stmt->rowCount();

// set response code - 200 OK
http_response_code(200);

// check if more than 0 record found
if($num > 0){
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as &$item) {
			if(is_null($item['children'])){
				$item['children'] = false;
			} else {
				$item['children'] = true;
			}
		}

	// show categories data in json format
	echo json_encode($result);
} else {
	echo json_encode([]);
}