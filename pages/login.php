<?php
require_once '../includes/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if (!empty($password) && $password === 'rcm2026') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        header("Location: index.php");
        exit;
    } else {
        $error = !empty($password) ? 'Password salah!' : 'Password harus diisi!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - RCM Mart & Printing</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #c0c0c0;
        }
        
        .login-box {
            background: #c0c0c0;
            padding: 20px;
            border: 2px;
            border-color: #ffffff #808080 #808080 #ffffff;
            border-style: solid;
            border-radius: 0px;
            box-shadow: none;
            min-width: 300px;
        }
        
        .login-box h2 {
            text-align: center;
            color: #000000;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        
        .login-box label {
            display: block;
            margin-bottom: 4px;
            color: #000000;
            font-weight: normal;
            font-size: 11px;
        }
        
        .login-box input {
            width: 100%;
            padding: 4px 6px;
            border: 2px;
            border-color: #dfdfdf #808080 #808080 #dfdfdf;
            border-style: solid;
            border-radius: 0px;
            box-sizing: border-box;
            font-size: 11px;
            margin-bottom: 12px;
            font-family: inherit;
        }
        
        .login-box input:focus {
            outline: none;
            border-color: #000000 #c0c0c0 #c0c0c0 #000000;
        }
        
        .login-box button {
            width: 100%;
            padding: 4px;
            background: #c0c0c0;
            border: 2px;
            border-color: #ffffff #808080 #808080 #ffffff;
            border-style: solid;
            border-radius: 0px;
            cursor: pointer;
            font-size: 11px;
            font-weight: normal;
            color: #000000;
        }
        
        .login-box button:hover {
            border-color: #ffffff #000000 #000000 #ffffff;
        }
        
        .login-box button:active {
            border-color: #808080 #ffffff #ffffff #808080;
        }
        
        .error-message {
            background: #c00000;
            border: 2px solid #c0c0c0;
            color: #ffffff;
            padding: 6px;
            border-radius: 0px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }
        
        .info-text {
            text-align: center;
            color: #000000;
            font-size: 10px;
            margin-top: 12px;
            border-top: 1px solid #808080;
            padding-top: 8px;
        }
        
        .info-text strong {
            display: block;
            margin-bottom: 2px;
            color: #000000;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>RCM SISTEM LOGIN</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required autofocus>
            
            <button type="submit">Masuk</button>
        </form>
    </div>
</div>

</body>
</html>
