<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'database/database.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);


    // Query to check the user
    $loginSql = "SELECT u.*, r.role FROM users u JOIN role r ON u.role_id = r.id WHERE u.username = :username";
    $stmt = $db->prepare($loginSql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $row['password'])) {
        session_regenerate_id(true); // Regenerate session ID to prevent fixation
        $_SESSION['user_id'] = $row['id'];
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = "Invalid credentials. Please try again.";
    }
}
$db = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Yayasan Sa'adah Martapura | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/dist/css/adminlte.css">
</head>
<body class="login-page bg-body-secondary">
    <?php if (isset($errorMessage)) { echo '<p class="alert alert-danger text-center">'.$errorMessage.'</p>'; } ?>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <img src="assets/image/logoats3.png" alt="logo.png" class="mx-auto d-block">
                <h2 class="mb-0 text-center">Sistem Manajemen Informasi</h2>
            </div>
            <div class="card-body login-card-body">
                <form method="post">
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="username" type="text" class="form-control" placeholder="Masukkan username" required>
                            <label for="username">Username</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="password" type="password" class="form-control" placeholder="Masukkan password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mx-auto d-block">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="assets/dist/js/adminlte.js"></script>
</body>
</html>