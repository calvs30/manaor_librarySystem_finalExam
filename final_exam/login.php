<?php require_once 'core/dbConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System - Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="split-container">
        <div class="left-panel">
            <div class="background-image"></div>
        </div>
        
        <div class="right-panel">
            <div class="form-wrapper">
                <form action="core/handleForms.php" method="POST">
                    <h1>Sign In</h1>
                    <p class="subtitle">Enter your details to get started.</p>
                    
                    <?php if (isset($_SESSION['message'])) { ?>
                        <div class="error-message"><?php echo $_SESSION['message']; ?></div>
                    <?php unset($_SESSION['message']); } ?>
                    
                    <p>
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username">
                    </p>
                    <p>
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password">
                    </p>
                    
                    <input type="submit" name="loginUserBtn" value="Sign In">
                    
                    <p class="account-prompt">
                        New admin? <a href="register.php">Register here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>