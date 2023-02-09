<main class="home">
    <h2>Contact Us</h2>
    <?php if (isset($me)) {
        echo $me;
    } ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Full name:</label>
        <input type="text" name="name"/>

        <label>Telephone No:</label>
        <input type="text" name="telephone"/>

        <label>E-mail:</label>
        <input type="text" name="email"/>

        <label>Enquiry:</label>
        <textarea name="enquiry"></textarea>


        <input type="submit" name="submit" value="Submit"/>

    </form>

    </section>
</main>
