<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['volunteer'])) {
    header("Location: volunteer_login.php");
    exit();
}

$volunteer = $_SESSION['volunteer'];
$id = $volunteer['id'];

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['full_name']);
    $location = trim($_POST['location']);
    $message = trim($_POST['message']);

    $profile_image = $volunteer['profile_image'];
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);

        if ($check !== false && move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image = $file_name;
        }
    }

    $sql = "UPDATE volunteers SET full_name=?, location=?, message=?, profile_image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $location, $message, $profile_image, $id);

    if ($stmt->execute()) {
        $volunteer['full_name'] = $name;
        $volunteer['location'] = $location;
        $volunteer['message'] = $message;
        $volunteer['profile_image'] = $profile_image;
        $_SESSION['volunteer'] = $volunteer;
        echo "<script>alert('Profile updated successfully!'); window.location.reload();</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Volunteer Profile - MLAPSE</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'components/Navigation.php'; ?>

<div class="container profile-section">
    <div class="profile-card">
        <h2>My Volunteer Profile</h2>
        <img src="uploads/<?php echo htmlspecialchars($volunteer['profile_image']); ?>" alt="Profile Picture" class="profile-photo">

        <form action="" method="POST" enctype="multipart/form-data" class="volunteer-form">
            <label for="profile_image" class="file-label">Change Profile Picture</label>
            <input type="file" name="profile_image" accept="image/*">

            <input type="text" name="full_name" value="<?php echo htmlspecialchars($volunteer['full_name']); ?>" required>
            <input type="text" name="location" value="<?php echo htmlspecialchars($volunteer['location']); ?>" placeholder="Your Location">
            <textarea name="message" placeholder="Short bio or message" rows="4"><?php echo htmlspecialchars($volunteer['message']); ?></textarea>

            <button type="submit" class="btn-primary">Update Profile</button>
            <a href="volunteer_logout.php" class="logout-btn">Log Out</a>
        </form>
    </div>
</div>

<?php include 'components/Footer.php'; ?>

</body>
</html>
