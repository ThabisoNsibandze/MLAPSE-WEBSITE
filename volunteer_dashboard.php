<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['volunteer_id'])) {
    echo "<script>alert('Please log in first.'); window.location='volunteer_login.php';</script>";
    exit;
}

$volunteer_id = $_SESSION['volunteer_id'];

// Fetch volunteer data
$sql = "SELECT * FROM volunteers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $message = trim($_POST['message']);

    $profile_image = $user['profile_image'];

    // Handle image upload
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);

        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Delete old image if not default
                if ($user['profile_image'] !== "default.png" && file_exists("uploads/" . $user['profile_image'])) {
                    unlink("uploads/" . $user['profile_image']);
                }
                $profile_image = $file_name;
            }
        }
    }

    // Update DB
    $sql_update = "UPDATE volunteers SET full_name=?, email=?, phone=?, location=?, message=?, profile_image=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssi", $full_name, $email, $phone, $location, $message, $profile_image, $volunteer_id);

    if ($stmt_update->execute()) {
        $_SESSION['volunteer_name'] = $full_name;
        $_SESSION['profile_image'] = $profile_image;
        echo "<script>alert('Profile updated successfully!'); window.location='volunteer_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteer Dashboard - MLAPSE</title>
<link rel="stylesheet" href="style.css">
<style>
.dashboard {
    max-width: 700px;
    margin: 50px auto;
    background: #1a1a1a;
    color: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 0 15px rgba(255,255,255,0.1);
    text-align: center;
}
.profile-image {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    margin: 10px 0;
    border: 3px solid #ff6600;
}
.file-input {
    display: none;
}
.change-pic-btn {
    background: none;
    color: #ff6600;
    border: none;
    cursor: pointer;
    text-decoration: underline;
    margin-top: 8px;
}
.dashboard form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 25px;
}
.dashboard input, .dashboard textarea {
    padding: 10px;
    border: none;
    border-radius: 5px;
    width: 100%;
}
.dashboard button {
    background-color: #ff6600;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
.logout {
    margin-top: 20px;
}
.logout a {
    color: #ff6600;
    text-decoration: none;
}
.logout a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<?php include 'components/Navigation.php'; ?>

<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?> 👋</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" 
             alt="Profile Image" class="profile-image" id="previewImage">
        <br>
        <label class="change-pic-btn" for="profile_image">Change Picture</label>
        <input type="file" name="profile_image" id="profile_image" class="file-input" accept="image/*" onchange="previewImage(event)">

        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
        <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>">
        <textarea name="message" rows="4"><?php echo htmlspecialchars($user['message']); ?></textarea>

        <button type="submit">Update Profile</button>
    </form>

    <div class="logout">
        <a href="logout.php">Log Out</a>
    </div>
</div>

<?php include 'components/Footer.php'; ?>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImage').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
