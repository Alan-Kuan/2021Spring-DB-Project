<?php
    // @param   String $page    current page
?>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><?= $SITENAME; ?></span>
        <div class="navbar-nav">
        <?php
            if($page === 'home'):
        ?>
            <a class="nav-link active" aria-current="page" href="home.php"><?= $MSG['home']; ?></a>
            <a class="nav-link" href="shop.php"><?= $MSG['shop']; ?></a>
        <?php
            elseif($page === 'shop'):
        ?>
            <a class="nav-link" href="home.php"><?= $MSG['home']; ?></a>
            <a class="nav-link active" aria-current="page" href="shop.php"><?= $MSG['shop']; ?></a>
        <?php
            endif;
        ?>
            <a class="nav-link" href="index.php"><?= $MSG['logout']; ?></a>
        </div>
    </div>
</nav>
