<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

$searchQuery = isset($_GET['searchQuery']) ? trim($_GET['searchQuery']) : '';

if ($searchQuery !== '') {
    $borrowers = searchBorrowers($pdo, $searchQuery);
    logActivity($pdo, $_SESSION['username'], 'SEARCH', 'Borrower', "Searched for borrowers with keyword term: '" . $searchQuery . "'");
} else {
    $borrowers = getAllBorrowers($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - View Borrowers</title>
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
                <h1>Borrowers Registry</h1>
                
                <form action="viewBorrowers.php" method="GET" style="display:flex; gap:10px; padding:0; background:transparent; box-shadow:none; max-width:400px; margin:0;">
                    <input type="search" name="searchQuery" placeholder="Search target profile..." value="<?php echo sanitize($searchQuery); ?>">
                    <input type="submit" value="Search" style="padding: 10px 15px;">
                    <?php if ($searchQuery !== ''): ?>
                        <a href="viewBorrowers.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Created By</th>
                            <th>Last Modification</th>
                            <th>Action Set</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($borrowers) > 0): ?>
                            <?php foreach ($borrowers as $row) { ?>
                            <tr>
                                <td style="font-weight: 600;"><?php echo sanitize($row['first_name'] . " " . $row['last_name']); ?></td>
                                <td><?php echo sanitize($row['contact_number']); ?></td>
                                <td><span class="badge" style="background-color: #1e293b;"><?php echo sanitize($row['added_by']); ?></span></td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo $row['last_updated']; ?></td>
                                <td>
                                    <div class="action-links">
                                        <a href="viewBooks.php?borrower_id=<?php echo sanitize($row['borrower_id']); ?>" class="btn btn-primary">Books</a>
                                        <a href="editBorrower.php?borrower_id=<?php echo sanitize($row['borrower_id']); ?>" class="btn btn-secondary">Edit</a>
                                        <a href="deleteBorrower.php?borrower_id=<?php echo sanitize($row['borrower_id']); ?>" class="btn btn-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b;">No matching records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>