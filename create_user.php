<?php
require_once 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: incorrect.php');
    exit();
}

$create_user_success = false;
$create_user_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Consider hashing this in production
    $role = $_POST['role'];

    $conn->begin_transaction();

    try {
        // Insert user into the users table
        $user_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("sss", $username, $password, $role);
        $user_stmt->execute();
        $user_id = $user_stmt->insert_id;

        // Insert into students or faculty based on role
        if ($role === 'student') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $age = $_POST['age'];
            $address = $_POST['address'];
            $date_of_birth = $_POST['date_of_birth'];

            if (!empty($name) && !empty($email) && !empty($phone) && !empty($age) && !empty($address) && !empty($date_of_birth)) {
                $student_sql = "INSERT INTO students (user_id, name, email, phone, age, address, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $student_stmt = $conn->prepare($student_sql);
                $student_stmt->bind_param("ississs", $user_id, $name, $email, $phone, $age, $address, $date_of_birth);
                $student_stmt->execute();
            } else {
                throw new Exception("Please fill in all student details.");
            }
        } elseif ($role === 'faculty') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $age = $_POST['age'];
            $hire_date = $_POST['hire_date'];

            if (!empty($name) && !empty($email) && !empty($phone) && !empty($age) && !empty($hire_date)) {
                $faculty_sql = "INSERT INTO faculty (user_id, name, email, phone, age, hire_date) VALUES (?, ?, ?, ?, ?, ?)";
                $faculty_stmt = $conn->prepare($faculty_sql);
                $faculty_stmt->bind_param("ississ", $user_id, $name, $email, $phone, $age, $hire_date);
                $faculty_stmt->execute();
            } else {
                throw new Exception("Please fill in all faculty details.");
            }
        }

        $conn->commit(); // Commit the transaction if all queries succeed
        $create_user_success = true;
    } catch (Exception $e) {
        $conn->rollback(); // Rollback all changes on failure
        $create_user_error = "Transaction failed: " . $e->getMessage();
    } finally {
        $user_stmt->close();
        if (isset($student_stmt)) $student_stmt->close();
        if (isset($faculty_stmt)) $faculty_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="/font-awesome/css/all.css">
    <script>
        function showForm() {
            const role = document.getElementById('role').value;
			document.getElementById('common-form').style.display = role !== 'admin' ? 'block' : 'none';
            document.getElementById('student-form').style.display = role === 'student' ? 'block' : 'none';
            document.getElementById('faculty-form').style.display = role === 'faculty' ? 'block' : 'none';
			document.getElementById('form-header').innerText = role === 'student' ? "Student Details" : "Faculty Details";
        }

        // Function to display the success or error popup
        function showPopup(message, type) {
            let popup = document.createElement('div');
            popup.classList.add('popup-message');
            if (type === 'error') {
                popup.classList.add('error'); // Apply error styles
            }
            popup.innerHTML = message;
            document.body.appendChild(popup);

            // Fade-out the popup after 3 seconds
            setTimeout(() => {
                popup.style.animation = 'fadeOut 1s ease-in-out forwards';
                setTimeout(() => {
                    popup.remove();
                }, 1000); // Allow the fade-out animation to finish before removing
            }, 3000);
        }
    </script>
</head>
<body>
<?php include 'sidebar.php' ?>
<div class="content-container">
    <div class="main-content">
        <header>
            <h1>Create User</h1>
        </header>

        <?php
        // Show popup on success or error
        if ($create_user_success) {
            echo "<script>showPopup('User and Profile Created Successfully!', 'success');</script>";
        } elseif ($create_user_error) {
            echo "<script>showPopup('$create_user_error', 'error');</script>";
        }
        ?>

        <form method="post" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" onchange="showForm()" required>
                <option value="">--Select Role--</option>
                <option value="admin">Admin</option>
                <option value="faculty">Faculty</option>
                <option value="student">Student</option>
            </select><br><br>

			<div id="common-form" style="display: none;">
                <header><h1 id="form-header">Student Details</h1></header>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name"><br><br>

                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br><br>

                <label for="phone">Phone:</label><br>
                <input type="text" id="phone" name="phone"><br><br>

                <label for="age">Age:</label><br>
                <input type="number" id="age" name="age"><br><br>
			</div>
            <div id="student-form" style="display: none;">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address"><br><br>

                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth"><br><br>
            </div>

            <div id="faculty-form" style="display: none;">
                <label for="hire_date">Hire Date:</label><br>
                <input type="date" id="hire_date" name="hire_date"><br><br>
            </div>

            <button type="submit">Create User</button>
        </form>
    </div>
</div>
</body>
</html>
