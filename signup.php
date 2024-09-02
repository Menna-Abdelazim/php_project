<?php
include "conn.php";
include "header.php";
include "navbar.php";

if (isset($_POST['signup'])) {
    $userName = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $errors = [];

    if (empty($userName)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 5) {
        $errors[] = "Password must be more than 5 characters.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!is_numeric($phone)) {
        $errors[] = "Phone number must be numeric.";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $userName, $email, $hashedPassword, $phone, $address);

        if ($stmt->execute()) {
            echo "You have successfully signed up.";
            header("Location: login.php");
            exit();
        } else {
            echo "Sign-up failed: " . $stmt->error;
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}

$conn->close();
?>

<!-- HTML Form -->
<div class="card-body px-5 py-5" style="background-color:darkgray;">
    <h3 class="card-title text-left mb-3">Register</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control p_input" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control p_input" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control p_input" name="password" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control p_input" name="phone" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control p_input" name="address" required>
        </div>
        <div class="text-center">
            <button type="submit" name="signup" class="btn btn-primary btn-block enter-btn">Signup</button>
        </div>
        <p class="sign-up text-center">Already have an Account? <a href="login.php">Login</a></p>
        <p class="terms">By creating an account you are accepting our <a href="#">Terms & Conditions</a></p>
    </form>
</div>

<?php include "footer.php"; ?>
