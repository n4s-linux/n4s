<?php
// Decode the incoming JSON request
$request = json_decode(file_get_contents('php://input'), true);

if (isset($request['tpath']) && isset($request['id']) && isset($request['expanded'])) {
    $tpath = $request['tpath'];
    $expansionFile = "$tpath/.expansion";

    // Ensure the file exists
    if (!file_exists($expansionFile)) {
        file_put_contents($expansionFile, json_encode([]));
    }

    // Load the current states
    $expansionStates = json_decode(file_get_contents($expansionFile), true);

    // Update the state based on the request
    $expansionStates[$request['id']] = $request['expanded'];
    file_put_contents($expansionFile, json_encode($expansionStates));

    http_response_code(200);
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}

