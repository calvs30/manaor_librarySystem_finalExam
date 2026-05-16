<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Fetch dynamic operational counts for KPIs
$metrics = getDashboardMetrics($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Home</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Library System</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="index.php"><ion-icon name="home-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Homepage</a></li>
                <li><a href="viewBorrowers.php"><ion-icon name="document-text-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Borrowers Registry</a></li>
                <li><a href="activityLogs.php"><ion-icon name="today-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>System Activity Logs</a></li>
            </ul>
            <div class="sidebar-footer">
                <div class="admin-profile">Logged Admin: <span><?php echo htmlspecialchars($_SESSION['username']); ?></span></div>
                <a href="core/handleForms.php?logoutAUser=1" class="btn btn-danger" style="width:100%;"><ion-icon name="log-out-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Log Out</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <button class="menu-toggle">☰</button>
                <h1>Dashboard Library System Overview</h1>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card borrowers">
                    <div class="kpi-label">Registered Borrowers</div>
                    <div class="kpi-value"><?php echo number_format($metrics['total_borrowers']); ?></div>
                    <ion-icon name="people-outline"></ion-icon>
                </div>

                <div class="kpi-card books">
                    <div class="kpi-label">Books Checked Out</div>
                    <div class="kpi-value"><?php echo number_format($metrics['total_books']); ?></div>
                    <ion-icon name="library-outline"></ion-icon>
                </div>

                <div class="kpi-card returned">
                    <div class="kpi-label">Total Books Returned</div>
                    <div class="kpi-value"><?php echo number_format($metrics['total_returned']); ?></div>
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <h2>Register New Book Borrower</h2>
                <form action="core/handleForms.php" method="POST">
                    <p><label>First Name</label><input type="text" name="firstName" required></p>
                    <p><label>Last Name</label><input type="text" name="lastName" required></p>
                    <p><label>Address Base</label><input type="text" name="homeAddress" required></p>
                    <p><label>Contact Field</label><input type="text" name="contactNumber" required></p>
                    <input type="submit" name="insertBorrowerBtn" value="Register Profile">
                </form>
            </div>
        </main>
    </div>
</body>
</html>