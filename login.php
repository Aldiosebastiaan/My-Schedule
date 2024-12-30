<?php
// Memulai session
session_start();

// Menyertakan file koneksi
include "connect.php";

// Redirect jika user sudah login
if (isset($_SESSION['username'])) {
    header("location:admin.php");
    exit();
}

// Pesan error default
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['user']);
    $password = md5($_POST['passw']); // Tetap menggunakan MD5

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $error_message = "Username atau Password tidak boleh kosong.";
    } else {
        // Menggunakan prepared statement
        $stmt = $conn->prepare("SELECT username FROM user WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Verifikasi login
        if ($row) {
            $_SESSION['username'] = $row['username'];
            header("location:admin.php");
            exit();
        } else {
            $error_message = "Username atau Password salah.";
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | My Schedule</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="icon" href="img/logo.png" />
</head>
<body class="bg-danger-subtle">
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12 col-sm-8 col-md-6 m-auto">
            <div class="card border-0 shadow rounded-5">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle h1 display-4"></i>
                        <p>My Schedule</p>
                        <hr />
                    </div>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <input
                          type="text"
                          name="user"
                          class="form-control my-4 py-2 rounded-4"
                          placeholder="Username"
                          value="<?= htmlspecialchars($_POST['user'] ?? '') ?>"
                        />
                        <input
                          type="password"
                          name="passw"
                          class="form-control my-4 py-2 rounded-4"
                          placeholder="Password"
                        />
                        <div class="text-center my-3 d-grid">
                            <button class="btn btn-danger rounded-4">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
  crossorigin="anonymous"
></script>
</body>
</html>
