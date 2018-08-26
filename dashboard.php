<?php
    session_start();
   
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        ?>
        <div class="container">
            <h1 class="page-title text-center"> لوحة التحكم الرئيسية </h1>
            <div class="main-items">
                <div class="row">
                    <div class="col-3">
                        <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Secondary card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                        </div>  
                    </div>
                    <div class="col-3">
                        <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Secondary card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                        </div>  
                    </div>
                    <div class="col-3">
                        <div class="card text-white bg-danger  mb-3" style="max-width: 18rem;">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Secondary card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                        </div>  
                    </div>
                    <div class="col-3">
                        <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                            <div class="card-header">Header</div>
                            <div class="card-body">
                                <h5 class="card-title">Secondary card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                        </div>  
                    </div>

                </div>
            </div>

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