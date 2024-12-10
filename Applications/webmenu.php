<?php
function topmenu() {
    $menus = array("Accounts", "Transactions", "Creditors", "Codes", "AccountData");
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">n4s</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php
                        foreach (array_unique($menus) as $menu) {
                            // Determine if the current menu item is active
                            $activeClass = (isset($_GET['page']) && $_GET['page'] === $menu) ? 'active' : '';
                            echo '<li class="nav-item">
                                    <a class="nav-link ' . $activeClass . '" href="web.php?page=' . htmlspecialchars($menu) . '">
                                        ' . htmlspecialchars($menu) . '
                                    </a>
                                  </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <style>
        /* Highlight the active link with custom styling */
        .nav-link.active {
            color: #ffffff !important;
            background-color: #0d6efd !important; /* Bootstrap primary color */
            border-radius: 5px;
        }

        /* Add hover effect to all links */
        .nav-link:hover {
            color: #ffffff !important;
            background-color: #0b5ed7 !important; /* Slightly darker shade of Bootstrap primary */
            border-radius: 5px;
            transition: all 0.2s ease-in-out;
        }

        /* Adjust the navbar's overall appearance */
        .navbar {
            font-size: 1.1rem;
        }
    </style>
<?php
}
?>

