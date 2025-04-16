<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup-form/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
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
        <form action="../includes/signUp.inc.php" method="post">
            <!-- Name -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name">
                <p class="information" id="name-info"></p>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname">
                <p class="information" id="lastname-info"></p>
            </div>

            <!-- Login -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
                <p class="information" id="name-info"></p>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="male"> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="female"> Female
                    </label>
                </div>
                <p class="information" id="gender-info"></p>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <p class="information" id="password-info"></p>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password">
                <p class="information" id="confirm-password-info"></p>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>

            <!-- Already Registered Link -->
            <div class="form-group">
                <p class="footer-text">Already registered? <a href="signin.php">Sign in</a></p>
            </div>
        </form>
    </div>
</body>
</html>