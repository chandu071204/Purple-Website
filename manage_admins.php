<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'login_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new admin
    if (isset($_POST['add_admin'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO admins (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);

        if ($stmt->execute()) {
            $message = "Admin added successfully.";
        } else {
            $message = "Error adding admin: " . $conn->error;
        }
        $stmt->close();
    }

    // Update admin
    if (isset($_POST['update_admin'])) {
        $adminId = $_POST['admin_id'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("UPDATE admins SET full_name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $full_name, $email, $adminId);

        if ($stmt->execute()) {
            $message = "Admin updated successfully.";
        } else {
            $message = "Error updating admin: " . $conn->error;
        }
        $stmt->close();
    }

    // Delete admin
    if (isset($_POST['delete_admin'])) {
        $adminId = $_POST['admin_id'];

        $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->bind_param("i", $adminId);

        if ($stmt->execute()) {
            $message = "Admin deleted successfully.";
        } else {
            $message = "Error deleting admin: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all admins
$result = $conn->query("SELECT * FROM admins");
$admins = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background: #2196f3;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background: #1976d2;
        }
        .admin-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .admin-list th, .admin-list td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .admin-list th {
            background: #f4f4f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Admins</h1>
        <?php if (isset($message)) { echo "<p style='color: green;'>$message</p>"; } ?>

        <!-- Add Admin -->
        <form method="POST">
            <h2>Add New Admin</h2>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_admin">Add Admin</button>
        </form>

        <!-- Admin List -->
        <div class="admin-list">
            <h2>Admin List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= $admin['id']; ?></td>
                            <td><?= htmlspecialchars($admin['full_name']); ?></td>
                            <td><?= htmlspecialchars($admin['email']); ?></td>
                            <td>
                                <!-- Update Admin -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="admin_id" value="<?= $admin['id']; ?>">
                                    <input type="text" name="full_name" value="<?= htmlspecialchars($admin['full_name']); ?>" required>
                                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" required>
                                    <button type="submit" name="update_admin">Update</button>
                                </form>
                                <!-- Delete Admin -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="admin_id" value="<?= $admin['id']; ?>">
                                    <button type="submit" name="delete_admin" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
