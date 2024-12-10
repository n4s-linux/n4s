<?php
// Load the database credentials from ~/.n4sdb.php
include getenv('HOME') . '/.n4sdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['dbname'];

    // Connect to PostgreSQL using the superuser credentials
    try {
        $pdo = new PDO("pgsql:host={$n4sdb['host']};port={$n4sdb['port']};dbname={$n4sdb['db']}", $n4sdb['user'], $n4sdb['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create a new database for the user
        $pdo->exec("CREATE DATABASE $dbname");
        echo "Database $dbname created successfully.<br>";

        // Create the user and set their password
        $pdo->exec("CREATE USER $username WITH ENCRYPTED PASSWORD '$password'");
        
        // Grant all privileges on the new database to the user
        $pdo->exec("GRANT ALL PRIVILEGES ON DATABASE $dbname TO $username");

        // Connect to the newly created database to grant schema-level privileges
        $userPdo = new PDO("pgsql:host={$n4sdb['host']};port={$n4sdb['port']};dbname=$dbname", $n4sdb['user'], $n4sdb['pass']);
        $userPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Grant usage and create privileges on the public schema to the user
        $userPdo->exec("GRANT ALL ON SCHEMA public TO $username");

        echo "User $username granted full privileges on $dbname, including schema access.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Database</title>
</head>
<body>
    <h1>Create a Database</h1>
    <form method="POST">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Database Name: <input type="text" name="dbname" required><br>
        <button type="submit">Create Database</button>
    </form>
</body>
</html>

