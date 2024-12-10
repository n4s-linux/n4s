<?php
    $op = exec("whoami");
    require_once("/home/$op/mariadb.cr.php");

    // Get the base name of the tpath environment variable
    $tpath = basename(getenv('tpath'));

    // Check if $tpath is set, exit if not
    if (!$tpath) {
        die("Error: Environment variable 'tpath' is not set or invalid.\n");
    }

    // Create mysqli connection
    $mysqli = new mysqli($dbh, $dbu, $dbp);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Use the specified database
    if (!$mysqli->select_db($db)) {
        die("Error selecting database: " . $mysqli->error);
    }

    // Create the table 'trans' if it does not exist
    $sql = "
    CREATE TABLE IF NOT EXISTS `$tpath` (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `filename` VARCHAR(255) UNIQUE,
        `data` JSON,
        `signature` LONGTEXT,
        `pubkey` LONGTEXT,
        `hash` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";

    if (!$mysqli->query($sql)) {
        die("Error creating table: " . $mysqli->error);
    }

    // Fetch all existing filenames, hashes, and timestamps from the database
    $result = $mysqli->query("SELECT `filename`, `hash`, `data`,`id`, `created_at` FROM `$tpath`");
    if (!$result) {
        die("Error fetching data: " . $mysqli->error);
    }

    // Create an associative array for quick lookups
    $existingFilesMap = [];
    while ($file = $result->fetch_assoc()) {
        $existingFilesMap[$file['filename']] = $file;
    }

    // Check for .trans files in the full $tpath directory
    $files = glob(getenv('tpath') . '/*.trans');

    foreach ($files as $file) {
        // Get the filename
        $filename = basename($file);

        // Read the contents of the .trans file (assuming it's JSON)
        $fileContents = file_get_contents($file);
        $localData = json_decode($fileContents, true);

        // Create a hash of the file contents
        $hash = hash('sha256', $fileContents);

        if (isset($existingFilesMap[$filename])) {
            $dbFile = $existingFilesMap[$filename];
            if ($dbFile['hash'] !== $hash) {
                echo "$file needs updating\n";
                // Data exists in the database, compare and decide
                $dbData = json_decode($dbFile['data'], true);
                $dbId = $dbFile['id'];

                // Get file timestamps
                $localFileTime = filemtime($file);
                $dbFileTime = strtotime($dbFile['created_at']);

                // Determine which version is more recent
                $isLocalMoreRecent = $localFileTime > $dbFileTime;
		if ($isLocalMoreRecent) {
			require_once("/svn/svnroot/Applications/fzf.php");
			if ($localData == $dbData) continue;
			$cdiff = generateArrayDiff($dbData,$localData);
			print_r($cdiff);die();
			$choice = fzf($cdiff ,"ENTER => Accept Changes ! ESC = Dont Accept ($file)","--tac --ansi");
			if (strlen($choice) > 0){
			    // Add DeletionInstruction to local JSON
			    $localData['Deletes'] = ['id' => $dbId];

			    // Update database with local version
			    $stmt = $mysqli->prepare("INSERT INTO `$tpath` (`filename`, `data`, `hash`) VALUES (?, ?, ?)
						      ON DUPLICATE KEY UPDATE `data` = VALUES(`data`), `hash` = VALUES(`hash`)");
			    $stmt->bind_param('sss', $filename, json_encode($localData, JSON_PRETTY_PRINT), $hash);
			    $stmt->execute();

			    // Save updated local JSON file
			    file_put_contents($file, json_encode($localData, JSON_PRETTY_PRINT));
			} elseif ($choice === 'Reject') {
			    // Keep remote (database) version and overwrite the local file
			    file_put_contents($file, json_encode($dbData, JSON_PRETTY_PRINT));
			}
		}
		else {
			echo "overwriting $file\n";
			file_put_contents($file,$dbFile['data']);
		}

                // Clean up temporary files
            }
		echo "$filename ok...\n";
        } else {
		echo "file not in db\n";
            // New file not in database
            $stmt = $mysqli->prepare("INSERT INTO `$tpath` (`filename`, `data`, `hash`) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $filename, $fileContents, $hash);
            $stmt->execute();
        }
    }

    $mysqli->close();

/**
 * Recursively compute the difference between two multidimensional arrays
 * Add '+' for additions and '-' for deletions.
 *
 * @param array $oldArray The original array.
 * @param array $newArray The modified array.
 * @return array The difference array with '+' and '-' signs.
 */
function arrayDiffRecursive(array $oldArray, array $newArray): array {
    $diff = [];

    // Check for items present in $newArray but not in $oldArray (additions)
    foreach ($newArray as $key => $value) {
        if (array_key_exists($key, $oldArray)) {
            // If both old and new arrays have the same key and it's an array, recurse
            if (is_array($value) && is_array($oldArray[$key])) {
                $nestedDiff = arrayDiffRecursive($oldArray[$key], $value);
                if (!empty($nestedDiff)) {
                    $diff[$key] = $nestedDiff;
                }
            } elseif ($oldArray[$key] !== $value) {
                // If values are different, mark as addition in new and deletion in old
                $diff[$key] = ['-' => $oldArray[$key], '+' => $value];
            }
        } else {
            // Key is in $newArray but not in $oldArray, so it's an addition
            $diff[$key] = ['+' => $value];
        }
    }

    // Check for items present in $oldArray but not in $newArray (deletions)
    foreach ($oldArray as $key => $value) {
        if (!array_key_exists($key, $newArray)) {
            // Key is in $oldArray but not in $newArray, so it's a deletion
            $diff[$key] = ['-' => $value];
        }
    }

    return $diff;
}

/**
 * Recursively compute the difference between two multidimensional arrays and return
 * the formatted diff with colored output for additions and deletions.
 * Additions are green (+), deletions are red (-), and both are tabulated.
 *
 * @param array $oldArray The original array.
 * @param array $newArray The modified array.
 * @param string $indent The indentation for nested arrays (default is '').
 * @return string The formatted diff string.
 */
function generateArrayDiff(array $oldArray, array $newArray, string $indent = ''): string {
    $diff = '';
    
    // Check for items present in $newArray but not in $oldArray (additions)
    foreach ($newArray as $key => $value) {
        if (array_key_exists($key, $oldArray)) {
            // If both old and new arrays have the same key and it's an array, recurse
            if (is_array($value) && is_array($oldArray[$key])) {
                $nestedDiff = generateArrayDiff($oldArray[$key], $value, $indent . "\t");
                if (!empty($nestedDiff)) {
                    $diff .= "{$indent}$key:\n";
                    $diff .= $nestedDiff;
                }
            } elseif ($oldArray[$key] !== $value) {
                // If values are different, mark as addition in new and deletion in old
                $diff .= "{$indent}$key\t\033[38;5;196m- {$oldArray[$key]}\033[0m\n"; // Red for deletions
                $diff .= "{$indent}$key\t\033[38;5;46m+ $value\033[0m\n"; // Green for additions
            }
        } else {
            // Key is in $newArray but not in $oldArray, so it's an addition
            $diff .= "{$indent}$key\t\033[38;5;46m+ $value\033[0m\n"; // Green for additions
        }
    }

    // Check for items present in $oldArray but not in $newArray (deletions)
    foreach ($oldArray as $key => $value) {
        if (!array_key_exists($key, $newArray)) {
            // Key is in $oldArray but not in $newArray, so it's a deletion
            $diff .= "{$indent}$key\t\033[38;5;196m- $value\033[0m\n"; // Red for deletions
        }
    }

    return $diff;
}

?>

