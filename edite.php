<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="crud.css">
    <style>
        body{
            height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body id="editBody">
    <?php
        $servername = 'localhost';
        $dbname = 'insertuser';
        $dbusername = 'root';
        $dbpassword = '';
        $tablename = "users";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['id'])) {
                $id = $_POST['id'];
                $stmt = $pdo->prepare("SELECT * FROM $tablename WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "ID not provided.";
                exit;
            }
        }
        if(isset($_POST['submit'])){

            try {
                $stmt = $pdo->prepare("UPDATE $tablename SET fullname = :fullname WHERE id = :id");
                $stmt->bindParam(':id', $_POST['id']);
                $stmt->bindParam(':fullname', $_POST['fullname']);
                $stmt->execute();
                echo  "Record updated successfully" . "<br>" . "<br>";
                $stmt = $pdo->prepare("SELECT * FROM $tablename WHERE id = :id");
                $stmt->bindParam(':id', $_POST['id']);
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                header("Location: crud.php");
                exit(); 
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            }
    ?>
    <form method="post" action="edite.php" id="SecondForm">
        <h1>Edit Data</h1>
        <label for="fullname">Fullname:</label><br>
        <input type="text" placeholder="Enter your fullname" id="fullname" name="fullname" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : (isset($data['fullname']) ? $data['fullname'] : ''); ?>"><br>
        <input type="hidden" name="id" value="<?php echo isset($_POST['id']) ? $_POST['id'] : ''; ?>">
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
