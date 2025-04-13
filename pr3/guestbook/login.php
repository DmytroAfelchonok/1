<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

// TODO 2: ROUTING
if (!empty($_SESSION['auth'])) {
    header('Location: /admin.php');
    die;
}

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

// 1. Create empty $infoMessage
$infoMessage = '';

// 2. handle form data
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // 3. Check that user has already existed
    $sUsers = file_get_contents("users.csv");
    $aJsonsUsers = explode("\n", $sUsers);

    $isAlreadyRegistered = false;

    foreach ($aJsonsUsers as $jsonUser) {
        $aUser = json_decode($jsonUser, true);
        if (!$aUser) break;

        foreach ($aUser as $email => $password) {
            if (($email == $_POST['email']) && ($password == $_POST['password'])) {
                $isAlreadyRegistered = true;

                $_SESSION['auth'] = true;
                // $_SESSION['email'] = $_POST['email'];

                header("Location: admin.php");
                die;
            }
        }
    }

    if (!$isAlreadyRegistered) {
        $infoMessage = "Такого пользователя не существует. Перейдите на страницу регистрации. ";
        $infoMessage .= "<a href='register.php'>Страница регистрации</a>";
    }

} elseif (!empty($_POST)) {
    $infoMessage = 'Заполните форму авторизации!';
}


?>


<!DOCTYPE html>
<html>

<?php require_once 'sectionHead.php' ?>

<body>

    <div class="container">

        <?php require_once 'sectionNavbar.php' ?>

        <br>

        <div class="card card-primary">
            <div class="card-header bg-primary text-light">
                Login form
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
                        <input type="submit" class="btn btn-primary" name="form"/>
                    </div>
                </form>

                <!-- TODO: render php data   -->
                <?php
                    if ($infoMessage) {
                        echo '<hr/>';
                        echo "<span style='color:red'>$infoMessage</span>";
                    }
                ?>

            </div>
        </div>
    </div>


</body>
</html>

