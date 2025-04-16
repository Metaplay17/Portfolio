<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <link rel="stylesheet" href="../css/password-recover-form/styles.css">
</head>
<body>
    <div class="container">
        <h1>Password Recovery</h1>
        <!-- First Form: Email Input -->
        <div class="form-container">
            <?php
                session_start();
                if(isset($_SESSION["id"])) 
                {
                    header('location: ../pages/todolist.php');
                }

                if (isset($_GET["errors"])) 
                { ?>
                    <h2><?php echo $_GET["errors"]?></h2>
                <?php 
                } 
            ?>
            <form action="../includes/recoverPassword.inc.php" method="post">
                <div class="form-group">
                    <p>Email</p>
                    <label class="information"></label>
                    <input type="password" id="email" name="email"value=<?php echo $_GET["email"]?>>
                </div>
                <div class="form-group">
                    <p>New Password</p>
                    <label class="information"></label>
                    <input type="password" id="new-password" name="new-password">
                </div>
                <div class="form-group">
                    <p>Confirm New Password</p>
                    <label class="information"></label>
                    <input type="password" id="confirm-password" name="confirm-password">
                </div>
                <div class="form-group">
                    <p>Confirming Code</p>
                    <label class="information"></label>
                    <input type="password" id="code" name="code" value=<?php echo $_GET["code"]?>>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>