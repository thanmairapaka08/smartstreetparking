<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "smartstreetparking"); // Change DB name

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed);
        $stmt->fetch();
        if (password_verify($password, $hashed)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $id;
            header("Location: index.html");
            exit;
        } else {
            $error = "Incorrect username or password!";
        }
    } else {
        $error = "Incorrect username or password!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Smart Street Parking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center min-h-screen">
    <form method="POST" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-indigo-700 text-center">Login</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-center text-red-600"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="username">Username</label>
            <input name="username" id="username" type="text" required class="w-full p-2 border rounded" autofocus>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2" for="password">Password</label>
            <input name="password" id="password" type="password" required class="w-full p-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">Login</button>
        <div class="mt-4 text-center text-sm">
            Don't have an account? <a href="register.php" class="text-blue-600">Register</a>
        </div>
    </form>
</body>
</html>
