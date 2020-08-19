<?php
class Category{
  
    // database connection and table name
    private $conn;
    private $table_name = "categories";
  
    // object properties
    public $id;
    public $name;
    public $parent_id;
    public $lang_code;
    public $position;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	public function get(){
		$query = "SELECT
                c1.id, 
                c1.name AS `text`, 
                c1.parent_id,
                case when c1.parent_id = '0' then 'root' else '' end as type,
                c2.children
            FROM
                " . $this->table_name . " c1 
            LEFT JOIN 
            	(SELECT id, parent_id, COUNT(*) as children FROM " . $this->table_name . "  GROUP BY parent_id ) c2
            ON c2.parent_id = c1.id
            WHERE c1.parent_id=:parent_id
            ORDER BY
                c1.created_at ";
  
    	$stmt = $this->conn->prepare( $query );

    	// bind values
		$stmt->bindParam(":parent_id", $this->parent_id);

   		$stmt->execute();
  
    	return $stmt;
	}

	// create category
	public function create(){
    	// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					name=:name, 
					lang_code=:lang_code,
					parent_id=:parent_id,
					position=:position
				";
	  
		// prepare query
		$stmt = $this->conn->prepare($query);
	  
		// sanitize
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->lang_code = strtolower(implode('_', explode(' ', $this->name)));
		$this->parent_id = htmlspecialchars(strip_tags($this->parent_id));
		$this->position = htmlspecialchars(strip_tags($this->position));


		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":lang_code", $this->lang_code);
		$stmt->bindParam(":parent_id", $this->parent_id);
		$stmt->bindParam(":position", $this->position);
	  
		// execute query
		if($stmt->execute()){
			return $this->conn->lastInsertId();
		}
	  
		return false;
	}
	
	// update the category 
	function update(){
	  
		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					name = :name,
					lang_code = :lang_code,
					parent_id = :parent_id
				WHERE
					id = :id";
	  
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	  
		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->lang_code = strtolower(implode('_', explode(' ', $this->name)));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
	  
		// bind new values
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':lang_code', $this->lang_code);
		$stmt->bindParam(':parent_id', $this->parent_id);
		$stmt->bindParam(':id', $this->id);
	  
		// execute the query
		if($stmt->execute()){
			return true;
		}
	  
		return false;
	}

}
?>
