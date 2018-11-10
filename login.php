<?php include 'includes/header.php';?>

<div class="container">
    <div class="form-login">
        <h1 class="login-header">Login</h1>
        <form action="actions/login.php" method="POST">
            <input type="password" name="password" />
            <input class="btn" type="submit" value="Login" />
        </form>
    </div>
</div>
<?php include 'includes/footer.php';