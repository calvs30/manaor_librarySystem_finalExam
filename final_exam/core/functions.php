<?php  
require_once 'dbConfig.php';

/**
 * Instead of using htmlspecialchars() dozens of times, I add a helper function to
 * simplify the process
*/
function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function validatePassword($password) {
    if (strlen($password) < 8) {
        return false;
    }

    $hasLower = false;
    $hasUpper = false;
    $hasNumber = false;

    for ($i = 0; $i < strlen($password); $i++) {
        if (ctype_lower($password[$i])) {
            $hasLower = true;
        } elseif (ctype_upper($password[$i])) {
            $hasUpper = true;
        } elseif (ctype_digit($password[$i])) {
            $hasNumber = true;
        }
    }

    return ($hasLower && $hasUpper && $hasNumber);
}

function insertNewUser($pdo, $username, $password) {
    $checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
    $stmt = $pdo->prepare($checkUserSql);
    $stmt->execute([$username]);
    if ($stmt->rowCount() == 0) {
        $sql = "INSERT INTO user_passwords (username,password) VALUES(?,?)";
        return $pdo->prepare($sql)->execute([$username, $password]);
    }
    return false;
}

function loginUser($pdo, $username, $password) {
    $sql = "SELECT * FROM user_passwords WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]); 
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        if ($password == $user['password']) {
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    return false;
}

function insertBorrower($pdo, $f_name, $l_name, $address, $contact, $added_by) {
    $sql = "INSERT INTO borrowers (first_name, last_name, home_address, contact_number, added_by) VALUES(?,?,?,?,?)";
    return $pdo->prepare($sql)->execute([$f_name, $l_name, $address, $contact, $added_by]);
}

function updateBorrower($pdo, $f_name, $l_name, $address, $contact, $added_by, $id) {
    $sql = "UPDATE borrowers SET first_name = ?, last_name = ?, home_address = ?, contact_number = ?, added_by = ? WHERE borrower_id = ?";
    return $pdo->prepare($sql)->execute([$f_name, $l_name, $address, $contact, $added_by, $id]);
}

function getAllBorrowers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM borrowers");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getBorrowerByID($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM borrowers WHERE borrower_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function deleteBorrower($pdo, $id) {
    $pdo->prepare("DELETE FROM books WHERE borrower_id = ?")->execute([$id]);
    return $pdo->prepare("DELETE FROM borrowers WHERE borrower_id = ?")->execute([$id]);
}

function insertBook($pdo, $title, $cat, $b_id) {
    $sql = "INSERT INTO books (book_title, book_category, borrower_id) VALUES (?,?,?)";
    return $pdo->prepare($sql)->execute([$title, $cat, $b_id]);
}

function getBooksByBorrower($pdo, $b_id) {
    $sql = "SELECT * FROM books WHERE borrower_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$b_id]);
    return $stmt->fetchAll();
}

function getBookByID($pdo, $id) {
    $sql = "SELECT books.*, CONCAT(borrowers.first_name,' ',borrowers.last_name) AS borrower_name 
            FROM books JOIN borrowers ON books.borrower_id = borrowers.borrower_id WHERE book_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateBook($pdo, $title, $cat, $id) {
    $sql = "UPDATE books SET book_title = ?, book_category = ? WHERE book_id = ?";
    return $pdo->prepare($sql)->execute([$title, $cat, $id]);
}

function deleteBook($pdo, $id) {
    return $pdo->prepare("DELETE FROM books WHERE book_id = ?")->execute([$id]);
}

//NEW ADDED FUNCTIONS FOR SEARCH & LOGGING


//Log System Activity
function logActivity($pdo, $username, $operation, $entity_type, $details) {
    $sql = "INSERT INTO activity_logs (username, operation, entity_type, details) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$username, $operation, $entity_type, $details]);
}


//Retrieve All Activity Logs
function getAllLogs($pdo) {
    $sql = "SELECT * FROM activity_logs ORDER BY date_performed DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

//Search Parent Entity (Borrowers)
function searchBorrowers($pdo, $search) {
    $sql = "SELECT * FROM borrowers WHERE first_name LIKE ? OR last_name LIKE ? OR home_address LIKE ? OR contact_number LIKE ?";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}


//Search Child Entity (Books) belonging to a specific Borrower
function searchBooks($pdo, $borrower_id, $search) {
    $sql = "SELECT * FROM books WHERE borrower_id = ? AND (book_title LIKE ? OR book_category LIKE ?)";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->execute([$borrower_id, $searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}

//Retrieves counts for dashboard KPI calculation metrics
function getDashboardMetrics($pdo) {
    $metrics = [
        'total_borrowers' => 0,
        'total_books' => 0,
        'total_returned' => 0
    ];
    
    try {
        // Count unique active borrowers
        $stmt1 = $pdo->query("SELECT COUNT(*) FROM borrowers");
        $metrics['total_borrowers'] = $stmt1->fetchColumn();
        
        // Count current active borrowed books
        $stmt2 = $pdo->query("SELECT COUNT(*) FROM books");
        $metrics['total_books'] = $stmt2->fetchColumn();
        
        // Count total books returned by filtering the activity log table for deleted book entries
        $stmt3 = $pdo->query("SELECT COUNT(*) FROM activity_logs WHERE operation = 'DELETE' AND entity_type = 'Book'");
        $metrics['total_returned'] = $stmt3->fetchColumn();
    } catch (PDOException $e) {
        // Fallback or handle missing logging tables gracefully
    }
    
    return $metrics;
}
?>