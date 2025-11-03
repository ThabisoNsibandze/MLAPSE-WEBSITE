<?php
session_start();
include 'db.php';
include 'components/Navigation.php';

$posts = [];
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feeds - MLAPSE</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container feeds-container">
        <h2>Community Feeds</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="post-form">
                <h3>Create New Post</h3>
                <form action="create_post.php" method="POST">
                    <div class="form-group">
                        <label for="post_title">Title</label>
                        <input type="text" id="post_title" name="post_title" required>
                    </div>
                    <div class="form-group">
                        <label for="post_content">Content</label>
                        <textarea id="post_content" name="post_content" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="posts-list">
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-meta">Posted by Admin on <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts available yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
