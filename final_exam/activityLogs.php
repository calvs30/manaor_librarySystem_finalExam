<?php
require_once 'core/dbConfig.php';
require_once 'core/functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$logs = getAllLogs($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Activity Logs</title>
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
                <li><a href="viewBorrowers.php"><ion-icon name="document-text-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Borrowers Registry</a></li>
                <li class="active"><a href="activityLogs.php"><ion-icon name="today-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>System Activity Logs</a></li>
            </ul>
            <div class="sidebar-footer">
                <div class="admin-profile">Logged Admin: <span><?php echo htmlspecialchars($_SESSION['username']); ?></span></div>
                <a href="core/handleForms.php?logoutAUser=1" class="btn btn-danger" style="width:100%;"><ion-icon name="log-out-outline" style="position:static; font-size:24px; opacity:1; margin-right:8px; vertical-align:middle;"></ion-icon>Log Out</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <button class="menu-toggle">☰</button>
                <h1>System Master Activity Logs</h1>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($logs) > 0): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo sanitize($log['log_id']); ?></td>
                                    <td style="font-weight: 600; color: #2563eb;"><?php echo sanitize($log['username']); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: 
                                            <?php 
                                                if($log['operation'] == 'CREATE') echo '#10b981';
                                                elseif($log['operation'] == 'UPDATE') echo '#f59e0b';
                                                elseif($log['operation'] == 'DELETE') echo '#ef4444';
                                                elseif($log['operation'] == 'SEARCH') echo '#3b82f6';
                                                else echo '#64748b';
                                            ?>;">
                                            <?php echo sanitize($log['operation']); ?>
                                        </span>
                                    </td>
                                    <td style="font-weight: 500;"><?php echo sanitize($log['entity_type']); ?></td>
                                    <td><?php echo sanitize($log['details']); ?></td>
                                    <td style="color: var(--text-muted); font-size:0.85rem;"><?php echo sanitize($log['date_performed']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #64748b;">No historical log traces found inside the database engine.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>