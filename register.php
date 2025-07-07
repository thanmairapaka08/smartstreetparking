<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "smartstreetparking // Change DB name

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check if user/email already exists
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $msg = "Username or email already exists!";
    } else {
        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed);
        if ($stmt->execute()) {
            $msg = "Registration successful! <a href='login.php' class='text-blue-600'>Login here</a>.";
        } else {
            $msg = "Registration failed. Try again.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Smart Street Parking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center min-h-screen">
    <form method="POST" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-indigo-700 text-center">Register</h2>
        <?php if ($msg): ?>
            <div class="mb-4 text-center text-red-600"><?php echo $msg; ?></div>
        <?php endif; ?>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="username">Username</label>
            <input name="username" id="username" type="text" required class="w-full p-2 border rounded" autofocus>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="email">Email</label>
            <input name="email" id="email" type="email" required class="w-full p-2 border rounded">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2" for="password">Password</label>
            <input name="password" id="password" type="password" required class="w-full p-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">Register</button>
        <div class="mt-4 text-center text-sm">
            Already have an account? <a href="login.php" class="text-blue-600">Login</a>
        </div>
    </form>
</body>
</html>
