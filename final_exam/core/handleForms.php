<?php  
require_once 'functions.php';
require_once 'dbConfig.php';

// User Registration
if (isset($_POST['registerUserBtn'])) {
    $username = sanitize($_POST['username']); 
    $password = $_POST['password'];

    if (validatePassword($password)) {
        if (insertNewUser($pdo, $username, sha1($password))) {
            header("Location: ../login.php");
            exit();
        } else {
            $_SESSION['message'] = "Username already taken.";
            header("Location: ../register.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Password failed! Must be 8+ chars with Uppercase, Lowercase, and Numbers.";
        header("Location: ../register.php");
        exit();
    }
}

// User Login
if (isset($_POST['loginUserBtn'])) {
    $username = sanitize($_POST['username']); 
    $password = sha1($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $loginQuery = loginUser($pdo, $username, $password);
        if ($loginQuery) {
            logActivity($pdo, $_SESSION['username'], 'LOGIN', 'User System', 'User logged into the admin panel.');
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password.";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../login.php");
        exit();
    }
}

// User Logout
if (isset($_GET['logoutAUser'])) {
    if (isset($_SESSION['username'])) {
        logActivity($pdo, $_SESSION['username'], 'LOGOUT', 'User System', 'User logged out of the admin panel.');
        unset($_SESSION['username']);
    }
    header('Location: ../login.php');
    exit();
}


// Insert Borrower (CREATE)
if (isset($_POST['insertBorrowerBtn'])) {
    $fName = sanitize($_POST['firstName']);
    $lName = sanitize($_POST['lastName']);
    $address = sanitize($_POST['homeAddress']);
    $contact = sanitize($_POST['contactNumber']);
    $currentUser = $_SESSION['username'];

    if (insertBorrower($pdo, $fName, $lName, $address, $contact, $currentUser)) {
        logActivity($pdo, $currentUser, 'CREATE', 'Borrower', "Added new borrower record: " . $fName . " " . $lName);
        header("Location: ../index.php");
        exit();
    }
}

// Edit Borrower (UPDATE)
if (isset($_POST['editBorrowerBtn'])) {
    $borrower_id = sanitize($_GET['borrower_id']);
    $fName = sanitize($_POST['firstName']);
    $lName = sanitize($_POST['lastName']);
    $address = sanitize($_POST['homeAddress']);
    $contact = sanitize($_POST['contactNumber']);
    $currentUser = $_SESSION['username'];

    if (updateBorrower($pdo, $fName, $lName, $address, $contact, $currentUser, $borrower_id)) {
        logActivity($pdo, $currentUser, 'UPDATE', 'Borrower', "Updated borrower details for Borrower ID: " . $borrower_id . " (" . $fName . " " . $lName . ")");
        header("Location: ../index.php");
        exit();
    }
}

// Delete Borrower (DELETE)
if (isset($_POST['deleteBorrowerBtn'])) {
    $borrower_id = sanitize($_GET['borrower_id']);
    $currentUser = $_SESSION['username'];
    
    // Fetch data before removal to obtain historical naming info for logs
    $borrowerData = getBorrowerByID($pdo, $borrower_id);
    $fullName = $borrowerData ? ($borrowerData['first_name'] . ' ' . $borrowerData['last_name']) : "Unknown Target";

    if (deleteBorrower($pdo, $borrower_id)) {
        logActivity($pdo, $currentUser, 'DELETE', 'Borrower', "Deleted borrower: " . $fullName .  " and all matching borrowed books.");
        header("Location: ../index.php");
        exit();
    }
}

// Insert Book (CREATE)
if (isset($_POST['insertNewBookBtn'])) {
    $borrower_id = sanitize($_GET['borrower_id']);
    $bookTitle = sanitize($_POST['bookTitle']);
    $bookCategory = sanitize($_POST['bookCategory']);
    $currentUser = $_SESSION['username'];

    if (insertBook($pdo, $bookTitle, $bookCategory, $borrower_id)) {
        logActivity($pdo, $currentUser, 'CREATE', 'Book', "Registered a book attachment: '" . $bookTitle . "' [" . $bookCategory . "] to Borrower ID: " . $borrower_id);
        header("Location: ../viewBooks.php?borrower_id=" . $borrower_id);
        exit();
    }
}

// Edit Book (UPDATE)
if (isset($_POST['editBookBtn'])) {
    $borrower_id = sanitize($_GET['borrower_id']);
    $book_id = sanitize($_GET['book_id']);
    $bookTitle = sanitize($_POST['bookTitle']);
    $bookCategory = sanitize($_POST['bookCategory']);
    $currentUser = $_SESSION['username'];

    if (updateBook($pdo, $bookTitle, $bookCategory, $book_id)) {
        logActivity($pdo, $currentUser, 'UPDATE', 'Book', "Modified details on Book: " . "(ID: ". $book_id . ") " . "Title: '" . $bookTitle . "', Category: " . $bookCategory);
        header("Location: ../viewBooks.php?borrower_id=" . $borrower_id);
        exit();
    }
}

// Delete Book (DELETE)
if (isset($_POST['deleteBookBtn'])) {
    $borrower_id = sanitize($_GET['borrower_id']);
    $book_id = sanitize($_GET['book_id']);
    $currentUser = $_SESSION['username'];

    // Collect name structure prior to deleting reference
    $bookData = getBookByID($pdo, $book_id);
    $title = $bookData ? $bookData['book_title'] : "Unknown Volume";

    if (deleteBook($pdo, $book_id)) {
        logActivity($pdo, $currentUser, 'DELETE', 'Book', "Confirmed the returned book: '" . $title . "' from Borrower ID: " . $borrower_id);
        header("Location: ../viewBooks.php?borrower_id=" . $borrower_id);
        exit();
    }
}
?>