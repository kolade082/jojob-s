<?php if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../styles.css">
    <title>Jo's Jobs - About</title>
</head>

<body>
<header>
    <section>
        <aside>
            <h3>Office Hours:</h3>
            <p>Mon-Fri: 09:00-17:30</p>
            <p>Sat: 09:00-17:00</p>
            <p>Sun: Closed</p>
        </aside>
        <h1>Jo's Jobs</h1>

    </section>
</header>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>
    <p><a href="../admin/logout"> Logout</a></p>
    <p><a href="../admin/jobs"> Tab</a></p>
    <?php
} else {
    ?>
    <p><a href="../admin/login"> Admin Login</a></p>
    <?php
}
?>
<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li>Jobs
            <?php require "navJob.php"; ?>
        </li>
        <li><a href="../about">About Us</a></li>
        <li><a href="../contact">Contact Us</a></li>
        <li><a href="../faqs">Faqs</a></li>
    </ul>

</nav>
<img src="../images/randombanner.php"/>
<?= $output ?? ""; ?>
<footer>
    &copy; Jo's Jobs <?= date('Y'); ?>
</footer>
</body>

</html>