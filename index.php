<?php
    session_start();
    # remove all session variables
    session_unset();
    # destroy the session
    session_destroy();
    $_SESSION['Authenticated'] = false;
?>

<?php include './header.php'; ?>

<div class="container-70">

    <h1><?= $SITENAME; ?></h1>
    <p><?= $DESC; ?></p>

    <?php include './components/portal.php'; ?>

</div>  <!-- container -->

<?php include './footer.php'; ?>
