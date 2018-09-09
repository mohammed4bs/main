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
        $stmt = $db->prepare("SELECT * FROM units");
        $stmt->execute();
        //$rows = $stmt->fetchAll();
        // Pagination Setup ==============================================
        $total_result = $stmt->rowCount();
        $total_pages = ceil($total_result/$limit);
       

        if(!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $starting_limit = ($page-1)*$limit;
        $show = "SELECT * FROM units ORDER BY unit_id ASC LIMIT $starting_limit, $limit";
        $rows = $db->prepare($show);
        $rows->execute();
        
        echo '<h1 class="page-title text-center"> أراضي القطعة الاوروبي </h1>';
        
        ?>
        <table class="table table-striped table-hover ">
            <thead class="thead-dark">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        رقم القطعة
                    </th>
                    <th>
                        اسم القطعة
                    </th>
                    <th>
                        حذف أو تعديل
                    </th>

                </tr>

            </thead>
            <tbody>
                <?php
                    
                    foreach($rows as $row) {
                        $stmt = $db->prepare('SELECT * FROM reefs WHERE reef_id = ? LIMIT 1');
                        $stmt->execute(array($row['reef_id']));
                        $reef = $stmt->fetch();
                        echo '<tr>';
                            echo '<td>' . $row['unit_id'] .  '</td><td>' . $row['unit_name'] . '</td><td>' . $reef['reef_name'] .
                            '</td><td><a href="?action=Edit&id=' . $row['unit_id'] . '" class="btn btn-outline-info">تعديل</a><a href="?action=Delete&id=' . $row['unit_id'] . '" class="btn btn-danger confirm"> حذف</a></td>';

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

        
        <a href="?action=Add" class="btn btn-outline-dark btn-lg">إضافة قطعة جديد</a>
        
        <?php
        

        include $tbl . 'footer.php';
        } elseif ($action == 'Add') {?>
            <h1 class="page-title text-center"> إضافة قطعة جديد </h1>
            <form action="?action=Insert" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم القطعة</label>
                        <input type="text" class="form-control col-8" name='unit_name' required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم الريف</label>
                        <select class="custom-select" name="reef_id">
                            <option selected>اختر ريف</option>
                            <?php $stmt = $db->prepare('SELECT * FROM reefs');
                              $stmt->execute();
                              $reefs = $stmt->fetchAll();  
                              
                              foreach ($reefs as $reef) {
                                  echo '<option value="' . $reef['reef_id'] . '">' . $reef['reef_name'] . '</option>';
                              }
                              ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <input type="submit" class="btn btn-lg btn-outline-secondary" value="حفظ" />
                </div>
            </form>
            <?php
            
        // Edit Page Start ===================================================================================================================
        } elseif ($action == 'Edit') {
            $pageTitle = "تعديل بيانات قطعة";
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM units WHERE unit_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $row = $stmt->fetch();
            //print_r($row);
            ?>
            <h1 class="page-title text-center"> تعديل بيانات قطعة </h1>
            <form action="?action=Update&id=<?php echo $id; ?>" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم القطعة</label>
                        <input type="text" class="form-control col-8" name='unit_name' value="<?php echo $row['unit_name']; ?>" required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم الشركة</label>
                        <select class="custom-select" name="reef_id">
                        <?php $stmt = $db->prepare('SELECT * FROM reefs WHERE reef_id = ? LIMIT 1');
                              $stmt->execute(array($row['reef_id']));
                              $reef = $stmt->fetch();  ?>
                            <option value='<?php $row['reef_id'] ?>' selected><?php echo $reef['reef_name'];  ?> </option>
                        
                        
                        
                            <?php $stmt = $db->prepare('SELECT * FROM reefs');
                              $stmt->execute();
                              $reefs = $stmt->fetchAll();  
                              
                              foreach ($reefs as $reef) {
                                  echo '<option value="' . $reef['reef_id'] . '">' . $reef['reef_name'] . '</option>';
                              }
                              ?>
                        </select>
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
                echo '<h1 class="page-title text-center"> تعديل بيانات قطعة </h1>';

                $id = $_GET['id'];
                $unit_name = $_POST['unit_name'];
                $reef_id = $_POST['reef_id'];
                

                $stmt = $db->prepare('UPDATE units SET unit_name = ? , reef_id = ? WHERE unit_id = '. $id);
                $stmt->execute(array($unit_name,$reef_id));
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'قطعة جديد</div>';
                echo '<a href="units.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة الأراضي</a>';
                
            } else {
                echo 'You can\'t browse this page directly';
            }
        // Update Page End ====================================================================================================================
        // Insert Page Start ====================================================================================================================
        } elseif ($action == 'Insert') {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $pageTitle = "إضافة قطعة جديد";
                $unit_name = $_POST['unit_name'];
                $reef_id = $_POST['reef_id'];
               

                $stmt = $db->prepare('INSERT INTO units (unit_name,reef_id) values (:unit_name , :reef_id)');
                $stmt->execute(array(
                    ':unit_name' => $unit_name,
                    ':reef_id' => $reef_id
                    ));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'قطعة جديد</div>';
                echo '<a href="units.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة الأراضي</a>';
            } else {
                header('Location: units.php');
                exit();
            }
            
        } elseif ($action == 'Delete') {
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM units WHERE unit_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count > 0) {
                $stmt = $db->prepare('DELETE FROM units WHERE unit_id = :zid');
                $stmt->bindParam(":zid", $id);
                $stmt->execute();
                echo '<h1 class="page-title text-center">تم الحذف</h1>';
                echo '<div class="alert alert-success" role="alret"> تم حذف بيانات' . $stmt->rowCount() . 'قطعة </div>';
                echo '<a href="units.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة الأراضي</a>';
            }
        }
        // Insert Page End ====================================================================================================================
        echo '</div>';
    
} else {
    header('Location: index.php');
    exit();
}?>