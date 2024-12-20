<?php
require_once 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: incorrect.php');
    exit();
}

// Check if the logged-in user is an admin
$is_admin = $_SESSION['role'] === 'admin';

// Variable to indicate if the update was successful
$update_success = false;

// Check if student_id parameter is provided
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // Fetch student details
    $sql = "SELECT * FROM students WHERE student_id = $student_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Handle form submission for updates (if the user is admin)
        if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $updated_name = $_POST['name'];
            $updated_email = $_POST['email'];
            $updated_phone = $_POST['phone'];
            $updated_age = $_POST['age'];
            $updated_address = $_POST['address'];
            $updated_dob = $_POST['date_of_birth'];

            $update_sql = "
                UPDATE students 
                SET name = ?, email = ?, phone = ?, age = ?, address = ?, date_of_birth = ? 
                WHERE student_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssssi", $updated_name, $updated_email, $updated_phone, $updated_age, $updated_address, $updated_dob, $student_id);

            if ($stmt->execute()) {
                $update_success = true;
            } else {
                echo "<p>Error updating student: " . $conn->error . "</p>";
            }
            $stmt->close();
        }

        // Fetch current subjects of the student along with section details
        $subjects_sql = "
            SELECT subjects.subject_id, subjects.name, subjects.description, ss.section_id, sections.section_name
            FROM student_section_subject sss
            INNER JOIN section_subject ss ON sss.section_subject_id = ss.section_subject_id
            INNER JOIN subjects ON ss.subject_id = subjects.subject_id
            INNER JOIN sections ON ss.section_id = sections.section_id
            WHERE sss.student_id = $student_id";
        $subjects_result = $conn->query($subjects_sql);
    } else {
        echo "<p>No student found with ID $student_id.</p>";
    }
} else {
    echo "<p>Invalid Usage.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link rel="stylesheet" href="page.css">
</head>
<body>
    <?php include 'sidebar.php' ?>
    <div class="main-content">
        <header>
            <h1>Student Profile</h1>
        </header>
        <?php if (isset($student)): ?>
            <?php if ($is_admin): ?>
                <form method="POST" id="updateForm">
                    <label>Name:</label><br>
                    <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required><br><br>

                    <label>Email:</label><br>
                    <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required><br><br>

                    <label>Phone:</label><br>
                    <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required><br><br>

                    <label>Age:</label><br>
                    <input type="number" name="age" value="<?= htmlspecialchars($student['age']) ?>" required><br><br>

                    <label>Address:</label><br>
                    <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required><br><br>

                    <label>Date of Birth:</label><br>
                    <input type="date" name="date_of_birth" value="<?= htmlspecialchars($student['date_of_birth']) ?>" required><br><br>

                    <button type="submit">Save Changes</button>
                    <a href="project.php" class="btn">Back</a>
                </form>
            <?php else: ?>
                <p><strong>Student ID:</strong> <?= $student['student_id'] ?></p>
                <p><strong>Name:</strong> <?= $student['name'] ?></p>
                <p><strong>Email:</strong> <?= $student['email'] ?></p>
                <p><strong>Phone:</strong> <?= $student['phone'] ?></p>
                <p><strong>Age:</strong> <?= $student['age'] ?></p>
                <p><strong>Address:</strong> <?= $student['address'] ?></p>
                <p><strong>Date of Birth:</strong> <?= $student['date_of_birth'] ?></p>
            <?php endif; ?>

            <header><h1>Current Subjects</h1></header>
<?php if ($subjects_result->num_rows > 0): ?>
    <div class="accordion">
        <?php while ($subject = $subjects_result->fetch_assoc()): ?>
            <div class="accordion-item">
                <button class="accordion-btn"><?= htmlspecialchars($subject['name']) ?></button>
                <div class="accordion-content">
                    <p><strong>Subject ID:</strong> <?= htmlspecialchars($subject['subject_id']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($subject['description']) ?></p>
                    <p><strong>Section ID:</strong> <?= htmlspecialchars($subject['section_id']) ?></p>
                    <p><strong>Section Name:</strong> <?= htmlspecialchars($subject['section_name']) ?></p>
                    <a href="view_student_grades.php?student_id=<?= $student_id ?>&subject_id=<?= $subject['subject_id'] ?>" class="view-grades-btn">View Grades</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No subjects found for this student.</p>
<?php endif; ?>
        <?php else: ?>
            <p>Invalid student ID or no student found.</p>
        <?php endif; ?>
        
        <!-- Popup Message -->
        <div id="popup" class="popup">
            <p>Updated Successfully!</p>
        </div>

        <!-- JavaScript to show the popup -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($update_success): ?>
                    const popup = document.getElementById('popup');
                    popup.style.display = 'block';
                    setTimeout(function() {
                        popup.style.opacity = '1';
                        setTimeout(function() {
                            popup.style.opacity = '0';
                            setTimeout(function() {
                                popup.style.display = 'none';
                            }, 1000); // Hide completely after 1 second
                        }, 3000); // Fade out after 3 seconds
                    }, 100); // Short delay to trigger CSS transition
                <?php endif; ?>
            });
        </script>

<script>
    document.querySelectorAll('.accordion-btn').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;

            // Toggle accordion content visibility with smooth transition
            if (content.style.display === 'block') {
                content.style.display = 'none';
                content.classList.remove('open');
            } else {
                content.style.display = 'block';
                content.classList.add('open');
            }

            // Toggle active state for the button (for visual feedback)
            button.classList.toggle('active');
        });
    });
</script>


    </div>
</body>
</html>
