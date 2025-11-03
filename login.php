<!-- login.php -->
<?php
session_start();
include 'db.php';

$message = '';
$message_type = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $message = "Email and password are required.";
        $message_type = "error";
    } else {
        // Check if connection exists
        if (!$conn) {
            $message = "Database connection failed. Please check your configuration.";
            $message_type = "error";
        } else {
            // Fetch user from database
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $stmt->close();
                        header("Location: index.php");
                        exit();
                    } else {
                        $message = "Invalid email or password.";
                        $message_type = "error";
                    }
                } else {
                    $message = "Invalid email or password.";
                    $message_type = "error";
                }
                $stmt->close();
            } else {
                $message = "Database error: " . $conn->error;
                $message_type = "error";
            }
        }
    }
}

include 'components/Navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MLAPSE</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container auth-container">
        <div class="auth-form">
            <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>" style="padding: 12px; border-radius: 6px; margin-bottom: 24px; text-align: center; <?php echo $message_type == 'error' ? 'background-color: rgba(255, 0, 0, 0.1); border: 1px solid #ff6b6b; color: #ff6b6b;' : 'background-color: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50;'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email"><i class="far fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <p style="margin-top: 24px; text-align: center; color: rgba(255, 255, 255, 0.6);">
                Don't have an account? <a href="register.php" style="color: #4caf50; font-weight: 500;">Register here</a>
            </p>
            <p style="margin-top: 12px; text-align: center; font-size: 14px; color: rgba(255, 255, 255, 0.4);">
                Demo: admin@mlapse.org / admin123
            </p>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

<!-- ============================================ -->
<!-- register.php -->
<?php
session_start();
include 'db.php';

$message = '';
$message_type = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $message = "All fields are required!";
        $message_type = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long!";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
        $message_type = "error";
    } else {
        // Check if connection exists
        if (!$conn) {
            $message = "Database connection failed. Please check your configuration.";
            $message_type = "error";
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            if ($stmt) {
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $message = "Username or Email already exists!";
                    $message_type = "error";
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new user into the database
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("sss", $username, $email, $hashed_password);

                        if ($stmt->execute()) {
                            $message = "Registration successful! You can now login.";
                            $message_type = "success";
                        } else {
                            $message = "Error: " . $stmt->error;
                            $message_type = "error";
                        }
                    } else {
                        $message = "Database error: " . $conn->error;
                        $message_type = "error";
                    }
                }
                $stmt->close();
            } else {
                $message = "Database error: " . $conn->error;
                $message_type = "error";
            }
        }
    }
}

include 'components/Navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MLAPSE</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container auth-container">
        <div class="auth-form">
            <h2><i class="fas fa-user-plus"></i> Register</h2>
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>" style="padding: 12px; border-radius: 6px; margin-bottom: 24px; text-align: center; <?php echo $message_type == 'error' ? 'background-color: rgba(255, 0, 0, 0.1); border: 1px solid #ff6b6b; color: #ff6b6b;' : 'background-color: rgba(76, 175, 80, 0.1); border: 1px solid #4caf50; color: #4caf50;'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($message_type == 'success'): ?>
                        <p style="margin-top: 12px;"><a href="login.php" style="color: #4caf50; text-decoration: underline;">Click here to login</a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" required placeholder="Choose a username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="email"><i class="far fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required placeholder="Create a password (min 6 characters)">
                </div>
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
            <p style="margin-top: 24px; text-align: center; color: rgba(255, 255, 255, 0.6);">
                Already have an account? <a href="login.php" style="color: #4caf50; font-weight: 500;">Login here</a>
            </p>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>