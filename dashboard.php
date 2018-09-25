<?php
    session_start();
   
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        ?>
        <div class="container">
            <h1 class="page-title text-center"> لوحة التحكم الرئيسية </h1>
            <h6> مرحبا,  <?php echo $_SESSION['username']; ?></h6>
            <div class="row">
                <div class="col-6">
                    <form class="" action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">بحث برقم القطعة</span>
                            </div>
                            <input type="text" aria-label="رقم القطعة" name="unit" class="form-control">
                            <div class="input-group-prepend">
                                <input type="submit" class="btn btn-outline-secondary" value="بحث"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <form class="" action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
                        <div class="input-group">
                            
                                <div class="input-group-prepend">
                                    <span class="input-group-text">بحث  باسم العميل</span>
                                </div>
                                <input type="text" aria-label="رقم القطعة" name="client" class="form-control">
                                <div class="input-group-prepend">
                                    <input type="submit" class="btn btn-outline-secondary" value="بحث"/>
                                </div>
                            
                        </div>
                    </form>
                </div>
                
            </div>


        <?php
        
        if (isset($_GET['unit'])) {
            $unit = $_GET['unit'];
            $stmt = $db->prepare('SELECT * FROM units WHERE unit_name like ? OR unit_id like ?');
            $stmt->execute(array('%' . $unit . '%' , '%' . $unit . '%' ));
            $row = $stmt->rowCount();
            $data = $stmt->fetchAll();
            
            $conUnits = array();
            foreach ($data as $u) {
                echo $u['unit_id'] . '<br>';
                $stmt = $db->prepare('SELECT * FROM contract_units WHERE unit_id = ?');
                $stmt->execute(array($u['unit_id']));
                $count = $stmt->rowCount();
                $filteredData = $stmt->fetch();
                $stmt = $db->prepare('SELECT * FROM maint WHERE contract_id = ?');
                $stmt->execute(array($filteredData['contract_id']));
                $row = $stmt->fetch();
                $balance = $row['balance'];
                $newbalance = 0;
                if (strtotime($row['end_date']) < strtotime(date('y-m-d'))) {
                    $newbalance = $balance + 750;
                } else {
                    $newbalance = 0;
                }
                $test = strtotime($row['end_date']) - strtotime(date('y-m-d'));
                echo $test . '<br>';

                if ($count > 0 ) {
                    $conUnits += array($u['unit_id'] => $u + $filteredData + $row);
                    echo $newbalance . '<br>';
                    
                }

            }
            echo '<pre>';
            print_r($conUnits);
            echo '</pre>';
        }
        ?>
            

        <?php
        
        if (isset($_GET['client'])) {
            $client = $_GET['client'];
            $stmt = $db->prepare('SELECT * FROM clients WHERE client_name like ? OR client_id like ?');
            $stmt->execute(array('%' . $client . '%' , '%' . $client . '%' ));
            $row = $stmt->rowCount();
            $data = $stmt->fetchAll();
            
            $conClients = array();
            foreach ($data as $c) {
                echo $c['client_id'];
                $stmt = $db->prepare('SELECT * FROM contracts WHERE client_id = ?');
                $stmt->execute(array($c['client_id']));
                $count = $stmt->rowCount();
                
                $filteredData = $stmt->fetch();
                if ($count > 0 ) {
                    $conClients += array($c['client_id'] => $c + $filteredData);
                    
                }

            }
            echo '<pre>';
            //print_r($conClients);
            echo '</pre>'
            ?>


            <!-- Report Start -->
            <div class="col-12">


                <div class="table-responsive-md">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                            رقم العميل
                            </th>
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
                            <th>
                            الصيانة
                            </th>
                            <th>
                            الكهرباء
                            </th>
                        
                        <tr>
                        
                        </thead>
                        <tbody>
                        
                        <?php 
                            
                            foreach($conClients as $r) {
                                echo "<tr><td>" . $r['client_id']. "</td><td>" . $r['client_name'] 
                                . "</td><td>" . $r['phone'] ."</td><td>" . $r['email'] . "</td><td>" 
                                . $r['address'] . "</td> <td>750</td> <td>1140</td></tr>";
                            
                            }
                            
                                
                        
                            
                            
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php

            
        } 

        ?>  

        
    
      
            
            
            
            
        </div>
        <div class="container">
            <div class="main-items">
                    <div class="row">
                        <div class="col-3">
                            <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                <div class="card-header">اجمالي مستحقات الصيانة</div>
                                <div class="card-body">
                                    <?php 
                                        $stmt = $db->prepare('SELECT SUM(balance) FROM maint');
                                        $stmt->execute();
                                        $balanceSum = $stmt->fetch();
                                        
                                        echo "<h5 class='card-title'>" . $balanceSum['SUM(balance)'] . " جنية</h5>";
                                    ?>
                                    
                                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                </div>
                            </div>  
                        </div>
                        <div class="col-3">
                            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                            <div class="card-header">اجمالي العملاء</div>
                                <div class="card-body">
                                    <?php 
                                        $stmt = $db->prepare('SELECT COUNT(client_id) FROM contracts');
                                        $stmt->execute();
                                        $clientsCount = $stmt->fetch();
                                        
                                        echo "<h5 class='card-title'>" . $clientsCount['COUNT(client_id)'] . " عميل</h5>";
                                    ?>
                                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                </div>
                            </div>  
                        </div>
                        <div class="col-3">
                            <div class="card text-white bg-danger  mb-3" style="max-width: 18rem;">
                                <div class="card-header">اجمالي مستحقات الكهرباء</div>
                                <div class="card-body">
                                    <?php 
                                        $stmt = $db->prepare('SELECT COUNT(client_id) FROM contracts');
                                        $stmt->execute();
                                        $clientsCount = $stmt->fetch();
                                        
                                        echo "<h5 class='card-title'>" . $clientsCount['COUNT(client_id)'] . " عميل</h5>";
                                    ?>
                                    
                                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                </div>
                            </div>  
                        </div>
                        <div class="col-3">
                            <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                <div class="card-header">اجمالي العقود</div>
                                <div class="card-body">
                                    <?php 
                                        $stmt = $db->prepare('SELECT COUNT(contract_id) FROM contracts');
                                        $stmt->execute();
                                        $contractsCount = $stmt->fetch();
                                        
                                        echo "<h5 class='card-title'>" . $contractsCount['COUNT(contract_id)'] . " عقود</h5>";
                                    ?>
                                    
                                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                </div>
                            </div>  
                        </div>

                    </div>
                </div>
        </div>
    <?php
    include $tbl . 'footer.php';
    } else {
        header('Location: index.php');
    }





