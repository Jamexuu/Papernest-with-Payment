<?php
require_once 'backend/Login.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = new Login();
    if ($login->isLoggedIn()) {
        echo '<div class="position-fixed top-0 start-0 w-100 d-flex justify-content-center z-5">
                <div class="alert alert-warning alert-dismissible fade show shadow-lg mt-5" role="alert" style="min-width:300px;">
                    You are already logged in!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>';
    } else {
        $result = $login->loginUser($_POST['email'], $_POST['password']);
        if ($result['success']) {
            $_SESSION['login_success'] = true;
            header('Location: index.php');
            exit;
        } else {
            echo $result['message'];
        }
    }
}

?>

<div class="row">
    <div class="col-12 p-0">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navbar">
            <div class="container-fluid d-flex p-0 flex-column justify-content-between">
                <div class="container d-flex w-100 align-items-center">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand fs-1 ms-2" href="index.php"
                        style="font-family: 'gilroy-bold'; color: var(--secondary-color);">papernest</a>
                    <div class="ms-auto d-flex">
                        <div class="btn d-none d-md-block" id="login" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            <i class="bi bi-person fs-4"></i>Login/Register
                        </div>
                        <div class="btn" onclick="window.location.href = 'cart.php';"><i
                                class="bi bi-cart fs-4"></i>Cart</div>
                    </div>
                </div>
                <div class="collapse navbar-collapse w-100" style="background-color: var(--primary-color);"
                    id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto justify-content-center w-100">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Non-Books</a>
                        </li>
                        <li class="nav-item d-block d-md-none">
                            <a class="nav-link" href="#" data-bs-toggle="modal"
                                data-bs-target="#loginModal">Login/Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="loginModalLabel">Login/Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container p-4 mb-4" style="background-color: var(--secondary-color)">
                            Welcome to papernest! Please login or register to continue.
                        </div>
                        <div class="container">
                            <form action="" method="post">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" placeholder="Enter your Email" required class="form-control"
                                    id="email" name="email">

                                <label for="password" class="form-label fw-bold mt-3">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password"
                                        placeholder="Enter your Password" name="password">
                                    <button class="btn border bg-light" type="button" id="showPassword"
                                        onclick="window.showPassword()">
                                        <i class="bi bi-eye" id="showPasswordIcon"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn w-100 mt-5"
                                    style="background-color: var(--primary-color); color: white;">Login</button>
                            </form>
                            <div class="text-center pb-4 border-bottom">
                                <a href="" class=" text-decoration-none" style="color: var(--primary-color);">Forgot
                                    password?</a>
                            </div>
                            <div class="p p-3">
                                Don't have an account?
                                <a href="register.php" class="text-decoration-none"
                                    style="color: var(--primary-color);">Register now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.showPassword = function () {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('showPasswordIcon');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>