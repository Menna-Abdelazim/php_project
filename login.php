<?php
include "conn.php";
include "header.php";
include "navbar.php";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Email and Password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['password'];

            if (password_verify($password, $hashedPassword)) {
                $role = $user['role'];

                if ($role === "admin") {
                    header("Location: admin/view/layout.php");
                    exit();
                } else {
                    $userlogin = true;
                    setcookie("userlogin", $userlogin, time() + 60 * 60 * 24 * 3); 
                    header("Location: shop.php");
                    exit();
                }
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "Email not found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!-- HTML Form -->
<div class="card-body px-5 py-5" style="background-color: darkgray;">
    <h3 class="card-title text-left mb-3">Login</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label>Email *</label>
            <input type="email" class="form-control p_input" name="email" required>
        </div>
        <div class="form-group">
            <label>Password *</label>
            <input type="password" class="form-control p_input" name="password" required>
        </div>
        <div class="form-group d-flex align-items-center justify-content-between">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input"> Remember me
                </label>
            </div>
            <a href="forgetPassword.php" class="forgot-pass">Forgot password?</a>
        </div>
        <div class="text-center">
            <button type="submit" name="login" class="btn btn-primary btn-block enter-btn">Login</button>
        </div>
        <div class="d-flex">
            <button class="btn btn-facebook me-2 col">
                <i class="mdi mdi-facebook"></i> Facebook
            </button>
            <button class="btn btn-google col">
                <i class="mdi mdi-google-plus"></i> Google plus
            </button>
        </div>
        <p class="sign-up">Don't have an Account? <a href="signup.php">Sign Up</a></p>
    </form>
</div>

<?php include "footer.php"; ?>
