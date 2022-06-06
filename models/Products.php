<?php
class Products
{
  //DBstaffs
  private $conn;
  private $table = "products";

  //Post Properties
  public $id;
  public $sku;
  public $name;
  public $price;
  public $type;
  public $size;



  //Constructor with DB
  public function __construct($db)
  {
    $this->conn = $db;
  }



  //Get Posts

  public function read()
  {
    // Create query
    $query = 'SELECT id,sku,name,price,type,size FROM '
      . $this->table .
      ' ORDER BY sku DESC';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  //Get single post
  public function read_single()
  {

    $query = 'SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.created_at
        FROM ' . $this->table . ' p
        LEFT JOIN
          categories c ON p.category_id = c.id
       WHERE p.id =?
       LIMIT 0,1
          ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    //bIND_id

    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($row['title']) || null) {
      return $this->id_not_found = "Id is not found";
    } else {
      $this->title = $row['title'];
      $this->body = $row['body'];
      $this->author = $row['author'];
      $this->category_id = $row['category_id'];
      $this->category_name = $row['category_name'];
    }
  }


  //Create Product

  public function create()
  {
    // Create query
    $query = 'INSERT INTO ' . $this->table . ' SET sku = :sku, name = :name, price = :price, type = :type, size=:size ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->sku = htmlspecialchars(strip_tags($this->sku));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->type = htmlspecialchars(strip_tags($this->type));
    $this->size = htmlspecialchars(strip_tags($this->size));
    // Bind data
    $stmt->bindParam(':sku', $this->sku);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':size', $this->size);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function delete()
  {

    $checkbox = $_POST['check'];
    for ($i = 0; $i < count($checkbox); $i++) {
      $del_id = $checkbox[$i];

      $query = "DELETE FROM'.$this->table.' WHERE sku='" . $this->sku . "'";

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(':sku', $this->sku);
    }
    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}