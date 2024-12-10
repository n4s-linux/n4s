<?php

function renderAccountPicker() {
    // Start the session to handle session variables
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Define directories to scan
    $directories = ['~/regnskaber', '/data/regnskaber'];

    // Resolve paths and get folder names
    $folders = [];
    foreach ($directories as $dir) {
        $resolvedDir = realpath($dir); // Resolve to absolute path
        if ($resolvedDir && is_dir($resolvedDir)) {
            foreach (scandir($resolvedDir) as $folder) {
                if (is_dir($resolvedDir . DIRECTORY_SEPARATOR . $folder) && $folder[0] !== '.') {
                    $relativePath = $dir . DIRECTORY_SEPARATOR . $folder;
                    $folders[] = $relativePath; // Save relative paths
                }
            }
        }
    }

    // If there is no session set and the list is not empty, pick the first account
    if (!isset($_SESSION['tpath']) && !empty($folders)) {
        $_SESSION['tpath'] = $folders[0]; // Set the first account as the default
    }

    // Update session variable if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tpath']) && in_array($_POST['tpath'], $folders)) {
        $_SESSION['tpath'] = $_POST['tpath']; // Save the selected account in the session
        exit(); // Stop further processing after posting
    }

    // Include Bootstrap and jQuery assets (CDN-based)
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">';
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';

    // Generate HTML
    echo <<<HTML
<div class="form-group">
    <label for="selectedAccount">Selected Account:</label>
    <input type="text" id="selectedAccount" class="form-control" readonly style="cursor: pointer;" 
           value="{$_SESSION['tpath']}" onclick="showAccountPicker()">
</div>

<!-- Modal -->
<div class="modal fade" id="accountPickerModal" tabindex="-1" aria-labelledby="accountPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountPickerModalLabel">Select an Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <input type="text" id="accountSearch" class="form-control mb-3 me-2" placeholder="Search accounts...">
                    <button type="button" class="btn btn-light text-danger" onclick="closeAccountPicker()">❌ Cancel</button>
                </div>
                <ul id="accountList" class="list-group">
HTML;

    foreach ($folders as $folder) {
        echo '<li class="list-group-item account-item" data-folder="' . htmlspecialchars($folder) . '">' . htmlspecialchars($folder) . '</li>';
    }

    echo <<<HTML
                </ul>
            </div>
        </div>
    </div>
</div>
HTML;

    // JavaScript for modal interaction and filtering
    echo <<<JS
<script>
function showAccountPicker() {
    var modal = new bootstrap.Modal(document.getElementById('accountPickerModal'), {});
    modal.show();

    // Focus on the search field when the modal is shown
    $('#accountPickerModal').on('shown.bs.modal', function() {
        $('#accountSearch').focus();
    });
}

function closeAccountPicker() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('accountPickerModal'));
    modal.hide();
}

// Real-time search filtering
$(document).ready(function() {
    $("#accountSearch").on("input", function() {
        var filter = $(this).val().toLowerCase();
        $("#accountList .account-item").each(function() {
            var text = $(this).data("folder").toLowerCase();
            $(this).toggle(text.indexOf(filter) > -1);
        });
    });

    // Select an account and post it immediately
    $(document).on("click", ".account-item", function() {
        var selectedAccount = $(this).data("folder");

        // Immediately post the selected account
        $.post("", { tpath: selectedAccount }, function() {
            location.reload(); // Reload the page after posting
        });
    });
});
</script>
JS;
}
?>

