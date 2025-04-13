<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

define('GUESTBOOK_FILE', 'guestbook.csv');
$errors = [];

/**
 * Функция для рендеринга комментариев из guestbook.csv
 */
function renderGuestbookComments($page = 1, $perPage = 5)
{
    if (!file_exists(GUESTBOOK_FILE)) {
        echo "<p>Коментарів поки немає.</p>";
        return;
    }

    $comments = array_reverse(array_map('str_getcsv', file(GUESTBOOK_FILE)));

    // Пагинация
    $totalComments = count($comments);
    $totalPages = ceil($totalComments / $perPage);
    $offset = ($page - 1) * $perPage;
    $commentsToShow = array_slice($comments, $offset, $perPage);

    foreach ($commentsToShow as $comment) {
        list($email, $name, $text, $date) = $comment;
        echo "<div class='border p-2 my-2'>
                <strong>$name ($email)</strong> <em>$date</em><br>
                <p>$text</p>
              </div>";
    }

    // Навигация по страницам
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i' class='btn btn-sm btn-secondary mx-1'>$i</a>";
    }
    echo "</div>";
}

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $date = date('Y-m-d H:i:s');

    // Валидация
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email";
    }
    if (empty($name)) {
        $errors[] = "Им'я не може бути порожнім";
    }
    if (empty($text)) {
        $errors[] = "Текст коментаря не може бути порожнім";
    }

    // Если нет ошибок, записываем в файл
    if (!$errors) {
        $file = fopen(GUESTBOOK_FILE, "a");
        fputcsv($file, [$email, $name, $text, $date]);
        fclose($file);

        // Перезагрузка страницы после отправки
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Определяем текущую страницу пагинации
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
    <!-- navbar menu -->
    <?php require_once 'sectionNavbar.php'; ?>

    <br>

    <!-- guestbook section -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <!-- TODO: create guestBook html form -->
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

                    <!-- Вывод ошибок валидации -->
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
                    <!-- TODO: render guestBook comments -->
                    <?php renderGuestbookComments($page); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
