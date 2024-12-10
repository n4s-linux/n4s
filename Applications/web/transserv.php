<?php
// Load the database credentials from ~/.n4sdb.php
include getenv('HOME') . '/.n4sdb.php';

// Function to create the table if it doesn't exist
function ensureTableExists($pdo, $tableName) {
    $sql = "
        CREATE TABLE IF NOT EXISTS $tableName (
            id BIGSERIAL PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            filename VARCHAR(255) NOT NULL,
            hash VARCHAR(255) NOT NULL,
            public_key VARCHAR(255) NOT NULL,
            signature VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) NOT NULL
        );
    ";
    $pdo->exec($sql);
}

// Function to authenticate the user and connect to the specified database
function authenticateUser($dbname, $username, $password) {
    global $n4sdb;
    try {
        // Attempt connection using the provided username, password, and database name
        $pdo = new PDO("pgsql:host={$n4sdb['host']};port={$n4sdb['port']};dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
}

// Transaction submission logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbname = $_POST['dbname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $tableName = $_POST['table_name'];
    $filename = $_POST['filename'];
    $hash = $_POST['hash'];
    $publicKey = $_POST['public_key'];
    $signature = $_POST['signature'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Authenticate the user and connect to the specified database
    $pdo = authenticateUser($dbname, $username, $password);
    if (!$pdo) {
        echo json_encode(['error' => 'Authentication failed or cannot connect to database.']);
        exit();
    }

    // Ensure the table exists
    ensureTableExists($pdo, $tableName);

    // Insert the transaction into the table
    try {
        $stmt = $pdo->prepare("
            INSERT INTO $tableName (filename, hash, public_key, signature, ip_address)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$filename, $hash, $publicKey, $signature, $ipAddress]);

        echo json_encode(['success' => true, 'message' => 'Transaction created successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Transaction failed: ' . $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Transaction</title>
</head>
<body>
    <h1>Create Transaction</h1>
    <form method="POST">
        Database Name: <input type="text" name="dbname" required><br>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Table Name: <input type="text" name="table_name" required><br>
        Filename: <input type="text" name="filename" required><br>
        Hash: <input type="text" name="hash" required><br>
        Public Key: <input type="text" name="public_key" required><br>
        Signature: <input type="text" name="signature" required><br>
        <button type="submit">Submit Transaction</button>
    </form>
</body>
</html>

