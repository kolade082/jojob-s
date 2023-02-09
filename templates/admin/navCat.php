<?php
require "../dbConnect.php";
?>

<ul>
    <?php if ($_SESSION["userDetails"]["usertype"] == 'ADMIN') { ?>
        <li><a href="categories">Categories</a></li>
        <li><a href="users">Manage User</a></li>
        <li><a href="enquiry">Enquiries</a></li>
    <?php } ?>
    <li><a href="jobs">Jobs</a></li>

</ul>
