<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<div>
    <img src = "assets/img/banner.jpg" alt = "colored arrows" />
</div>
<nav id="primary_nav_wrap">
    <ul>
        <li><a>Conference</a>
            <ul>
                <li class="current-menu-item"><a href="index.php">Home</a></li>
                <li><a href="keynote.php">Keynote Speaker</a></li>
                <li><a href="general.php">General Information</a>
                    <ul>
                        <li><a href="about.php">About the Conference</a></li>
                        <li><a href="fee.php">Conference Fee</a></li>
                        <li><a href="hotel.php">Hotel Information</a></li>
                    </ul>
                </li>
                <li><a href="call.php">Call for paper</a></li>
                <li><a href="dates.php">Important Dates</a></li>
                <li><a href="majors.php">Major Areas</a></li>
                <li><a href="submission.php">Paper Submission</a></li>
                <li><a href="review.php">Paper Review</a></li>
                <li><a href="program.php">Conference Program</a></li>
                <li><a href="guidelines.php">Guidelines</a></li>
                <li><a href="feedback.php">Comments and feedback</a></li>
                <li><a href="book_store.php">Book Store</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="login_div">
    <?php echo "Welcome, {$_SESSION['username']}"; ?>
    <a href="controller.php?action=logout">logout</a>
</div>


