<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Delete Borrower</title>
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
                <li><a href="index.php"><ion-icon name="home-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Homepage</a></li>
                <li class="active"><a href="viewBorrowers.php"><ion-icon name="document-text-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Borrowers Registry</a></li>
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
                <h1>Remove Profile Record</h1>
            </div>

            <div class="card" style="max-width: 550px; margin: 0 auto; border-top: 4px solid #ef4444;">
                <h2 style="color:#ef4444;">Are you absolutely sure?</h2>
                <p style="margin-bottom: 20px; color: #64748b;">This action will permanently strip the profile configuration along with its linked transaction records from the ledger system.</p>
                
                <?php $getBorrowerByID = getBorrowerByID($pdo, $_GET['borrower_id']); ?>
                <div style="background-color: #f8fafc; padding: 18px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ef4444;">
                    <p style="margin-bottom: 8px;"><strong>Full Name:</strong> <?php echo sanitize($getBorrowerByID['first_name'] . " " . $getBorrowerByID['last_name']); ?></p>
                    <p style="margin-bottom: 8px;"><strong>Address:</strong> <?php echo sanitize($getBorrowerByID['home_address']); ?></p>
                    <p style="margin-bottom: 0;"><strong>Contact Number:</strong> <?php echo sanitize($getBorrowerByID['contact_number']); ?></p>
                </div>

                <form action="core/handleForms.php?borrower_id=<?php echo $_GET['borrower_id']; ?>" method="POST" style="display:flex; gap:12px; padding:0; box-shadow:none; background:none;">
                    <input type="submit" name="deleteBorrowerBtn" value="Confirm Delete" class="btn btn-danger" style="flex:1;">
                    <a href="viewBorrowers.php" class="btn btn-secondary" style="flex:1; text-align:center;">Cancel</a>
                </form>         
            </div>  
        </main>
    </div>
</body>
</html>