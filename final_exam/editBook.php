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
    <title>Library System - Edit Book</title>
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
                <h1>Edit Borrowed Book Information</h1>
            </div>

            <div class="card" style="max-width: 650px; margin: 0 auto;">
                <h2>Book Information</h2>
                <?php $getBookByID = getBookByID($pdo, $_GET['book_id']); ?>
                <form action="core/handleForms.php?book_id=<?php echo $_GET['book_id']; ?>&borrower_id=<?php echo $_GET['borrower_id']; ?>" method="POST">
                    <p>
                        <label for="bookTitle">Book Title</label> 
                        <input type="text" name="bookTitle" value="<?php echo sanitize($getBookByID['book_title']); ?>" required>
                    </p>
                    <p>
                        <label for="bookCategory">Category Classification</label> 
                        <input type="text" name="bookCategory" value="<?php echo sanitize($getBookByID['book_category']); ?>" required>
                    </p>
                    
                    <div style="display: flex; gap: 12px; margin-top: 25px;">
                        <input type="submit" name="editBookBtn" value="Save Changes" style="flex: 1;">
                        <a href="viewBooks.php?borrower_id=<?php echo $_GET['borrower_id']; ?>" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>