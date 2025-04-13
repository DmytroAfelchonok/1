<!-- navigation block -->
<nav class="navbar navbar-expand-lg bg-body-secondary">
    <div class="container-fluid">

        <!-- logo and link to home page   -->
        <a class="navbar-brand" href="/">
            <span style="color: Dodgerblue;">
                <i class="fa-brands fa-php fa-2xl"></i>
            </span>
        </a>

        <!-- navbar small button collapse menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (!empty($_SESSION['auth'])) { ?>
                    <li class="nav-item">
                        <a href="admin.php" class="nav-link active" aria-current="page">Admin</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="guestbook.php">GuestBook</a>
                    </li>

                <?php } ?>
            </ul>

            <ul class="navbar-nav navbar-right">
                <?php if (!empty($_SESSION['auth'])) { ?>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link active" aria-current="page">Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link active" aria-current="page" href="#">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link active" aria-current="page" href="#">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
</nav>