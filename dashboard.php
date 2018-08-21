<?php
    session_start();
   
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        if (isset($_GET['client'])) {
            $client = $_GET['client'];
            $stmt = $db->prepare('SELECT client_name FROM clients WHERE client_name = ?');
            $stmt->execute(array($client));
            $row = $stmt->rowCount();
            
            if ($row > 0 ) {
                echo "Found";
                
            }
            
        }
        
        

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