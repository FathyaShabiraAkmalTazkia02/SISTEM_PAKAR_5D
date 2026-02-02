<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $sql = "SELECT * FROM admin 
            WHERE username='$username' AND password='$password' 
            LIMIT 1";
    $res = mysqli_query($koneksi, $sql);

    if ($res && mysqli_num_rows($res) == 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;
        header("Location: admin_home.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/login.png') no-repeat top center;
            background-size: cover;   /* supaya menyesuaikan layar */
        }
        .container {
            max-width:700px; margin:60px auto; background:#ffffffcc;
            padding:30px 40px; border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            text-align:center;
        }
        label { display:block; margin-top:10px; font-size:0.9rem; }
        input[type="text"], input[type="password"] {
            width:100%; padding:8px 10px; margin-top:4px;
            border:1px solid #ccc; border-radius:4px;
        }
        .btn {
            margin-top:15px; width:100%; padding:8px 0;
            background:#3498db; color:#fff; border:none;
            border-radius:4px; cursor:pointer;
        }
        .btn:hover { background:#2980b9; }
        .error { color:#c0392b; font-size:0.85rem; margin-top:10px; }
        .link { text-align:center; margin-top:10px; font-size:0.9rem; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login Admin</h2>
    <?php if ($error != '') { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>
    </form>
    <div class="link">
        <a href="index.php">Kembali</a>
    </div>
</div>
</body>
</html>
