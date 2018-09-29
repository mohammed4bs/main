<?php 

session_start();
// Check if there's a session with the user name============================
if (isset($_SESSION['username'])) {
    include 'init.php';
    echo '<div class="container">';
    // check if thers's an action if not go to manage units=================================
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = "Manage";
    }
     
        // Manage Page Start here !!!======================================
        if ($action == 'Manage') {

            $pageTitle = "الاراضي";
        
        // Get all units==================================================
        $limit = 4; // rows per page limit ----------------------------
        $stmt = $db->prepare("SELECT * FROM contracts INNER JOIN contract_units ON contracts.contract_id = contract_units.contract_id ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        //print_r($rows);
        // Pagination Setup ==============================================
        $total_result = $stmt->rowCount();
        $total_pages = ceil($total_result/$limit);
       

        if(!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $starting_limit = ($page-1)*$limit;
        $show = "SELECT * FROM contracts INNER JOIN contract_units ON contracts.contract_id = contract_units.contract_id ORDER BY contracts.contract_id ASC LIMIT $starting_limit, $limit";
        $rows = $db->prepare($show);
        $rows->execute();
        
        echo '<h1 class="page-title text-center"> العقود </h1>';
        
        ?>
        <table class="table table-striped table-hover ">
            <thead class="thead-dark">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        الوصف
                    </th>
                    <th>
                        اسم العميل
                    </th>
                    <th>
                        اسم القطعة
                    </th>
                    <th>
                        نوع العقد
                    </th>
                    <th>
                        المساحة
                    </th>
                    <th>
                        الرصيد
                    </th>
                    <th>
                        التاريخ
                    </th>
                    <th>
                        إضافة قطعة اخري
                    </th>
                    <th>
                        حذف أو تعديل
                    </th>
                    

                </tr>

            </thead>
            <tbody>
                <?php
                    
                    foreach($rows as $row) {
                        $stmt = $db->prepare('SELECT * FROM clients WHERE client_id = ? LIMIT 1');
                        $stmt->execute(array($row['client_id']));
                        $client = $stmt->fetch();
                        $contract_kind ='';
                        if ($row['contract_kind'] == '0') {
                            $contract_kind  = 'من الشركة';
                        } elseif ($row['contract_kind'] == '1') {
                            $contract_kind  = 'تنازل';
                        }

                        $stmt = $db->prepare('SELECT unit_space FROM contract_units WHERE contract_id = ?');
                        $stmt->execute(array($row['contract_id']));
                        $unit_spaces = $stmt->fetchAll();
                        $total_space = 0.0;
                        foreach($unit_spaces as $space) {
                            $total_space += $space['unit_space'];
                        }
                        $stmt = $db->prepare('SELECT balance FROM maint WHERE contract_id = ?');
                                          $stmt->execute(array($row['contract_id'] ));
                                          $b = $stmt->fetch();
                        echo '<tr>';
                            echo '<td>' . $row['contract_id'] .  '</td><td>'
                             . $row['description'] . '</td><td>' 
                             . $client['client_name'] .
                            '</td><td>' . $row['unit_id'] . 
                            '</td><td>' . $contract_kind  . 
                            '</td><td>' . $total_space . 
                            '</td><td>' . 
                                          $b['balance'] .
                            '</td><td>' . $row['contract_date'] . 
                            '</td><td><a href="?action=addUnit&id=' . $row['contract_id'] . 
                            '" class="btn btn-outline-info">إضافة قطعة</a></td><td><a href="?action=Edit&id=' . $row['contract_id'] . 
                            '" class="btn btn-outline-info">تعديل</a><a href="?action=Delete&id=' . 
                            $row['contract_id'] . '" class="btn btn-danger confirm"> حذف</a></td>';

                        echo '</tr>';
                    }
                ?>

            </tbody>

        </table>
        <!--    =====================================  Pagination ================================== -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
            <?php $past_page = $_GET['page'] - 1;
                if ($past_page < 1) {
                    $past_page = 1;
                    $past_page_status = 'disabled';
                }    
            ?>
                <li class="page-item <?php echo $past_page_status; ?>">
                    <a href='<?php echo "?page=$past_page"; ?>' class="page-link">السابق
                    </a>
                </li>
                <?php
                for ($page=1; $page <= $total_pages ; $page++):?>
                <li class="page-item">
                    <a href='<?php echo "?page=$page"; ?>' class="page-link <?php if ($_GET['page'] == $page) { echo 'current';} ?>"><?php  echo $page; ?>
                    </a>
                </li>

          
            <?php endfor; ?>
            <?php $next_page = $_GET['page'] + 1;
                  $status = '';
                if ($next_page > $total_pages) {
                    $next_page = $_GET['page'];
                    $next_page_status = 'disabled';

                }    
            ?>
                <li class="page-item  <?php echo $next_page_status; ?>"> <!-- this for disableing the next button when last page occured -->
                    <a href='<?php echo "?page=$next_page"; ?>' class="page-link">التالي
                    </a>
                </li>
            </ul>
        </nav>

        
        <a href="?action=Add" class="btn btn-outline-dark btn-lg">إضافة عقد جديد</a>
        
        <?php
        

        include $tbl . 'footer.php';
        } elseif ($action == 'Add') { ?>
            <h1 class="page-title text-center"> إضافة عقد جديد </h1>

                
            <form action="?action=Insert" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> وصف العقد</label>
                        <input type="text" class="form-control col-8" name='description' required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم العميل</label>
                        <input id="search" onkeyup="filter()" class="form-control" autocomplete="off" />
                        <select id="select" size="5" class="custom-select" name="client_id">
                            <option selected>. . . . </option>
                        
                        
                        
                            <?php $stmt = $db->prepare('SELECT * FROM clients');
                              $stmt->execute();
                              $clients = $stmt->fetchAll();  
                              
                              foreach ($clients as $client) {
                                  echo '<option value="' . $client['client_id'] . '">' . $client['client_name'] . '</option>';
                              } 
                              ?> 
                        </select>
                    </div>
                </div>
                <div class="row">
                
                   
                    <div class="form-group col-2">
                        <label class="col-form-label col-8">اسم الشركة</label>
                        <select class="custom-select" id="comp" name="company_id">
                            <option selected>اختر شركة</option>
                            
                            <?php $stmt = $db->prepare('SELECT * FROM company');
                            $stmt->execute();
                            $companies = $stmt->fetchAll();  
                                
                            foreach ($companies as $company) {
                                   echo '<option value="' . $company['company_id'] . '">' . $company['company_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                   
                    
                    <div class="form-group col-2">
                    <label class="col-form-label col-8"> إسم الريف</label>
                        <select class="custom-select" id="reef" name="reef_id">
                            <option selected value="">اختر ريف</option>
                            <?php /*$stmt = $db->prepare('SELECT * FROM reefs');
                              $stmt->execute();
                              $reefs = $stmt->fetchAll();  
                              
                              foreach ($reefs as $reef) {
                                 echo '<option value="' . $reef['reef_id'] . '">' . $reef['reef_name'] . '</option>';
                              }*/
                              ?>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label col-8"> رقم القطعة</label>
                        <select class="custom-select" id="unit" name="unit_id">
                            <option selected>اختر قطعة</option>
                        
                        
                        
                            <?php /*$stmt = $db->prepare('SELECT * FROM units');
                              $stmt->execute();
                              $units = $stmt->fetchAll();  
                              
                              foreach ($units as $unit) {
                                  echo '<option value="' . $unit['unit_id'] . '">' . $unit['unit_name'] . '</option>';
                              }*/
                              ?>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> نوع العقد</label>
                        <select class="custom-select" name="contract_kind">
                            <option selected>نوع العقد</option>
                        
                        
                        
                            
                            <option value="0">شراء من الشركة</option>
                            <option value="1">تنازل من عميل</option>
                             
                             
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-4">
                        <label class="col-form-label col-4"> المساحة</label>
                        <select class="custom-select" name="space">
                            <option selected>اختر المساحة</option>
                            <option value="0.5">نصف فدان</option>
                            <option value="1">فدان</option>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label class="col-form-label col-6"> رصيد الصيانة</label>
                        
                        <input type="text" class="form-control col-8" name="balance" />
                    </div>
                    
                    <div class="form-group col-4">
                        <label class="col-form-label col-4"> التاريخ</label>
                        
                        <input type="date" class="form-control col-8" name="date" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-4">
                        <label class="col-form-label col-6"> رصيد الكهرباء</label>
                        
                        <input type="text" class="form-control col-8" name="elec_balance" />
                    </div>
                    <div class="form-group col-4">
                        <label class="col-form-label col-6"> آخر قراءة</label>
                        
                        <input type="text" class="form-control col-8" name="prev_reading" />
                    </div>
                </div>
                
                
                <div class="form-group">
                    
                    <input type="submit" class="btn btn-lg btn-outline-secondary" value="حفظ" />
                </div>
            </form>
            <?php
            
        // Edit Page Start ===================================================================================================================
        }elseif($action == 'addUnit') {
            
            ?>
            <h1 class="page-title text-center"> إضافة قطعة لعقد </h1>
            <?php
            $id = $_GET['id'];
            $show = "SELECT * FROM contracts WHERE contract_id = ? LIMIT 1";
            $rows = $db->prepare($show);
            $rows->execute(array($id));
            $cont = $rows->fetch();
            
            ?>
            <table class="table table-striped table-hover ">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            الوصف
                        </th>
                        <th>
                            اسم العميل
                        </th>
                        
                        <th>
                            نوع العقد
                        </th>
                        <th>
                            المساحة
                        </th>
                        <th>
                            التاريخ
                        </th>
                        
                        

                    </tr>

                </thead>
                <tbody>
                    <?php
                        
                        
                            $stmt = $db->prepare('SELECT * FROM clients WHERE client_id = ? LIMIT 1');
                            $stmt->execute(array($cont['client_id']));
                            $client = $stmt->fetch();
                            $contract_kind ='';
                            if ($cont['contract_kind'] == '0') {
                                $contract_kind  = 'من الشركة';
                            } elseif ($cont['contract_kind'] == '1') {
                                $contract_kind  = 'تنازل';
                            }

                            $stmt = $db->prepare('SELECT unit_space FROM contract_units WHERE contract_id = ?');
                            $stmt->execute(array($cont['contract_id']));
                            $unit_spaces = $stmt->fetchAll();
                            $total_space = 0;
                            foreach($unit_spaces as $space) {
                                $total_space += $space['unit_space'];
                            }
                            echo '<tr>';
                                echo '<td>' . $cont['contract_id'] .  '</td><td>' . $cont['description'] . '</td><td>' . $client['client_name'] .
                                '</td><td>' . $contract_kind  . '</td><td>' . 
                                $total_space . '</td><td>' . $cont['contract_date'] . '</td>';

                            echo '</tr>';
                        
                    ?>

                </tbody>

            </table>
            <?php 
                $url = "?action=insertUnit&id=" . $cont['contract_id']; 
            ?>
            <form action="<?php echo $url ?>"  method="post">
                <div class="row">
                    <input type="hidden" name="id" value="<?php $cont['contract_id']  ?>" />
                    <div class="form-group col-2">
                                <label class="col-form-label col-4">اسم الشركة</label>
                                <select class="custom-select" id="comp" name="company_id">
                                    <option selected>اختر شركة</option>
                                    
                                    <?php $stmt = $db->prepare('SELECT * FROM company');
                                    $stmt->execute();
                                    $companies = $stmt->fetchAll();  
                                        
                                    foreach ($companies as $company) {
                                        echo '<option value="' . $company['company_id'] . '">' . $company['company_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                        
                            
                            <div class="form-group col-2">
                            <label class="col-form-label col-4"> إسم الريف</label>
                                <select class="custom-select" id="reef" name="reef_id">
                                    <option selected value="">اختر ريف</option>
                                    <?php /*$stmt = $db->prepare('SELECT * FROM reefs');
                                    $stmt->execute();
                                    $reefs = $stmt->fetchAll();  
                                    
                                    foreach ($reefs as $reef) {
                                        echo '<option value="' . $reef['reef_id'] . '">' . $reef['reef_name'] . '</option>';
                                    }*/
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label class="col-form-label col-4"> رقم القطعة</label>
                                <select class="custom-select" id="unit" name="unit_id">
                                    <option selected>اختر قطعة</option>
                                
                                
                                
                                    <?php /*$stmt = $db->prepare('SELECT * FROM units');
                                    $stmt->execute();
                                    $units = $stmt->fetchAll();  
                                    
                                    foreach ($units as $unit) {
                                        echo '<option value="' . $unit['unit_id'] . '">' . $unit['unit_name'] . '</option>';
                                    }*/
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label class="col-form-label col-4"> مساحة القطعة</label>
                                <select class="custom-select" name="space">
                                    <option selected>اختر المساحة</option>
                                    <option value="0.5">نصف فدان</option>
                                    <option value="1">فدان</option>
                                </select>
                            </div>
                            
                </div>
                <div class="row">
                    <div class="form-group col-4">
                        <input type="submit" class="btn btn-lg btn-outline-secondary" value="حفظ" />
                    </div>
                </div>
            </form>
            <?php
        }elseif($action == 'insertUnit') {
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo 'welcome to insert unit';

                $id =  $_GET['id'];
                $unit_id = $_POST['unit_id'];
                $space = $_POST['space'];
                // Add space to total_space
                $stmt = $db->prepare('SELECT total_space FROM contracts WHERE contract_id = ?');
                $stmt->execute(array($id));
                $spc = $stmt->fetch();
                $total_space = $spc['total_space'];
                $space = $_POST['space'];
                $total_space += $space;
                echo $space;
                echo '<h1>' . $total_space . '</h1>';

                $stmt = $db->prepare('INSERT INTO contract_units (contract_id,unit_id,unit_space) 
                VALUES (:cont,:unid,:spid)');
                $stmt->execute(array(
                    ':cont' => $id,
                    ':unid' => $unit_id,
                    ':spid' => $space
                ));

                $stmt = $db->prepare('UPDATE contracts SET total_space = ? WHERE contract_id = ?');
                $stmt->execute(array($total_space, $id));
                echo '<h1>Done</h1>';
            } else {
                header('Location: contracts.php?page=1');
                exit();
            }

        } elseif ($action == 'Edit') {
            $pageTitle = "تعديل بيانات عقد";
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM contracts WHERE contract_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $row = $stmt->fetch();
            print_r($row);
            ?>
            <h1 class="page-title text-center"> تعديل بيانات عقد </h1>
            <form action="?action=Update&id=<?php echo $id; ?>" method="POST">
            <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> وصف العقد</label>
                        <input type="text" class="form-control col-8" name='description' value="<?php echo $row['description'] ?>" required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم العميل</label>
                        <input id="search" onkeyup="filter()" class="form-control" autocomplete="off" />
                        <select id="select" size="5" class="custom-select" name="client_id">
                            <?php
                            // Get client name by his ID
                            $stmt = $db->prepare('SELECT * FROM clients WHERE client_id = ?');
                              $stmt->execute(array($row['client_id']));
                              $client = $stmt->fetch();  
                              ?>
                            <option selected value="<?php echo $client['client_id'] ?>"> <?php echo $client['client_name']; ?></option>
                        
                        
                        
                            <?php $stmt = $db->prepare('SELECT * FROM clients');
                              $stmt->execute();
                              $clients = $stmt->fetchAll();  
                              
                              foreach ($clients as $client) {
                                  echo '<option value="' . $client['client_id'] . '">' . $client['client_name'] . '</option>';
                              } 
                              ?> 
                        </select>
                    </div>
                </div>
                <div class="row">
                
                    <div class="form-group col-2">
                        <label class="col-form-label col-4">اسم الشركة</label>
                        <select class="custom-select" id="comp" name="company_id">
                            <option selected>اختر شركة</option>
                            
                            <?php $stmt = $db->prepare('SELECT * FROM company');
                            $stmt->execute();
                            $companies = $stmt->fetchAll();  
                                
                            foreach ($companies as $company) {
                                   echo '<option value="' . $company['company_id'] . '">' . $company['company_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                   
                    
                    <div class="form-group col-2">
                    <label class="col-form-label col-4"> إسم الريف</label>
                        <select class="custom-select" id="reef" name="reef_id">
                            <option selected value="">اختر ريف</option>
                            <?php /*$stmt = $db->prepare('SELECT * FROM reefs');
                              $stmt->execute();
                              $reefs = $stmt->fetchAll();  
                              
                              foreach ($reefs as $reef) {
                                 echo '<option value="' . $reef['reef_id'] . '">' . $reef['reef_name'] . '</option>';
                              }*/
                              ?>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <label class="col-form-label col-4"> رقم القطعة</label>
                        <select class="custom-select" id="unit" name="unit_id">
                        <?php
                            $stmt = $db->prepare('SELECT unit_name FROM units WHERE unit_id = ?');
                            $stmt->execute(array($row['unit_id']));
                            $unit = $stmt->fetch();
                        ?>
                            <option selected value="<?php $row['unit_id'] ?>"><?php echo $unit['unit_name']; ?> </option>
                        
                        
                        
                            <?php /*$stmt = $db->prepare('SELECT * FROM units');
                              $stmt->execute();
                              $units = $stmt->fetchAll();  
                              
                              foreach ($units as $unit) {
                                  echo '<option value="' . $unit['unit_id'] . '">' . $unit['unit_name'] . '</option>';
                              }*/
                              ?>
                        </select>
                    </div>
                              
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> نوع العقد</label>
                        <select class="custom-select" name="contract_kind">
                            <?php 
                            if ($row['contract_kind'] == 0) {
                                echo '<option selected value="' .  $row['contract_kind'] . '">شراء من الشركة</option>';
                            }else {
                                echo '<option selected value="' .  $row['contract_kind'] . '">تنازل من عميل</option>';
                            }  ?> 
                            
                        
                        
                        
                            
                            <option value="0">شراء من الشركة</option>
                            <option value="1">تنازل من عميل</option>
                             
                             
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> المساحة</label>
                        <select class="custom-select" name="space">
                            <option selected value="<?php $row['space'] ?>"> <?php echo $row['total_space']; ?></option>
                        
                        
                        
                            
                            <option value="0.5">0.5</option>
                            <option value="1">1</option>
                            
                             
                             
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> التاريخ</label>
                        
                        <input type="date" class="form-control col-8" name="date" value="<?php echo $row['contract_date'] ?>" />
                    </div>
                </div>
            
                <div class="form-group">
                    
                    <input type="submit" class="btn btn-lg btn-outline-secondary" value="حفظ" />
                </div>
            </form>
            <?php
        // Edit Page End ====================================================================================================================
        // Update Page Start ====================================================================================================================
        } elseif ($action == 'Update') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="page-title text-center"> تعديل بيانات عقد </h1>';

                $id = $_GET['id'];
                $unit_name = $_POST['unit_name'];
                $reef_id = $_POST['reef_id'];
                

                $stmt = $db->prepare('UPDATE units SET unit_name = ? , reef_id = ? WHERE unit_id = '. $id);
                $stmt->execute(array($unit_name,$reef_id));
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'عقد جديد</div>';
                echo '<a href="units.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة الأراضي</a>';
                
            } else {
                echo 'You can\'t browse this page directly';
            }
        // Update Page End ====================================================================================================================
        // Insert Page Start ====================================================================================================================
        } elseif ($action == 'Insert') {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $pageTitle = "إضافة عقد جديد";
                // contracts table prop

                $description = $_POST['description'];
                $client_id = $_POST['client_id'];
                $contract_kind = $_POST['contract_kind'];
                $space = $_POST['space'];
                $date = $_POST['date'];
               
                // maintainance table prop
                $start_date = $date;
                $balance = $_POST['balance'];
                $end_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)). "next day"));
                
                echo $end_date;

                // elec table prop
                $elec_balance = $_POST['elec_balance'];
                $prev_reading = $_POST['prev_reading'];
                $rate = 1.45;

                
                // contract_units prop
                $unit_id = $_POST['unit_id'];
                $total_space = $space;
                
                // calculate total space
                $stmt = $db->prepare('SELECT unit_space FROM contract_units WHERE contract_id = ?');
                        $stmt->execute(array($db->lastInsertId()));
                        $unit_spaces = $stmt->fetchAll();
                        foreach($unit_spaces as $space) {
                        $total_space += $space['unit_space'];
                }
                
                // Insert contract tbl
                $stmt = $db->prepare('INSERT INTO contracts (description,client_id,
                contract_kind , total_space ,contract_date  ) values (:descr , :cid , 
                :conknd , :tsp, :dt)');
                
                $stmt->execute(array(
                    ':descr' => $description,
                    ':cid' => $client_id,
                    ':conknd' => $contract_kind,
                    ':tsp' => $total_space,
                    ':dt' => $date
                ));
                
                $cont_id = $db->lastInsertId();
                
                // Insert contract_units tbl
                $stmtUnits = $db->prepare('INSERT INTO contract_units (contract_id, unit_id,unit_space) VALUES (:last_id,:u,:s)');
                $stmtUnits->execute(array(
                    ':last_id' => $cont_id,
                    ':u' => $unit_id,
                    ':s' => $space
                ));

                // Insert Maintainance table
                $stmtMaint = $db->prepare('INSERT INTO maint (contract_id ,balance , start_date, end_date) 
                VALUES (:coid, :blc , :sdt, :edt)');
                $stmtMaint->execute(array(
                    ':coid' => $cont_id,
                    ':blc'  => $balance,
                    ':sdt'  => $date,
                    ':edt'  => $end_date
                ));

                $stmtElec = $db->prepare('INSERT INTO elec 
                (contract_id , prev_reading, prev_reading_date, balance) 
                VALUES (:con,:prev,:prev_dt,:eblnc)');
                $stmtElec->execute(array(
                    ':con'      => $cont_id,
                    ':prev'     => $prev_reading,
                    ':prev_dt'  => date('Y-m-d'),
                    ':eblnc'    => $elec_balance
                ));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'عقد جديد</div>';
                echo '<a href="contracts.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة العقود</a>';
                echo '<a href="contracts.php?action=Add" class=" btn btn-secondary"> إضافة عقد آخر</a>';
            } else {
                header('Location: contracts.php');
                exit();
            }
            
        } elseif ($action == 'Delete') {
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM contracts WHERE contract_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count > 0) {
                $stmt = $db->prepare('DELETE FROM contracts WHERE contract_id = :zid');
                $stmt->bindParam(":zid", $id);
                $stmt->execute();
                echo '<h1 class="page-title text-center">تم الحذف</h1>';
                echo '<div class="alert alert-success" role="alret"> تم حذف بيانات' . $stmt->rowCount() . 'عقد </div>';
                echo '<a href="contracts.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة العقود</a>';
            }
        }
        // Insert Page End ====================================================================================================================
        echo '</div>';
    include $tbl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}