<main class="sidebar">

    <section class="left">
        <?php require "navCat.php"; ?>
    </section>

    <section class="right">
        <h2>Jobs</h2>

        <a class="new" href="addjob">Add new job</a>
        <form>
            <select name="category_name">
                <option disabled selected>Filter By Categories</option>
                <option value="All">All</option>
                <?php
                foreach ($categories as $row) {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
                ?>
                <input type="submit" name="submit" value="Search"/>
            </select>
        </form>
        <?php
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Title</th>';
        echo '<th>Category Name</th>';
        echo '<th style="width: 15%">Salary</th>';
        echo '<th style="width: 5%">&nbsp;</th>';
        echo '<th style="width: 15%">&nbsp;</th>';
        echo '<th style="width: 5%">&nbsp;</th>';
        echo '<th style="width: 5%">&nbsp;</th>';
        echo '</tr>';


        foreach ($jobs as $job) {


            echo '<tr>';
            echo '<td>' . $job['title'] . '</td>';
            echo '<td>' . $job['catName'] . '</td>';
            echo '<td>' . $job['salary'] . '</td>';
            echo '<td><a style="float: right" href="editjob?id=' . $job['id'] . '">Edit</a></td>';
            echo '<td><a style="float: right" href="applicants?id=' . $job['id'] . '">View applicants (' . $job['count'] . ')</a></td>';
            if ($job['archive'] == 1) {
                echo '<td><form method="post" action="repostjob">
				<input type="hidden" name="id" value="' . $job['id'] . '" />
				<input type="submit" name="submit" value="Re-Post" />
				</form></td>';
            } else {
                echo '<td><form method="post" action="deletejob">
				<input type="hidden" name="id" value="' . $job['id'] . '" />
				<input type="submit" name="submit" value="Delete" />
				</form></td>';
            }
            echo '</tr>';
        }

        echo '</thead>';
        echo '</table>';

        ?>

    </section>
</main>



