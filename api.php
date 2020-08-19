<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  

// Get database connection
require_once('./configs/database.php');

$database = new Database();
$db = $database->getConnection();

$request_parts = explode('/', $_SERVER['REQUEST_URI']); // array('users', 'show', 'abc')

$path = $request_parts[2]; // The catalog name
$action =  explode('?', $request_parts[3])[0]; // e.g. add, edit, delete

$id = (isset($request_parts[4])) ? (int) $request_parts[4] : 0;

//Output based on request
switch($path) {
    case 'category':
		require_once('./api/category.php');
		$class = new Category($db);
		// echo json_encode($output);
        break;
    default:
        // set response code - 400 bad request
		http_response_code(400);
	  
		// tell the user
		echo json_encode(array("message" => "Unable to proccess. Data is incomplete."));
}

switch($action) {
    case 'create':
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['response' => 'Method not allowed']);die;	
		}

		require_once('./api/controllers/create_category.php');
        break;
    case 'update':
		if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
			http_response_code(405);
			echo json_encode(['response' => 'Method not allowed']);die;	
		}

		require_once('./api/controllers/update_category.php');
        break;
    case 'get':
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405);
			echo json_encode(['response' => 'Method not allowed']);die;	
		}

		require_once('./api/controllers/get_category.php');
		break;
	default:
        // set response code - 400 bad request
		http_response_code(400);
	  
		// tell the user
		echo json_encode(array("message" => "Unable to proccess. Data is incomplete."));
}     			
