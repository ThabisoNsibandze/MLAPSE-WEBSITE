<?php
include 'db_connect.php';
include 'components/Navigation.php';

$result = $conn->query("SELECT * FROM volunteers ORDER BY joined_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Volunteers - MLAPSE</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container volunteers-section">
    <h2>Our Volunteers</h2>
    <p>Meet our passionate and dedicated community members.</p>

    <div class="volunteer-grid">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="volunteer-card">
            <img src="uploads/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Volunteer Photo">
            <h3><?php echo htmlspecialchars($row['full_name']); ?></h3>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p><strong>Joined:</strong> <?php echo date("F j, Y", strtotime($row['joined_at'])); ?></p>
            <?php if (!empty($row['message'])): ?>
                <p class="message">"<?php echo htmlspecialchars($row['message']); ?>"</p>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'components/Footer.php'; ?>
</body>
</html>

<?php $conn->close(); ?>
