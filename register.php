<?php 
    session_start();
    include "_dbconnect.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $phonenumber = $_POST['Phonenumber'];
        $password = $_POST['Password'];
        $confirmPassword = $_POST['ConfirmPassword'];
    
        if (empty($phonenumber) || empty($password) || empty($confirmPassword)) {
            $error = "All fields are required.";
        } elseif ($password !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE phonenumber = ?");
            $stmt->bind_param("s", $phonenumber);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $error = "Phone number already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
                $stmt = $conn->prepare("INSERT INTO users (phonenumber, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $phonenumber, $hashedPassword);
    
                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }
    
            $stmt->close();
        }
    
        $conn->close();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<form action="register.php" method="POST">
        <h1>REGISTER</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <input type="text" name="Phonenumber" placeholder="Phone number" required><br><br>
        <input type="password" name="Password" placeholder="Password" required><br><br>
        <input type="password" name="ConfirmPassword" placeholder="Confirm password" required><br><br>
        <button type="submit">Register</button>
        <a href="login.php">Already have an account?</a>
    </form>
</body>
</html>