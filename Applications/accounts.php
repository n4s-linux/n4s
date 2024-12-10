<?php
    function Accounts() {
	global $tpath; global $begin; global $end;
	ob_start();
	system("tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end php /svn/svnroot/Applications/newl.php csv");
	$d = ob_get_clean();
	$lines = explode("\n",$d);
	$data = array();
	foreach ($lines as $curline) {
		$line = str_getcsv($curline);
		$data[] = $line;
	}
	dat2html($data);
    }
function dat2html($data) {
	$bal = array();
	$tla = array();
	$tlabal = array();
	foreach ($data as $curdata) {
		if (!isset($curdata[3])) continue;
		$acc = $curdata[3];
		if (isset($bal[$acc])) $bal[$acc] += $curdata[5];
		else $bal[$acc] = $curdata[5];
		$t = explode(":",$acc)[0];
		if (!isset($tlabal[$t])) $tlabal[$t] = 0; $tlabal[$t] += $curdata[5];
		if (!in_array($t,$tla)) array_push($tla,$t);
	}
	require_once("/svn/svnroot/Applications/tlasort.php");
	usort($tla,"tlasort");
	foreach ($tla as $curtla) {
		rendertla($curtla,$data,$tlabal[$curtla]);
	}
}
function rendertla($tla,$data,$tlabal) {
	ob_start();
	echo "<table width=600 class='table table-striped'>";
	foreach ($data as $curdata) {
		if (!isset($curdata[3])) continue;
		$x = explode(":",$curdata[3]);
		$tl = $x[0];
		$nl = $x[1];
		if ($tl != $tla) continue;
		if (!isset($bal[$nl])) $bal[$nl] = 0;
		$bal[$nl] += $curdata[5];
	}
	foreach ($bal as $cb => $cv) {
		$pv = pv($cv);
		echo "<tr><td width=500>$cb</td><td width=100><p align=right>$pv</p></td>\n";
	}
	echo "</table>";
	$d = ob_get_clean();
	//function expandableDiv($header, $content) {
	echo expandableDiv("$tla",$d,$tlabal);
}
function pv($var) {
    $var = floatval($var);

    return number_format($var,0,",",".");
}
function expandableDiv($header, $content, $balance = 100000, $tpath = '/path/to/directory') {
    $pb = number_format($balance, 0, ',', '.'); // Format the balance
    $uniqueId = uniqid("expandable_div_");
    $filePath = "$tpath/.expansiondata.json";

    // Load existing expansion data or initialize empty
    if (file_exists($filePath)) {
        $expansionData = json_decode(file_get_contents($filePath), true);
    } else {
        $expansionData = [];
    }

    // Check if this div is expanded; default to false if not set
    $isExpanded = isset($expansionData[$uniqueId]) ? $expansionData[$uniqueId] : false;

    // JavaScript to toggle the div and update the JSON file
    $script = <<<JS
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header_$uniqueId');
            const content = document.getElementById('content_$uniqueId');
            const icon = document.getElementById('icon_$uniqueId');
            
            // Set initial state from PHP
            const isExpanded = $isExpanded;
            content.style.display = isExpanded ? 'block' : 'none';
            icon.textContent = isExpanded ? '➖' : '➕'; // Expanded: Minus, Collapsed: Plus

            header.addEventListener('click', function() {
                const expanded = content.style.display === 'none';
                content.style.display = expanded ? 'block' : 'none';
                icon.textContent = expanded ? '➖' : '➕';

                // Update JSON file on the server
                fetch('save_expansion.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: '$uniqueId', expanded: expanded })
                }).catch(err => console.error('Error updating expansion state:', err));
            });
        });
    </script>
    JS;

    // HTML structure for the expandable div
    $html = <<<HTML
    <style>
        .expandable-container {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            border-radius: 5px;
            overflow: hidden;
        }
        .expandable-header {
            cursor: pointer;
            background: #f0f0f0;
        }
        .expandable-content {
            padding: 10px;
            display: none;
        }
    </style>
    <div class="expandable-container">
        <div id="header_$uniqueId" class="expandable-header">
            <span id="icon_$uniqueId"></span>
            <table width=600 class='table table-striped'>
                <tr>
                    <td width=500><b>$header</b></td>
                    <td width=100><p align=right><b>$pb</b></p></td>
                </tr>
            </table>
        </div>
        <div id="content_$uniqueId" class="expandable-content">
            $content
        </div>
    </div>
    $script
    HTML;

    return $html;
}

// Server-side logic to update the JSON file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	global $tpath;
    $input = json_decode(file_get_contents('php://input'), true);
    $filePath = "$tpath/.expansiondata.json";

    // Validate the incoming data
    if (!empty($input['id']) && isset($input['expanded'])) {
        // Load existing expansion data or initialize
        $expansionData = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        // Update the expansion state
        $expansionData[$input['id']] = (bool)$input['expanded'];

        // Save the updated expansion data
        file_put_contents($filePath, json_encode($expansionData));
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        exit;
    }
}

// Ensure the expansion file exists with an empty structure if not already present
if (isset($tpath)&&!file_exists("$tpath/.expansiondata.json")) {
    file_put_contents("$tpath/.expansiondata.json", json_encode([]));
}

