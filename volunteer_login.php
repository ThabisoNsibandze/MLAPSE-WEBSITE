<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $sql = "SELECT * FROM volunteers WHERE email = ? AND phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['volunteer'] = $result->fetch_assoc();
        header("Location: volunteer_profile.php");
        exit();
    } else {
        echo "<script>alert('Invalid login details. Please check your email and phone.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteer Login - MLAPSE</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'components/Navigation.php'; ?>

<div class="container volunteer-register">
    <h2>Volunteer Login</h2>
    <form action="" method="POST" class="volunteer-form">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <button type="submit" class="btn-primary">Login</button>
    </form>
</div>

<?php include 'components/Footer.php'; ?>

</body>
</html>
