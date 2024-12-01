<?php include 'db.php'; ?>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: incorrect.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar">
        <h2>Welcome, Admin!</h2>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="project.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="members.php">Departments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
        </header>
        <main>
            <div class="stats">
                <div class="stat">
                    <img src="/images/student.png" alt="Students">
                    <h2>Students</h2>
                </div>
                <div class="stat">
                    <img src="/images/course.png" alt="Courses">
                    <h2>Courses</h2>
                </div>
                <div class="stat">
                    <img src="/images/department.png" alt="Departments">
                    <h2>Departments</h2>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="studentChart"></canvas>
            </div>
        </main>
    </div>

<!-- jquery for sub-menu toggle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.sub-btn').click(function(){
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            })
        })
    </script>

    <script>
        var ctx = document.getElementById('studentChart').getContext('2d');
        var studentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
                datasets: [{
                    label: 'Enrolled Students',
                    data: [180, 150, 100, 130,80, 68, 50], 
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
