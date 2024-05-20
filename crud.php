<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="crud.css">
    <title>CRUD</title>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Fullname</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody id="data">
      <?php 
      function validate($data) {
          return htmlspecialchars(strip_tags(trim($data)));
      }

      $servername = 'localhost';
      $dbname = 'insertuser';
      $dbusername = 'root';
      $dbpassword = '';
      $tablename = "users";

      function search($servername, $dbname, $dbusername, $dbpassword, $tablename) {
          try {
              $connection = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
              $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $input_data = isset($_POST['search']) ? $_POST['search'] : '';
              $word = validate($input_data);

              $stmt = $connection->prepare("SELECT * FROM $tablename WHERE fullname LIKE :word");
              $stmt->execute([':word' => "%$word%"]);

              $results = $stmt->fetchAll();

              if (count($results) == 0) {
                  echo "<tr><td colspan='4'>No results</td></tr>";
              } else {
                  foreach ($results as $result) {
                    echo "<tr>
                    <td>{$result['id']}</td>
                    <td>{$result['fullname']}</td>
                    <td>  
                    <form class='FormButton' method='post' action='edite.php'>
                    <input type='hidden' name='id' value='{$result['id']}'> 
                    <button type='submit' name='edit' class='edit'>Edit</button>
                </form>
                
                  
                    </td>
                    <td>
                        <form class='FormButton' method='post' action='crud.php'>
                            <button type='submit' name='delete' class='delete'>Delete</button>
                            <input type='hidden' name='id' value='{$result['id']}'>
                        </form>
                    </td>
                  </tr>";
            
                  }
              }
          } catch (PDOException $e) {
              echo $e->getMessage();
          }
      }

      function deleteRecord($servername, $dbname, $dbusername, $dbpassword, $tablename) {
          try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              
              $sql = "DELETE FROM $tablename WHERE id = :id";
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(':id', $_POST["id"]);
              $stmt->execute();
              
              echo "Record deleted successfully";
          } catch(PDOException $e) {
              echo $sql . "<br>" . $e->getMessage();
          }
          $conn = null;
      }

      if(isset($_POST['delete'])){
        deleteRecord($servername, $dbname, $dbusername, $dbpassword, $tablename);
      }

      search($servername, $dbname, $dbusername, $dbpassword, $tablename);

      ?>
    </tbody>
  </table>
</body>
</html>
