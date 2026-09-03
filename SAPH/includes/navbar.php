<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['UserID']);
$isVolunteerLoggedIn = isset($_SESSION['VolunteerID']);
$userRole = $_SESSION['Role'] ?? '';
?>

<!-- Header file for navigation -->
<header class="site-header">
    <div class="header-container">
        <!-- Logo Font -->
         <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
        <!-- Logo -->
        <h1 class="logo"> Save-a-Pet HUB <img src = "includes/assets/images/logo.jpeg"></h1>

        <!-- Navigation -->
        <nav class="nav-links">
           <a href="index.php">Home</a>
    <a href="pet_listings.php">Adopt</a>
    <!-- GET INVOLVED -->
            <div class="nav-dropdown">

                <button class="dropdown-button">
                    Get Involved
                    <span class="dropdown-arrow">▾</span>
                </button>

                <div class="dropdown-menu">

                    <a href="donation.php">
                        Donate
                    </a>

                    <a href="volunteer.php">
                        Volunteer
                    </a>

                </div>

            </div>


            <!-- ANIMAL SERVICES -->
            <div class="nav-dropdown">

                <button class="dropdown-button">
                    Animal Services
                    <span class="dropdown-arrow">▾</span>
                </button>

                <div class="dropdown-menu">

                    <a href="report_abuse.php">
                        Report Animal Abuse
                    </a>

                    <a href="pet_surrender.php">
                        Pet Surrender
                    </a>

                </div>

            </div>


            <!-- NOT LOGGED IN -->
            <?php if (!$isLoggedIn && !$isVolunteerLoggedIn): ?>

                <a href="login.php">
                    Login
                </a>

                <a href="register.php">
                    Register
                </a>


            <!-- LOGGED IN -->
            <?php else: ?>


                <!-- NORMAL USER -->
                <?php if ($isLoggedIn && $userRole === 'User'): ?>

                    <a
                        href="profile.php"
                        class="navbar-avatar"
                        title="My Profile">

                        <span class="navbar-avatar">
                            👤
                        </span>

                    </a>


                <!-- ADMIN -->
                <?php elseif ($isLoggedIn && $userRole === 'Admin'): ?>

                    <a
                        href="admin_dashboard.php"
                        class="navbar-avatar"
                        title="Admin Profile">

                        <span class="navbar-avatar">
                            👤
                        </span>

                    </a>


                <!-- VOLUNTEER -->
                <?php elseif ($isVolunteerLoggedIn): ?>

                    <a
                        href="volunteer_profile.php"
                        class="navbar-avatar"
                        title="Volunteer Profile">

                        <span class="navbar-avatar">
                            👤
                        </span>

                    </a>

                <?php endif; ?>


            <?php endif; ?>

        </nav>

    </div>

</header>