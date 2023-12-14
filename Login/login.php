<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap'>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<!-- partial:index.partial.html -->
<div class="wrapper">
    <?php
    if (isset($_POST["submit"])) {
        $fullName = $_POST["fullname"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $ID = $_POST["ID"];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $errors = array();

        if (empty($fullName) || empty($password) || empty($email) || empty($ID)) {
            array_push($errors, "All fields are required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long");
        }
        require_once "data.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            array_push($errors, "Email already exists!");
        }
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {

            $sql = "INSERT INTO users (full_name, password, email, ID) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $fullName, $passwordHash, $email, $ID);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
        }
    }
    ?>
    <form action="login.php" method="POST">
        <div class="login_box">
            <div class="login-header">
                <span>Login</span>
            </div>
            <div class="input_box">
                <input type="text" id="user" class="input-field" name="fullname" placeholder="Full Name" required>
                <label for="user" class="label">Username</label>
            </div>

            <div class="input_box">
                <input type="password" id="pass" class="input-field" name="password" placeholder="Password" required>
                <label for="pass" class="label">Password</label>
            </div>

            <div class="input_box">
                <input type="email" id="mail" class="input-field" name="email" placeholder="Mail" required>
                <label for="mail" class="label">Mail</label>
            </div>

            <div class="input_box">
                <input type="number" id="ssn" class="input-field" name="ID" placeholder="ID" required>
                <label for="ssn" class="label">ID</label>
            </div>

            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <div class="forgot">
                    <a href="#">Forgot password?</a>
                </div>
            </div>

            <div class="input_box">
                <input type="submit" class="input-submit" value="Login" name="submit">
            </div>
        </div>
    </form>
    <div class="register">
        <span>Don't have an account? <a href="#">Register</a></span>
    </div>
</div>

<!-- partial -->
</body>
</html>