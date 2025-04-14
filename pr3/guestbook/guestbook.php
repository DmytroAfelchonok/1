<?php
session_start();

require_once 'config.php';

$errors = [];

$db = mysqli_connect(
    $config['host'],
    $config['user'],
    $config['pass'],
    $config['name']
);

if (!$db) {
    die("Помилка підключення до бази даних: " . mysqli_connect_error());
}

function renderGuestbookComments($db, $page = 1, $perPage = 5)
{
    $offset = ($page - 1) * $perPage;
    $query = "SELECT * FROM comments ORDER BY id DESC LIMIT $offset, $perPage";
    $result = mysqli_query($db, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<p>Коментарів поки немає.</p>";
        return;
    }

    while ($comment = mysqli_fetch_assoc($result)) {
        echo "<div class='border p-2 my-2'>
                <strong>{$comment['name']} ({$comment['email']})</strong> <em>{$comment['date']}</em><br>
                <p>{$comment['text']}</p>
              </div>";
    }

    $countQuery = "SELECT COUNT(*) as total FROM comments";
    $countResult = mysqli_query($db, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalPages = ceil($countRow['total'] / $perPage);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i' class='btn btn-sm btn-secondary mx-1'>$i</a>";
    }
    echo "</div>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $date = date('Y-m-d H:i:s');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email";
    }
    if (empty($name)) {
        $errors[] = "Ім'я не може бути порожнім";
    }
    if (empty($text)) {
        $errors[] = "Текст коментаря не може бути порожнім";
    }

    if (!$errors) {
        $query = "INSERT INTO comments (email, name, text, date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $email, $name, $text, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостьова книга</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <?php require_once 'sectionNavbar.php'; ?>
    <br>

    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Ім'я:</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Коментар:</label>
                            <textarea name="text" id="text" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Відправити</button>
                    </form>

                    <?php if ($errors): ?>
                        <div class="alert alert-danger mt-3">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="card card-primary">
        <div class="card-header bg-body-secondary text-dark">
            Коментарі
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <?php renderGuestbookComments($db, $page); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
