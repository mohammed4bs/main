<?php
    session_start();
   
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        ?>
        <div class="container-fluid">
        <?php
        if (isset($_GET['client'])) {
            $client = $_GET['client'];
            $stmt = $db->prepare('SELECT client_id, client_name,phone,email,address FROM clients WHERE client_name = ?');
            $stmt->execute(array($client));
            $row = $stmt->rowCount();
            $data = $stmt->fetch();
            
            ?>
            <div class="col-8 offset-2">
                <div class="table-responsive-md">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                            الاسم
                            </th>
                            <th>
                            التليفون
                            </th>
                            <th>
                            الايميل
                            </th>
                            <th>
                            العنوان
                            </th>
                        
                        <tr>
                        
                        </thead>
                        <tbody>
                        
                        <?php 
                            echo "<tr><td>" . $data['client_name'] . "</td><td>" . $data['phone'] ."</td><td>" . $data['email'] . "</td><td>" . $data['address'] . "</td></tr>";
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php

            
        }
        

    ?>
      
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