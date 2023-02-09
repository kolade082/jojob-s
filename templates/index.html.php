
<main class="home">

    <form action="home" method="get">
        <select name="job_location">
            <option disabled selected>Filter Location</option>
            <option value="All">All</option>
            <?php
            foreach ($locations as $location) {
                echo '<option class="search" value="' . $location['location'] . '">' . $location['location'] . '</option>';
            }
            ?>
            <input style="float: right" type="submit" name="submit" value="Search" />
        </select>
    </form>

        <?php
        foreach ($jobs as $job) { ?>
            <h3><?= $job['title']; ?></h3>
            <h4><?= $job['salary']; ?></h4>
            <h4><?= $job['location']; ?></h4>
            <p><?= $job['description']; ?></p>
            <a class="more" href="apply?id=<?= $job['id'] ?>">Apply for this job</a>
        <?php } ?>

</main>
