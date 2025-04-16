<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../css/signin-form/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign In</h2>
        <?php
            session_start();
            if(isset($_SESSION["id"])) 
            {
                header('location: ../pages/todolist.php');
            }
            
            if (isset($_GET["errors"])) 
            { ?>
                <h2><?php echo $_GET["errors"]?></h2>
             <?php } 
        ?>
        <p class="information" id="signin-info"></p>
        <form action="../includes/signIn.inc.php" method="POST">
            <!-- Login (Email) -->
            <div class="form-group">
                <label for="email">Login or Email</label>
                <input type="text" id="email" name="email">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>

            <!-- Sign In Button -->
            <div class="form-group">
                <button type="submit">Sign In</button>
            </div>

            <!-- Haven't Registered Link -->
            <div class="form-group">
                <p>Haven't registered yet? <a href="signup.php">Sign up</a></p>
            </div>

            <!-- Forgot Password Link -->
            <div class="form-group">
                <p>Forgot password? <a href="recover-password.php">Remind</a></p>
            </div>
        </form>
    </div>
</body>
</html>