<?php
require_once 'backend/Register.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register = new Register();
    $result = $register->registerUser(
        $_POST['fullname'],
        $_POST['email'],
        $_POST['password'],
    );
    
    if ($result['success']) {
        header('Location: index.php');
        echo '<div class="alert alert-success">' . $result['message'] . 
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        exit;
    } else {
        echo '<div class="alert alert-danger">' . $result['message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papernest | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <?php include 'components/navbar.php'; ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <div class="container p-3 rounded-2" style="background-color: #eeee;">
                        <div class="h3 fw-bold">Create an Account</div>
                        <div class="h4 fw-semibold">Fill Personal Information</div>
                        <form action="" method="post" onsubmit="return validatePasswords();">
                            <label for="fullname" class="form-label fw-bold mt-3">Full Name</label>
                            <input type="text" placeholder="Enter your Full Name" required class="form-control"
                                id="fullname" name="fullname">

                            <label for="email" class="form-label fw-bold mt-3">Email</label>
                            <input type="email" placeholder="Enter your Email" required class="form-control"
                                id="registerEmail" name="email">

                            <label for="password" class="form-label fw-bold mt-3">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerPassword"
                                    placeholder="Enter your Password" name="password">
                                <button class="btn border bg-light" type="button" id="showRegisterPassword"
                                    onclick="window.showRegisterPassword()">
                                    <i class="bi bi-eye" id="showPasswordIcon"></i>
                                </button>
                            </div>

                            <label for="confirm-password" class="form-label fw-bold mt-3">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" placeholder="Re-enter your Password" required
                                    class="form-control" id="confirm-password">
                                <button class="btn border bg-light" type="button" id="showConfirmPassword"
                                    onclick="window.showConfirmPassword()">
                                    <i class="bi bi-eye" id="showConfirmPasswordIcon"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn w-100 mt-5"
                                style="background-color: var(--primary-color); color: white;">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'components/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script>
        window.showRegisterPassword = function () {
            const passwordInput = document.getElementById('registerPassword');
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

        window.showConfirmPassword = function () {
            const confirmPasswordInput = document.getElementById('confirm-password');
            const toggleIcon = document.getElementById('showConfirmPasswordIcon');
            if (confirmPasswordInput.type === "password") {
                confirmPasswordInput.type = "text";
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
            else {
                confirmPasswordInput.type = "password";
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        function validatePasswords() {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>