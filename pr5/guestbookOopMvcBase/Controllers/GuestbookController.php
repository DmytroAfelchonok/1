<?php

namespace guestbook\Controllers;

class GuestbookController
{
    public function execute()
    {
        session_start();
        $aConfig = require 'config.php';
        $infoMessage = '';

        // 1. Подключаемся к БД через PDO
        $pdo = new \PDO(
            "mysql:dbname={$aConfig['name']};host={$aConfig['host']};charset={$aConfig['charset']}",
            $aConfig['user'],
            $aConfig['pass']
        );

        // 2. Если отправлена форма
        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['text'])) {
            $aComment = $_POST;
            $aComment['date'] = date('m.d.Y');

            // 3. Подготавливаем запрос на вставку (prepared statement для безопасности)
            $stmt = $pdo->prepare("INSERT INTO comments (email, name, text, date) VALUES (:email, :name, :text, :date)");
            $stmt->execute([
                ':email' => $aComment['email'],
                ':name'  => $aComment['name'],
                ':text'  => $aComment['text'],
                ':date'  => $aComment['date']
            ]);

        } elseif (!empty($_POST)) {
            $infoMessage = 'Заполните поля формы!';
        }

        // 4. Получаем все комментарии
        $pdoStatement = $pdo->query('SELECT * FROM comments');
        $aComments = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);

        // 5. Передаем в представление
        $this->renderView([
            'infoMessage' => $infoMessage,
            'comments' => $aComments
        ]);
    }

    public function renderView($arguments = [])
    {
        $infoMessage = $arguments['infoMessage'] ?? '';
        $comments = $arguments['comments'] ?? [];

        ?>

        <!DOCTYPE html>
        <html>

        <?php require_once 'ViewSections/sectionHead.php' ?>

        <body>

        <div class="container">

            <?php require_once 'ViewSections/sectionNavbar.php' ?>
            <br>

            <div class="card card-primary">
                <div class="card-header bg-primary text-light">
                    Guestbook form
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <form method="post" name="form" class="fw-bold">
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName">Name</label>
                                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputText">Text</label>
                                    <textarea name="text" class="form-control" id="exampleInputText" placeholder="Enter text" required></textarea>
                                </div><br>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                            </form>
                            <br>
                        </div>

                        <?php
                        if ($infoMessage) {
                            echo "<span style='color:red'>$infoMessage</span>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <br>

            <div class="card card-primary">
                <div class="card-header bg-body-secondary text-dark">
                    Comments
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            foreach ($comments as $comment) {
                                echo htmlspecialchars($comment['name']) . '<br>';
                                echo htmlspecialchars($comment['email']) . '<br>';
                                echo htmlspecialchars($comment['text']) . '<br>';
                                echo htmlspecialchars($comment['date']) . '<br>';
                                echo '<hr>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </body>
        </html>

        <?php
    }
}
