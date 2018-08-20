<?php
    session_start();
  
    
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        

    ?>
        <div class="container-fluid">
            <h2> مرحبا,  <?php echo $_SESSION['username']; ?></h2>
        </div>
    <?php
    } else {
        header('Location: index.php');
    }


?>

















<?php
    include $tbl . 'footer.php';

?>