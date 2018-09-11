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
                        $total_space = 0;
                        foreach($unit_spaces as $space) {
                            $total_space += $space['unit_space'];
                        }
                        echo '<tr>';
                            echo '<td>' . $row['contract_id'] .  '</td><td>' . $row['description'] . '</td><td>' . $client['client_name'] .
                            '</td><td>' . $row['unit_id'] . '</td><td>' . $contract_kind  . '</td><td>' . 
                            $total_space . '</td><td>' . $row['date'] . '</td><td><a href="?action=addUnit&id=' . $row['contract_id'] . 
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
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> المساحة</label>
                        <select class="custom-select" name="space">
                            <option selected>اختر المساحة</option>
                        
                        
                        
                            
                            <option value="0.5">نصف فدان</option>
                            <option value="1">فدان</option>
                            <option value="1.5">فدان ونصف</option>
                            <option value="2">فدانان</option>
                            <option value="2.5">2.5</option>
                            <option value="3"> 3</option>
                            <option value="3.5">3.5</option>
                            <option value="4">4</option>
                            <option value="4.5">4.5</option>
                            <option value="5">5</option>
                            <option value="5.5">5.5</option>
                             
                             
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> التاريخ</label>
                        
                        <input type="date" class="form-control col-8" name="date" />
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
                                $total_space . '</td><td>' . $cont['date'] . '</td>';

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
                echo '<h1>' . $id . '</h1>';

                $stmt = $db->prepare('INSERT INTO contract_units (contract_id,unit_id,unit_space) 
                VALUES (:cont,:unid,:spid)');
                $stmt->execute(array(
                    ':cont' => $id,
                    ':unid' => $unit_id,
                    ':spid' => $space
                ));
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
                            <option value="1.5">1.5</option>
                            <option value="2">2</option>
                            <option value="2.5">2.5</option>
                            <option value="3"> 3</option>
                            <option value="3.5">3.5</option>
                            <option value="4">4</option>
                            <option value="4.5">4.5</option>
                            <option value="5">5</option>
                            <option value="5.5">5.5</option>
                             
                             
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> التاريخ</label>
                        
                        <input type="date" class="form-control col-8" name="date" value="<?php echo $row['date'] ?>" />
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
                $description = $_POST['description'];
                $client_id = $_POST['client_id'];
                $contract_kind = $_POST['contract_kind'];
                $space = $_POST['space'];
                $date = $_POST['date'];
                $unit_id = $_POST['unit_id'];
                $total_space = $space;
                $stmt = $db->prepare('SELECT unit_space FROM contract_units WHERE contract_id = ?');
                        $stmt->execute(array($db->lastInsertId()));
                        $unit_spaces = $stmt->fetchAll();
                        foreach($unit_spaces as $space) {
                        $total_space += $space['unit_space'];
                }
                

                $stmt = $db->prepare('INSERT INTO contracts (description,client_id,
                contract_kind , total_space ,date  ) values (:descr , :cid , 
                :conknd , :tsp, :dt)');
                
                $stmt->execute(array(
                    ':descr' => $description,
                    ':cid' => $client_id,
                    ':conknd' => $contract_kind,
                    ':tsp' => $total_space,
                    ':dt' => $date
                ));
                print $db->lastInsertId();
                $stmtUnits = $db->prepare('INSERT INTO contract_units (contract_id, unit_id,unit_space) VALUES (:last_id,:u,:s)');
                $stmtUnits->execute(array(
                    ':last_id' => $db->lastInsertId(),
                    ':u' => $unit_id,
                    ':s' => $space
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