<?php
    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }
?>

<?php include './header.php'; ?>

<?php includeWith('./components/navbar.php', array('page' => 'shop-order')); ?>

<div class="w-75 my-5 mx-auto">

    <h1><?= $TEXT['shop-order']; ?></h1>

</div>  <!-- container -->

<?php include './footer.php'; ?>