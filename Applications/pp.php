<?php

function renderCompactPeriodPicker() {
    // Start the session to handle session variables
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Set default session values if not already set
    if (!isset($_SESSION['begin']) || !isset($_SESSION['end'])) {
        $_SESSION['begin'] = date('Y-m-d', strtotime('first day of January this year')); // Default to the start of the year
        $_SESSION['end'] = date('Y-m-d'); // Default to today
    }

    // Update session variables if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $_SESSION['begin'] = $_POST['start_date'];
            $_SESSION['end'] = $_POST['end_date'];
	global $tpath;
		putenv("tpath=$tpath");
		putenv("LEDGER_BEGIN=$_SESSION[begin]");
		putenv("LEDGER_END=$_SESSION[end]");
		echo "calculating opening\n";
		$cmd = ("LEDGER_END=$_SESSION[end] LEDGER_BEGIN=$_SESSION[begin] tpath=$tpath php /svn/svnroot/Applications/calcopening.php");
		$cmd = "cd $tpath; tpath=$tpath LEDGER_DEPTH=999 LEDGER_BEGIN=1970-01-01 LEDGER_END=$_SESSION[end] noend=1 color=none php /svn/svnroot/Applications/newl.php csv|LEDGER_BEGIN=$_SESSION[end] tpath=$tpath php /svn/svnroot/Applications/calcopening.php";
		echo "run '$cmd'\n";
		$d = exec($cmd);
		echo "ran cmd ok\n";
		echo $d;
        }
    }

    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">';
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>';

    $currentDate = new DateTime('now');
    $startDate = (new DateTime('now'))->modify('-5 years')->setDate((int)$currentDate->format('Y') - 5, 1, 1);
    $presets = [];
    $tempDate = clone $startDate;

    // Generate months
    while ($tempDate <= $currentDate) {
        $presets["Month: " . $tempDate->format('F Y')] = [
            'start' => $tempDate->format('Y-m-01'),
            'end' => $tempDate->format('Y-m-t')
        ];
        $tempDate->modify('+1 month');
    }

    // Generate quarters
    $tempDate = clone $startDate;
    while ($tempDate <= $currentDate) {
        $quarter = ceil((int)$tempDate->format('n') / 3);
        $year = $tempDate->format('Y');
        $startQuarter = (new DateTime("$year-" . (($quarter - 1) * 3 + 1) . "-01"));
        $endQuarter = (clone $startQuarter)->modify('+2 months')->modify('last day of this month');
        $presets["Quarter: Q$quarter $year"] = [
            'start' => $startQuarter->format('Y-m-d'),
            'end' => $endQuarter->format('Y-m-d')
        ];
        $tempDate->modify('+3 months');
    }

    // Generate half-years
    for ($year = $startDate->format('Y'); $year <= $currentDate->format('Y'); $year++) {
        $presets["Half-Year: H1 $year"] = ['start' => "$year-01-01", 'end' => "$year-06-30"];
        $presets["Half-Year: H2 $year"] = ['start' => "$year-07-01", 'end' => "$year-12-31"];
    }

    echo '<form method="post" class="d-flex align-items-center gap-2" style="white-space: nowrap;">';
    echo '<input type="text" id="startDate" name="start_date" class="form-control datepicker" placeholder="Start" required style="width: 120px;" value="' . htmlspecialchars($_SESSION['begin']) . '">';
    echo '<input type="text" id="endDate" name="end_date" class="form-control datepicker" placeholder="End" required style="width: 120px;" value="' . htmlspecialchars($_SESSION['end']) . '">';
    echo '<select id="presetPeriods" class="form-select" style="width: 160px;"><option value="">-- Presets --</option>';
    foreach ($presets as $name => $period) {
        echo '<option value="' . htmlspecialchars(json_encode($period)) . '">' . htmlspecialchars($name) . '</option>';
    }
    echo '</select>';
    echo '<button type="submit" class="btn btn-primary btn-sm">Go</button>';
    echo '</form>';

    echo <<<JS
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true});
    $('#presetPeriods').on('change', function() {
        const selectedPeriod = $(this).val();
        if (selectedPeriod) {
            const period = JSON.parse(selectedPeriod);
            $('#startDate').datepicker('setDate', period.start);
            $('#endDate').datepicker('setDate', period.end);
        }
    });
});
</script>
JS;
}
?>

