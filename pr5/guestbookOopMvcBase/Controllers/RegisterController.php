<?php

namespace guestbook\Controllers;

class RegisterController {

    // TODO 3.raw: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3.raw) transforming data
    public function execute() {

        if (!empty($_SESSION['auth'])) {
            header('Location: /admin');
            die;
        }

        // 1. Create empty $infoMessage
        $infoMessage = '';

        // 2. handle form data
        if (!empty($_POST['email']) && !empty($_POST['password'])) {

            $aConfig = require_once 'config.php';

            // 3.raw. Check that user has already existed
            $pdo = new \PDO("mysql:dbname={$aConfig['name']};host={$aConfig['host']};charset={$aConfig['charset']}", $aConfig['user']);

            $pdoStatement = $pdo->query("SELECT * FROM users WHERE `email`='".$_POST['email'] . "' AND `password`=" . "'". sha1($_POST['password']). "'");
            $user = $pdoStatement->fetch(\PDO::FETCH_ASSOC);

            if (!empty($user)) {
                $infoMessage = "Такой пользователь уже существует! Перейдите на страницу входа. ";
                $infoMessage .= "<a href='/login'>Страница входа</a>";
            }

            if (empty($user)) {
                // 4. Create new user
                $pdo->query("INSERT INTO `users` (`email`, `password`, `created_at`) VALUES ('".$_POST['email']."', '". sha1($_POST['password']) ."', '" .date('Y-m-d H:i:s')."')");

                header('Location: /login');
                die;
            }

        } elseif (!empty($_POST)) {
            $infoMessage = 'Заполните форму регистрации!';
        }

        $arguments = [
            'infoMessage' => $infoMessage
        ];

        $this->renderView($arguments);
    }

    // TODO 4: RENDER: 1) view (html) 2) data (from php)
    public function renderView($arguments = []) {
        ?>
            <!DOCTYPE html>
            <html>

            <?php require_once 'ViewSections/sectionHead.php' ?>

            <body>

            <div class="container">

                <?php require_once 'ViewSections/sectionNavbar.php' ?>

                <br>

                <div class="card card-primary">
                    <div class="card-header bg-success text-light">
                        Register form
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email"/>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password"/>
                            </div>
                            <br>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" name="formRegister"/>
                            </div>
                        </form>

                        <!-- TODO: render php data   -->
                        <?php
                        if ($arguments['infoMessage']) {
                            echo '<hr/>';
                            echo "<span style='color:red'>{$arguments['infoMessage']}</span>";
                        }
                        ?>

                    </div>

                </div>
            </div>

            </body>
            </html>
        <?php
    }
}