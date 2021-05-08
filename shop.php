<?php
    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }
?>

<?php include './header.php'; ?>

<?php includeWith('./components/navbar.php', array('page' => 'shop')); ?>

<div class="w-75 my-5 mx-auto">

    <h1><?= $TEXT['shop']; ?></h1>

    <?php
        if(isShopkeeper($_SESSION['Username'])) {
            include './components/manage-shop-panel.php';
        } else {
            include './components/register-shop-form.php';
        }
    ?>

</div>  <!-- container -->

<?php include './footer.php'; ?>
