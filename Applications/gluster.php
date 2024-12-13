<?php
if ($argc < 2) {
    die("Usage: php gluster_dynamic_setup.php <username>\n");
}

$username = $argv[1];
$glusterVolume = "sharedvol";
$localFolder = "/root/glusterfs";
$mountPoint = "/data/regnskaber/gluster";
$peersFile = "/root/.peers";

function logMessage($message) {
    echo "[" . date("Y-m-d H:i:s") . "] $message\n";
}

function runCommand($command, $description) {
    logMessage("Running: $description");
    $output = [];
    $returnVar = 0;
    exec($command, $output, $returnVar);
    if ($returnVar !== 0) {
        logMessage("Error: Command failed with exit code $returnVar\n" . implode("\n", $output));
    } else {
        logMessage("Success: " . implode("\n", $output));
    }
    return $returnVar === 0;
}

function getPeers($file) {
    if (!file_exists($file)) {
        logMessage("Peer file $file does not exist. Waiting...");
        return [];
    }
    return array_filter(array_map('trim', file($file)));
}

logMessage("Starting GlusterFS setup for user: $username");

// Step 1: Install GlusterFS (if not installed)
//runCommand("apt update && apt install -y glusterfs-server glusterfs-client", "Installing GlusterFS");

// Step 2: Start GlusterFS service
//runCommand("systemctl enable glusterd && systemctl start glusterd", "Starting GlusterFS service");

// Step 3: Create and set permissions for the local folder
if (!is_dir($localFolder)) {
    runCommand("mkdir -p $localFolder", "Creating GlusterFS storage folder");
}

// Step 4: Create and set permissions for the mount point
if (!is_dir($mountPoint)) {
    runCommand("mkdir -p $mountPoint", "Creating GlusterFS mount point");
}
runCommand("chown $username:$username $mountPoint", "Setting ownership for mount point");

// Step 5: Continuously check peers and configure GlusterFS
$knownPeers = [];
while (true) {
    $peers = getPeers($peersFile);

    foreach ($peers as $peer) {
        if (!in_array($peer, $knownPeers)) {
            if (runCommand("gluster peer probe $peer", "Probing peer node: $peer")) {
                $knownPeers[] = $peer;
            }
        }
    }

    // Create the volume only on the first peer
    if (gethostname() === $peers[0] && count($knownPeers) > 1) {
        $bricks = array_map(fn($node) => "$node:$localFolder", $knownPeers);
        $bricksString = implode(" ", $bricks);

        if (!runCommand("gluster volume info $glusterVolume", "Checking if volume exists")) {
            runCommand("gluster volume create $glusterVolume replica " . count($knownPeers) . " $bricksString",
                "Creating GlusterFS volume");
            runCommand("gluster volume start $glusterVolume", "Starting GlusterFS volume");
        }
    }

    // Mount the volume
    if (!shell_exec("mount | grep -q '$mountPoint'")) {
        runCommand("mount -t glusterfs {$peers[0]}:$glusterVolume $mountPoint", "Mounting GlusterFS volume");
    }

    // Provide updates every 10 seconds
    logMessage("Current peers: " . implode(", ", $knownPeers));
    sleep(10);
}
?>

