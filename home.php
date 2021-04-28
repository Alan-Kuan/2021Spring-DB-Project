<?php
    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }
?>

<?php include './header.php'; ?>

<?php includeWith('./components/navbar.php', array('page' => 'home')); ?>

<div class="w-75 my-5 mx-auto">

    <h1><?= $TEXT['home']; ?></h1>

    <?php include './components/homepage.php'; ?>

</div>  <!-- container -->

<?php include './footer.php'; ?>
