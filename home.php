<?php
    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }
?>

<?php include './header.php'; ?>

<div class="container-70">

    <h1>Home</h1>

</div>  <!-- container -->

<?php include './footer.php'; ?>
