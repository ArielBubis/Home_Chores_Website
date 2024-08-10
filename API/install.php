<?php
mysqli_report(MYSQLI_REPORT_OFF);
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$dbName = "hw3_205735749_208019729";
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql)) {
    echo "Database created successfully <br>";

    // Select the newly created database
    $conn->select_db($dbName);

    require_once('create_tables.php');
    create_users_table($conn);
    create_household_table($conn);
    users_partof_household($conn);
    create_chores_list_table($conn);
    create_chrores_table($conn);
} else {
    echo "Error creating data base:" . $conn->error . "<br>";
    die();
}

// ---- Insert values to tables ----

// Add test yser record 
$password = 'test123';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users(email, first_name, last_name, avatar_color, password) 
    VALUES
    ('test@test.com', 'John', 'Doe','43a047', '$passwordHash'),
    ('test1@test.com', 'Jane', 'Doe','e303fc', '$passwordHash')
    ";

if ($conn->query($sql)) {
    echo "Test user added successfully<br>";
} else {
    echo "Error: " . $sql . " " . $conn->error . "<br>";
}


// Add chores lists records
$sql = "INSERT INTO Household(responsible_user_id)
    VALUES
        (1),
        (2)";
if ($conn->query($sql)) {
    echo "2 households added successfully<br>";
} else {
    echo "Error: " . $sql . " " . $conn->error . "<br>";
}

// Add chores lists records
$sql = "INSERT INTO Users_partOf_Household(user_id, house_id)
    VALUES
        (1,1),
        (2,2),
        (1,2)
        ";
if ($conn->query($sql)) {
    echo "users added as part of households successfully<br>";
} else {
    echo "Error: " . $sql . " " . $conn->error . "<br>";
}

// Add chores lists records
$sql = "INSERT INTO Chores_List(house_id, responsible_user_id ,list_title, due_date, status)
    VALUES
        (1,1,'House chores', '2024-06-07', 0),
        (1,1,'Shopping' ,'2024-06-08', 0),
        (2,2,'Motel chores', '2024-06-07', 0)"
        ;
if ($conn->query($sql)) {
    echo "2 Chores lists added successfully<br>";
} else {
    echo "Error: " . $sql . " " . $conn->error . "<br>";
}

// Add chores to chores lists 
$sql = "INSERT INTO Chores(list_id, chore_title, date_added, user_id, finished)
    VALUES
        (1, 'Clean the house', '2024-06-07', 1, 0),
        (1, 'Organize the closet' ,'2024-06-07', 1, 0),
        (1, 'Washing dishes', '2024-06-07', 1, 0),
        (1, 'Paint the wall', '2024-06-09', 1, 0),
        (2, 'Buy milk', '2024-06-08', 2, 0),
        (2, 'Buy wall paint' ,'2024-06-09', 2, 0),
        (2, 'Buy paintbrush', '2024-06-09', 2, 0),
        (3, 'Organize the closet' ,'2024-06-07', 2, 0),
        (3, 'Washing dishes', '2024-06-07', 2, 0),
        (3, 'Paint the wall', '2024-06-09', 1, 0)";

if ($conn->query($sql)) {
    echo "Chores added successefully to chores lists";
} else {
    echo "Error: " . $sql . " " . $conn->error . "<br>";
}

// $stmt = $conn->prepare("INSERT INTO Users(
//     email, first_name, last_name, password) VALUES(?,?,?,?)");
// $stmt->bind_param("ssss", $email,$first_name, $last_name, $password);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create database</title>

</head>

<body>
    <div class="container all_style">
        <br>
        <a href="../log_in.php">
            <button type="button">Back to site</button>
        </a>
    </div>
</body>

</html>