<?php
require "../dbConnect.php";
?>
<ul>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM category");
    $stmt->execute();
    $data = $stmt->fetchAll();
    foreach ($data as $category) {
        echo '<li><a class="categoryLink" href="../job?categoryId=' . $category['id'] . '">' . $category['name'] . '</a></li>';
    }
    ?>

</ul>


