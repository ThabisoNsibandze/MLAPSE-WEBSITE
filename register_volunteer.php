<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $message = trim($_POST['message']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash password

    $profile_image = "default.png";
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $file_name;
            }
        }
    }

    $sql = "INSERT INTO volunteers (full_name, email, password, phone, location, message, profile_image)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $location, $message, $profile_image);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location='volunteer_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteer Registration - MLAPSE</title>
<link rel="stylesheet" href="style.css">
<style>
/* ===== Volunteer Page Wrapper with Background ===== */
.volunteer-page-wrapper {
    min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 48px;
    position: relative;
    overflow: hidden;
}

/* ===== Background Image Layer ===== */
.volunteer-page-wrapper::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: url('assets/your-background.jpg') center/cover no-repeat;
    z-index: 0;
    opacity: 0.9; /* adjust between 0.7 and 1 to make it brighter/darker */
    animation: bgFade 20s ease-in-out infinite alternate;
}

/* Subtle fade animation for background */
@keyframes bgFade {
    0% { transform: scale(1) brightness(0.95); }
    100% { transform: scale(1.05) brightness(1.1); }
}

/* ===== Glass Effect Container ===== */
.volunteer-register {
    background: rgba(45, 45, 45, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
    padding: 50px;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    color: #fff;
    text-align: left;
    z-index: 2;
    position: relative;
}

/* ===== Form Headings ===== */
.volunteer-register h2 {
    color: #ffffff;
    text-align: center;
    margin-bottom: 20px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
}

/* ===== Form Controls ===== */
.volunteer-form .form-control {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px;
}

.volunteer-form label {
    font-weight: 500;
    color: rgba(255,255,255,0.9);
    margin-bottom: 6px;
}

.volunteer-form input,
.volunteer-form textarea {
    width: 100%;
    padding: 12px 14px;
    border: none;
    border-radius: 6px;
    outline: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 15px;
}

.volunteer-form input::placeholder,
.volunteer-form textarea::placeholder {
    color: rgba(255,255,255,0.6);
}

/* ===== File Input ===== */
.file-label {
    margin-top: 10px;
    display: block;
    color: rgba(255,255,255,0.9);
    margin-bottom: 6px;
}

input[type="file"] {
    background: rgba(255,255,255,0.05);
    border-radius: 6px;
    padding: 10px;
    color: #fff;
}

/* ===== Submit Button ===== */
.btn-primary {
    width: 100%;
    background-color: #4CAF50;
    color: #ffffff;
    border: none;
    padding: 14px;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
}

.btn-primary:hover {
    background-color: #43a047;
    transform: translateY(-2px);
}

/* ===== Responsive Design ===== */
@media (max-width: 768px) {
    .volunteer-page-wrapper {
        padding: 60px 20px;
    }

    .volunteer-register {
        padding: 30px 20px;
    }
}
</style>
</head>
<body>

<?php include 'components/Navigation.php'; ?>

<!-- Volunteer Page Wrapper -->
<div class="volunteer-page-wrapper">
    <div class="volunteer-register">
        <h2>Join Our Volunteers</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="volunteer-form">
            
            <div class="form-control">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="form-control">
                <label for="email">Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-control">
                <label for="password">Create Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-control">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" placeholder="Enter your phone number">
            </div>

            <div class="form-control">
                <label for="location">Location</label>
                <input type="text" name="location" placeholder="Your location">
            </div>

            <div class="form-control">
                <label for="message">Why do you want to volunteer?</label>
                <textarea name="message" rows="4" placeholder="Tell us why you want to volunteer..."></textarea>
            </div>

            <div class="form-control">
                <label for="profile_image" class="file-label">Upload Profile Picture</label>
                <input type="file" name="profile_image" accept="image/*">
            </div>

            <button type="submit" class="btn-primary">Register Now</button>
        </form>
    </div>
</div>

<?php include 'components/Footer.php'; ?>

</body>
</html>
