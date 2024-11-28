<?php
// Подключение к базе данных
$dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Обработка формы добавления комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);

    if (!empty($author) && !empty($content)) {
        // Использование подготовленного запроса
        $stmt = $pdo->prepare('INSERT INTO comments (author, content) VALUES (:author, :content)');
        $stmt->execute([
            ':author' => htmlspecialchars($author, ENT_QUOTES, 'UTF-8'),
            ':content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
        ]);
        // Перенаправление, чтобы избежать повторной отправки формы
        header('Location: comments.php');
        exit;
    } else {
        $error = 'Пожалуйста, заполните все поля.';
    }
}

// Получение списка комментариев
$comments = $pdo->query('SELECT * FROM comments ORDER BY created_at DESC')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комментарии</title>
</head>
<body>
    <h1>Комментарии</h1>

    <!-- Список комментариев -->
    <?php if ($comments): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <strong><?= htmlspecialchars($comment['author'], ENT_QUOTES, 'UTF-8') ?></strong> 
                    <em>(<?= $comment['created_at'] ?>)</em>: 
                    <?= nl2br(htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Комментариев пока нет.</p>
    <?php endif; ?>

    <!-- Форма добавления комментария -->
    <h2>Добавить комментарий</h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form action="comments.php" method="post">
        <p>
            <label for="author">Имя:</label><br>
            <input type="text" name="author" id="author" required>
        </p>
        <p>
            <label for="content">Комментарий:</label><br>
            <textarea name="content" id="content" rows="5" required></textarea>
        </p>
        <p>
            <button type="submit">Добавить</button>
        </p>
    </form>
</body>
</html>
