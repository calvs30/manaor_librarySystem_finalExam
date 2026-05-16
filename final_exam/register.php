<?php require_once 'core/dbConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System - Register</title>
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
                    <h1>Create Account</h1>
                    <p class="subtitle">Register your admin details.</p>

                    <?php if (isset($_SESSION['message'])) { ?>
                        <div class="error-message" style="color: #e74c3c; background-color: #fceae9; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #e74c3c;">
                            <?php echo $_SESSION['message']; ?>
                        </div>
                    <?php unset($_SESSION['message']); } ?>
                    
                    <p>
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter desired username">
                    </p>
                    
                    <p>
                        <label>Password</label>
                        <input type="password" 
                               name="password"
                               placeholder="Enter your password"
                               required
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters">
                    </p>
                    
                    <input type="submit" name="registerUserBtn" value="Sign Up">
                    
                    <p class="account-prompt">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>