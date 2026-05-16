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
    <title>Library System - Return Book</title>
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
                <h1>Process Borrow Return</h1>
            </div>

            <div class="card" style="max-width: 550px; margin: 0 auto; border-top: 4px solid #1a6d32;">
                <h2>Confirm Book Return</h2>
                <p style="margin-bottom: 20px; color: #64748b;">Please confirm that this item is safely back in physical stacks before logging return verification.</p>
                
                <?php $book = getBookByID($pdo, $_GET['book_id']); ?>
                <div style="background-color: #f8fafc; padding: 18px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #1a6d32;">
                    <p style="margin-bottom: 8px;"><strong>Book Title:</strong> <?php echo sanitize($book['book_title']); ?></p>
                    <p style="margin-bottom: 0;"><strong>Borrowed By:</strong> <?php echo sanitize($book['borrower_name']); ?></p>
                </div>

                <form action="core/handleForms.php?book_id=<?php echo $_GET['book_id']; ?>&borrower_id=<?php echo $_GET['borrower_id']; ?>" method="POST" style="display:flex; gap:12px; padding:0; box-shadow:none; background:none;">
                    <input type="submit" name="deleteBookBtn" value="Confirm Return" class="btn btn-primary" style="flex:1;">
                    <a href="viewBooks.php?borrower_id=<?php echo $_GET['borrower_id']; ?>" class="btn btn-secondary" style="flex:1; text-align:center;">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>