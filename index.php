<?php
    session_start();
    # remove all session variables
    session_unset();
    # destroy the session
    session_destroy();
    $_SESSION['Authenticated'] = false;
?>

<?php include './header-index.php'; ?>

<div class="bg-dark text-white px-5 py-3">
    <h1><?= $SITENAME; ?></h1>
    <p><?= $DESC; ?></p>
</div>

<div class="w-50 mt-5 mx-auto">

    <?php include './components/portal.php'; ?>

</div>  <!-- container -->

<?php include './footer.php'; ?>
