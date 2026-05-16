<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

$borrower_id = isset($_GET['borrower_id']) ? sanitize($_GET['borrower_id']) : '';
$b = getBorrowerByID($pdo, $borrower_id);

if (!$b) {
    echo "Borrower profile not found.";
    exit();
}

$searchQuery = isset($_GET['searchQuery']) ? trim($_GET['searchQuery']) : '';

if ($searchQuery !== '') {
    $books = searchBooks($pdo, $borrower_id, $searchQuery);
    logActivity($pdo, $_SESSION['username'], 'SEARCH', 'Book', "Searched for books under Borrower ID " . $borrower_id . " with query: '" . $searchQuery . "'");
} else {
    $books = getBooksByBorrower($pdo, $borrower_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Borrowed Books Registry</title>
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
                <h1>Books Borrowed By: <?php echo sanitize($b['first_name'] . " " . $b['last_name']); ?></h1>
                
                <form action="viewBooks.php" method="GET" style="display:flex; gap:10px; padding:0; background:transparent; box-shadow:none; max-width:400px; margin:0;">
                    <input type="hidden" name="borrower_id" value="<?php echo $borrower_id; ?>">
                    <input type="text" name="searchQuery" placeholder="Search title/category..." value="<?php echo sanitize($searchQuery); ?>">
                    <input type="submit" value="Search" style="padding:10px 15px;">
                    <?php if ($searchQuery !== ''): ?>
                        <a href="viewBooks.php?borrower_id=<?php echo $borrower_id; ?>" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card">
                <h2>Log a Borrowed Transaction</h2>
                <form action="core/handleForms.php?borrower_id=<?php echo sanitize($borrower_id); ?>" method="POST">
                    <p><label>Book Title</label><input type="text" name="bookTitle" required></p>
                    <p><label>Category Classification</label><input type="text" name="bookCategory" required></p>
                    <input type="submit" name="insertNewBookBtn" value="Add to Borrowed Book">
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Title Name</th>
                            <th>Category</th>
                            <th>Date Borrowed</th>
                            <th>Action Set</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($books) > 0): ?>
                            <?php foreach ($books as $row) { ?>
                            <tr>
                                <td style="font-weight:600;"><?php echo sanitize($row['book_title']); ?></td>
                                <td><?php echo sanitize($row['book_category']); ?></td>
                                <td style="color: var(--text-muted);"><?php echo $row['date_borrowed']; ?></td>
                                <td>
                                    <div class="action-links">
                                        <a href="editBook.php?book_id=<?php echo sanitize($row['book_id']); ?>&borrower_id=<?php echo sanitize($borrower_id); ?>" class="btn btn-secondary">Edit</a>
                                        <a href="deleteBook.php?book_id=<?php echo sanitize($row['book_id']); ?>&borrower_id=<?php echo sanitize($borrower_id); ?>" class="btn btn-danger">Return Book</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b;">No books match this system configuration.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>