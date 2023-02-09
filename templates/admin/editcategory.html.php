<main class="sidebar">

    <section class="left">
        <?php require "navCat.php"; ?>
    </section>

    <section class="right">

        <h2><?php echo !isset($currentCategory) ? 'Edit Category' : 'Add Category'; ?></h2>

        <form action="" method="POST">
            <?php if ($currentCategory): ?>
                <input type="hidden" name="id" value="<?php echo $currentCategory['id']; ?>"/>
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $currentCategory['name']; ?>"/>
            <?php else: ?>
                <input type="hidden" name="id" value=""/>
                <label>Name</label>
                <input type="text" name="name" value=""/>
            <?php endif; ?>

            <input type="submit" name="submit" value="Save Category"/>

        </form>

    </section>
</main>


