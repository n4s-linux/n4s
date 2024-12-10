<?php

require '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

define('DB_CREDENTIALS_PATH', getenv('HOME') . '/.n4sdb.yaml');

// Function to prompt the user for credentials
function prompt_for_credentials() {
    echo "Enter PostgreSQL host: ";
    $host = trim(fgets(STDIN));

    echo "Enter PostgreSQL port (default 5432): ";
    $port = trim(fgets(STDIN));
    if (empty($port)) {
        $port = '5432'; // Default port if not provided
    }

    echo "Enter PostgreSQL user: ";
    $user = trim(fgets(STDIN));

    echo "Enter PostgreSQL password: ";
    system('stty -echo'); // Disable password echo for security
    $pass = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";

    return [
        'host' => $host,
        'port' => $port,
        'user' => $user,
        'pass' => $pass
    ];
}

// Function to save the credentials to a YAML file
function save_credentials_to_yaml($credentials) {
    try {
        $yaml_content = Yaml::dump($credentials);
        file_put_contents(DB_CREDENTIALS_PATH, $yaml_content);
        echo "Credentials saved successfully.\n";
    } catch (Exception $e) {
        throw new Exception("Failed to save credentials to YAML: " . $e->getMessage());
    }
}

// Function to verify credentials by attempting to connect to the database
function verify_credentials($credentials) {
    $conn_string = "host={$credentials['host']} port={$credentials['port']} dbname=n4s user={$credentials['user']} password={$credentials['pass']}";
    $conn = pg_connect($conn_string);

    if (!$conn) {
        return false; // Return false if connection fails
    }

    pg_close($conn);
    return true;
}

// Function to load database credentials from YAML, prompt if they don't exist, and verify them
function load_db_credentials() {
    if (file_exists(DB_CREDENTIALS_PATH)) {
        try {
            $n4sdb = Yaml::parseFile(DB_CREDENTIALS_PATH);
            if (!isset($n4sdb['host'], $n4sdb['port'], $n4sdb['user'], $n4sdb['pass'])) {
                throw new Exception("Missing required database credentials in YAML.");
            }

            if (verify_credentials($n4sdb)) {
                return $n4sdb;
            } else {
                echo "Stored credentials are invalid. Please provide correct credentials.\n";
            }

        } catch (Exception $e) {
            echo "Error parsing YAML: " . $e->getMessage() . "\n";
        }
    }

    while (true) {
        $n4sdb = prompt_for_credentials();

        if (verify_credentials($n4sdb)) {
            save_credentials_to_yaml($n4sdb);
            return $n4sdb;
        } else {
            echo "Connection failed. Please try again.\n";
        }
    }
}

// Function to connect to the PostgreSQL database and create the table if it doesn't exist
function opendb($table_name) {
    try {
        $n4sdb = load_db_credentials();
        $conn_string = "host={$n4sdb['host']} port={$n4sdb['port']} dbname=n4s user={$n4sdb['user']} password={$n4sdb['pass']}";
        $conn = pg_connect($conn_string);

        if (!$conn) {
            throw new Exception("Failed to connect to the database after verifying credentials.");
        }

        $query = "SELECT to_regclass('$table_name');";
        $result = pg_query($conn, $query);
        if (!$result) {
            throw new Exception("Error checking if table exists: " . pg_last_error($conn));
        }
        $table_exists = pg_fetch_result($result, 0, 0);

        if ($table_exists === null) {
            $create_table_query = "
                CREATE TABLE $table_name (
                    id SERIAL PRIMARY KEY,
                    filename VARCHAR NOT NULL,
                    hash VARCHAR NOT NULL,
                    data VARCHAR NOT NULL,
                    signature VARCHAR NOT NULL,
                    pubkey VARCHAR NOT NULL,
                    ip VARCHAR[] DEFAULT ARRAY[]::VARCHAR[]
                );
            ";
            
            $result = pg_query($conn, $create_table_query);
            if (!$result) {
                throw new Exception("Error creating table: " . pg_last_error($conn));
            }

            // Revoke UPDATE and DELETE rights for all users
            $revoke_privileges = "
                REVOKE UPDATE, DELETE ON $table_name FROM PUBLIC;
            ";
            $result = pg_query($conn, $revoke_privileges);
            if (!$result) {
                throw new Exception("Error revoking privileges: " . pg_last_error($conn));
            }

            $create_ip_function = "
                CREATE OR REPLACE FUNCTION set_ip_address()
                RETURNS TRIGGER AS $$
                BEGIN
                    NEW.ip := ARRAY[inet_client_addr()::VARCHAR];
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;
            ";
            $result = pg_query($conn, $create_ip_function);
            if (!$result) {
                throw new Exception("Error creating IP function: " . pg_last_error($conn));
            }

            $create_ip_trigger = "
                CREATE TRIGGER update_ip_after_insert
                BEFORE INSERT ON $table_name
                FOR EACH ROW EXECUTE FUNCTION set_ip_address();
            ";
            $result = pg_query($conn, $create_ip_trigger);
            if (!$result) {
                throw new Exception("Error creating IP trigger: " . pg_last_error($conn));
            }

            echo "Table '$table_name' created successfully with IP auto-update and restricted UPDATE/DELETE rights.\n";
        } else {
            echo "Table '$table_name' already exists.\n";
        }

        return $conn;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Function to insert a record into the table
function insert_record($conn, $table_name, $filename, $hash, $data, $signature, $pubkey) {
    try {
        $query = "INSERT INTO $table_name (filename, hash, data, signature, pubkey) VALUES ($1, $2, $3, $4, $5)";
        $params = [$filename, $hash, $data, $signature, $pubkey];

        $result = pg_query_params($conn, $query, $params);

        if (!$result) {
            throw new Exception("Error inserting record: " . pg_last_error($conn));
        }

        echo "Record inserted successfully.\n";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Function to update a record by id (which should fail due to revoked rights)
function update_record($conn, $table_name, $id, $new_data) {
    try {
        $query = "UPDATE $table_name SET data = $1 WHERE id = $2";
        $params = [$new_data, $id];

        $result = pg_query_params($conn, $query, $params);
        if (!$result) {
            echo ("Error updating record: " . pg_last_error($conn));
		die();
        }

        echo "Record with id $id updated successfully.\n";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
	die();
    }
}

// Function to delete a record by id (which should fail due to revoked rights)
function delete_record($conn, $table_name, $id) {
    try {
        $query = "DELETE FROM $table_name WHERE id = $1";
        $params = [$id];

        $result = pg_query_params($conn, $query, $params);

        if (!$result) {
            throw new Exception("Error deleting record: " . pg_last_error($conn));
        }

        echo "Record with id $id deleted successfully.\n";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Connect to the database and ensure the table exists
$conn = opendb('cryptographic_data');

if ($conn) {
    // Insert a record into the table
    $data = json_encode(['field1' => 'value1', 'field2' => 'value2']);
    $signature = json_encode(['signature' => 'sigdata']);
    $pubkey = json_encode(['public_key' => 'pubkeydata']);

    insert_record(
        $conn,
        'cryptographic_data',
        'example.txt',
        '123abc',
        $data,
        $signature,
        $pubkey
    );

    // Attempt to update the record with id = 1 (should fail)
    update_record($conn, 'cryptographic_data', 1, json_encode(['new_field' => 'new_value']));

    // Attempt to delete the record with id = 1 (should fail)
    delete_record($conn, 'cryptographic_data', 1);
} else {
    echo "Failed to establish database connection.\n";
}
?>

