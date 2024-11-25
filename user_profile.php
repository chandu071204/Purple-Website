<?php 
session_start(); 

// Database connection
require_once "dbconnection.php";

$userName = "";
$adminName = "";
$welcomeMessage = "";

// Check if the user is logged in as a regular user or admin
if (isset($_SESSION['user'])) {
    // If logged in as a regular user
    $userId = $_SESSION['user'];
    $sql = "SELECT full_name, email, mobile_number, address FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            $full_name = $user['full_name'];
            $email = $user['email'];
            $mobile_number = $user['mobile_number'];
            $address = $user['address'];
            $welcomeMessage = "Welcome, $full_name!";
        }
    }
} elseif (isset($_SESSION['admin'])) {
    // If logged in as an admin
    $adminId = $_SESSION['admin'];
    $sql = "SELECT full_name FROM admins WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $adminId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);
        if ($admin) {
            $adminName = $admin['full_name'];
            $welcomeMessage = "Welcome, Admin $adminName!";
        }
    }
} else {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// If the form is submitted, update the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newMobile = $_POST['mobile_number'];
    $newAddress = $_POST['address'];

    // Update the user's mobile number and address in the database
    if (isset($_SESSION['user'])) {
        // Update query for regular user
        $updateSql = "UPDATE users SET mobile_number = ?, address = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $updateSql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $newMobile, $newAddress, $userId);
            mysqli_stmt_execute($stmt);
            echo "<p>Profile updated successfully!</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update My Profile</title>
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
    
</head>
<body>

<div class="container">
    <div class="navbar">
    <?php include 'include/navbar.php'; ?>
    </div>
    <!-- New division specifically for form content -->
    <div class="form-container">
        <h2>Update My Profile</h2>
        <p class="message"><?php echo $welcomeMessage; ?></p>

        <!-- Profile Update Form -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly class="readonly"><br>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly class="readonly"><br>
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($mobile_number); ?>" required pattern="^[789]\d{9}$" title="Mobile number must be 10 digits and start with 7, 8, or 9"><br>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($address); ?></textarea><br>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

<!-- Scoped CSS for the form-container division -->
<style>

    
    /* No changes to the navbar */


/* Form container */
.form-container {
    margin-top: 180px; /* Adjust this value to match the height of the navbar */
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

    /* Scoped styles for the form inside the form-container */
    .form-container .form-group {
        margin-bottom: 15px;
    }

    .form-container .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-container .form-group input,
    .form-container .form-group textarea {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-container .readonly {
        background-color: #f0f0f0;
    }

    .form-container button {
        padding: 10px 20px;
        background-color: #000;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #45a049;
    }

    /* Responsive design for small screens */
    @media (max-width: 600px) {
        .form-container {
            padding: 15px;
        }

        .form-container .form-group input,
        .form-container .form-group textarea {
            font-size: 12px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
        }
    }
</style>

</body>
</html>
