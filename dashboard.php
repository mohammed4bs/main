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
                // Get all units that exist in contracts
                $stmt = $db->prepare('SELECT * FROM contract_units WHERE unit_id = ?');
                $stmt->execute(array($u['unit_id']));
                $count = $stmt->rowCount();
                $filteredData = $stmt->fetch();

                // Get Maint info for that contract
                $stmt = $db->prepare('SELECT * FROM maint WHERE contract_id = ?');
                $stmt->execute(array($filteredData['contract_id']));
                $row = $stmt->fetch();

                // Get client_id and Total space from contracts
                $stmtClient = $db->prepare('SELECT client_id,total_space FROM contracts WHERE contract_id = ?');
                $stmtClient->execute(array($filteredData['contract_id']));
                $client_fetched = $stmtClient->fetch(); 
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
                    $conUnits += array($u['unit_id'] => $u + $filteredData + $row + $client_fetched);
                    echo $newbalance . '<br>';
                    
                }

            }

            ?>
            <table class="table table-striped table-hover ">
            <thead class="thead-dark">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        اسم القطعة
                    </th>
                    <th>
                        الريف
                    </th>
                    <th>
                    اسم العميل
                   
                    </th>
                    <th>
                        
                        رقم العقد  
                    </th>
                    
                    
                    <th>
                    المساحة
                    </th>
                    <th>
                        الرصيد
                    </th>
                    

                </tr>

            </thead>
            <tbody>
                <?php
                    foreach($conUnits as $unit) {
                            // Get reef name instead of reef_id
                             $s1 = $db->prepare('SELECT reef_name FROM reefs WHERE reef_id = ?');
                             $s1->execute(array($unit['reef_id']));
                             $f1 = $s1->fetch();
                             $s2 = $db->prepare('SELECT client_name FROM clients WHERE client_id = ?');
                             $s2->execute(array($unit['client_id']));
                             $f2 = $s2->fetch();
                        echo '<tr><td>'  . $unit['unit_id'] .
                             '</td><td>' . $unit['unit_name'] .
                             
                             '</td><td>' . $f1['reef_name'] .
                             '</td><td>' .$f2['client_name']  .
                             '</td><td>' . $unit['contract_id'] .
                             '</td><td>' . $unit['total_space'] . 
                             '</td><td>' . $unit['balance']  . "<a class='btn btn-primary' href='payment.php?action=payMaint&conid=" . $unit['contract_id'] . "'> دفع</a></td></tr>";
                    } 
                ?>
            </tbody>
            </table>
            <?php
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
            
            $conClients = [];
            foreach ($data as $c) {
                echo $c['client_id'];
                $stmt = $db->prepare('SELECT * FROM contracts c 
                                     INNER JOIN maint m on c.contract_id = m.contract_id
                                      WHERE client_id = ?');
                $stmt->execute(array($c['client_id']));
                $result = $stmt->fetchAll();
                echo ' %%%%%%%%%%%%%%%%%%%%%%%%%%%% <pre>';
                print_r($result);
                echo '</pre>';
                
                /*$stmt = $db->prepare('SELECT * FROM contracts WHERE client_id = ?');
                $stmt->execute(array($c['client_id']));
                $count = $stmt->rowCount();
                
                $filteredData = $stmt->fetch();
                // Get maint info
                $stmtMaint = $db->prepare('SELECT * FROM maint WHERE contract_id = ?');
                $stmtMaint->execute(array($filteredData['contract_id']));
                $maint = $stmtMaint->fetch();
                // get unit info
                $stmtContract = $db->prepare('SELECT * FROM contract_units  
                INNER JOIN 
                units ON contract_units.unit_id = units.unit_id
                WHERE contract_id = ?');
                $stmtContract->execute(array($filteredData['contract_id']));
                $cont = $stmtContract->fetchAll();
                echo '<pre>';
                print_r($cont);
                echo '</pre>';
                $con_units = [];
                foreach ($cont as $unit) {
                    $stmt = $db->prepare('SELECT * FROM units WHERE unit_id = ?');
                    $stmt->execute(array($unit['unit_id']));
                    $stmt->fetchAll();
                    if ($stmt->rowCount() > 0) {
                        $con_units += array($unit['unit_name']);

                    }
                    echo '<pre>';
                    print_r($con_units);
                    echo '</pre>';
                }
                if ($count > 0 ) {
                    $conClients += array($c['client_id'] => $c + $filteredData + $maint + $cont);
                    
                }*/
                $reefs = [];
                $conUnits = [];
            foreach ($result as $u) {
                $cid = $u['contract_id'];
                //echo '---------------------------------------------' . $cid;
                $stmtUnits = $db->prepare('SELECT * FROM units u INNER JOIN
                contract_units cu on cu.unit_id = u.unit_id INNER JOIN
                reefs r ON u.reef_id = r.reef_id
                WHERE cu.contract_id = ?');
                $stmtUnits->execute(array($cid));
                $units = $stmtUnits->fetchAll();
                
                foreach ($units as $unit ) {
                    array_push($reefs,$unit['reef_name']);
                    array_push($conUnits,$unit['unit_name']);
                }
                echo '***********************<pre>';
                print_r($units);
                echo '</pre>';
                echo '+++++++++++++++++++++++<pre>';
                print_r($reefs);
                echo '</pre>';
                echo '$$$$$$$$$$$$$$$$$$$$$$$<pre>';
                print_r($conUnits);
                echo '</pre>';
                $conClients += array($u['client_id'] => $u + $reefs + $conUnits);
                echo '=======0990000 909090 <pre>';
                print_r($conUnits);
                echo '</pre>';
            }

            }
            
            echo '******************************************<pre>';
            print_r($conClients);
            echo '</pre>'
            ?>


            <!-- Report Start -->
            <div class="col-12">


                <div class="table-responsive-md">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                اسم القطعة
                            </th>
                            <th>
                                الريف
                            </th>
                            <th>
                            اسم العميل
                        
                            </th>
                            <th>
                                
                                رقم العقد  
                            </th>
                            
                            
                            <th>
                            المساحة
                            </th>
                            <th>
                                الرصيد
                            </th>
                            

                        </tr>
                        
                        </thead>
                        <tbody>
                        
                        <?php 
                        
                            $units_diplayed = implode("-",$conUnits);
                            $reefs_dislayed = implode("-",$reefs);

                            print_r($units_diplayed);
                            foreach($conClients as $r) {
                                echo "<tr><td>" . $r['contract_id']. 
                                "</td><td>" . $units_diplayed
                                . "</td><td>" . $reefs_dislayed . 
                                "</td><td>" . $r['client_id'] . 
                                "</td><td>" . $r['contract_id'] . "</td> <td>"
                                . $r['total_space'] . "</td> <td>"
                                . $r['balance'] . "<a class='badge badge-primary action-botton' href='payment.php?action=payMaint&conid=" . $r['contract_id'] . "'> دفع الصيانة</a></td></tr>";
                            
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





