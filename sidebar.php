<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$_debug = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
        <img class="bmsi-logo" src="/images/bmsi-logo.png" alt="logo of bmsi">
        <h2>Welcome, <?=ucfirst($_SESSION['role']) . " - " . $_SESSION['username']?>!</h2>
        <div class="menu">
			<?php if ($_SESSION['role'] == 'admin' || $_debug): ?>
            <div class="item"><a href="dashboard.php"><i class="fa-solid fa-house"></i>Home</a></div>

            <div class="item"><a href="create_user.php"><i class="fa-solid fa-user-plus"></i>Create User</a></div>

            <div class="item"><a class="sub-btn"><i class="fa-solid fa-graduation-cap"></i>Students
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
            <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <div class="item"><a class="sub-btn"><i class="fa-solid fa-clipboard-user"></i>Student List
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
                    <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <a href="enroll_student.php" class="sub-item">Enroll Students</a>
                <a href="project.php" class="sub-item">View Students</a>
                    </div>
                </div>
                <a href="#" class="sub-item">Academic Records</a>   

                </div>
            </div>

            <div class="item"><a class="sub-btn"><i class="fa-solid fa-graduation-cap"></i>Academic Management
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
                <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <div class="item"><a class="sub-btn"><i class="fa-solid fa-clipboard-user"></i>Manage Subjects
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
                    <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <a href="create_subject.php" class="sub-item">Create Subject</a>
                <a href="assign_student_subject.php" class="sub-item">Assign Student Subject</a>
                <a href="assign_faculty_subject.php" class="sub-item">Assign Faculty Subject</a>
                <a href="view_subject_list.php" class="sub-item">View Subjects</a>
                    </div>
                </div>
                <div class="item"><a class="sub-btn"><i class="fa-solid fa-clipboard-user"></i>Curriculum Planning
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
                        <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <a href="create_section.php" class="sub-item">Create Section</a>
                <a href="assign_section_subject.php" class="sub-item">Assign Section Subject</a>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="item"><a class="sub-btn"><i class="fa-solid fa-circle-info"></i>Faculty
				<i class="fa-solid fa-chevron-down drpdown"></i>
				</a>
				<div class="sub-menu">
					<a href="" class="sub-item"></a>
					<a href="manage_faculty.php" class="sub-item">Faculty List</a>
					<a href="assign_faculty_subject.php" class="sub-item">Assign Faculty Subject</a>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($_SESSION['role'] == 'faculty' || $_debug): ?>
			
			
			<?php endif; ?>
			<?php if ($_SESSION['role'] == 'student' || $_debug): ?>

			
			<?php endif; ?>
            <div class="item"><a class="sub-btn"><i class="fa-solid fa-circle-info"></i>More
            <i class="fa-solid fa-chevron-down drpdown"></i>
            </a>
            <div class="sub-menu">
                <a href="" class="sub-item"></a>
                <a href="mission_vision.php" class="sub-item">Mission & Vision</a>
                <a href="members.php" class="sub-item">Developers</a>
            </div>
        </div>

            <div class="item"><a href="logout.php"><i class="fa-solid fa-circle-left"></i>Logout</a></div>
        </div>
    </div>

    <!-- jquery for sub-menu toggle -->
    <script src="/js/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $('.sub-btn').click(function(){
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            })
        })
    </script>
</body>
</html>