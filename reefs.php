<?php 

session_start();
// Check if there's a session with the user name============================
if (isset($_SESSION['username'])) {
    include 'init.php';
    echo '<div class="container">';
    // check if thers's an action if not go to manage clients=================================
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = "Manage";
    }
     
        // Manage Page Start here !!!======================================
        if ($action == 'Manage') {

            $pageTitle = "الارياف";
        
        // Get all Clients==================================================
        $limit = 4; // rows per page limit ----------------------------
        $stmt = $db->prepare("SELECT * FROM reefs");
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
        $show = "SELECT * FROM reefs ORDER BY reef_id ASC LIMIT $starting_limit, $limit";
        $rows = $db->prepare($show);
        $rows->execute();
        
        echo '<h1 class="page-title text-center"> أرياف الريف الاوروبي </h1>';
        
        ?>
        <table class="table table-striped table-hover ">
            <thead class="thead-dark">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        اسم الريف
                    </th>
                    <th>
                        حذف أو تعديل
                    </th>

                </tr>

            </thead>
            <tbody>
                <?php
                    foreach($rows as $row) {
                        echo '<tr>';
                            echo '<td>' . $row['reef_id'] .  '</td><td>' . $row['reef_name'] . 
                            '<td><a href="?action=Edit&id=' . $row['client_id'] . '" class="btn btn-outline-info">تعديل</a><a href="?action=Delete&id=' . $row['client_id'] . '" class="btn btn-danger confirm"> حذف</a></td>';

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

        
        <a href="?action=Add" class="btn btn-outline-dark btn-lg">إضافة ريف جديد</a>
        
        <?php
        

        include $tbl . 'footer.php';
        } elseif ($action == 'Add') {?>
            <h1 class="page-title text-center"> إضافة ريف جديد </h1>
            <form action="?action=Insert" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم الريف</label>
                        <input type="text" class="form-control-lg col-8" name='reef_name' required  />
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <input type="submit" class="btn btn-lg btn-outline-secondary" value="حفظ" />
                </div>
            </form>
            <?php
            
        // Edit Page Start ===================================================================================================================
        } elseif ($action == 'Edit') {
            $pageTitle = "تعديل بيانات عميل";
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM reefs WHERE reef_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $row = $stmt->fetch();
            //print_r($row);
            ?>
            <h1 class="page-title text-center"> تعديل بيانات عميل </h1>
            <form action="?action=Update&id=<?php echo $id; ?>" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم الريف</label>
                        <input type="text" class="form-control-lg col-8" name='reef_name' value="<?php echo $row['reef_name']; ?>" required  />
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
                echo '<h1 class="page-title text-center"> تعديل بيانات عميل </h1>';

                $id = $_GET['id'];
                $reef_name = $_POST['reef_name'];
                

                $stmt = $db->prepare('UPDATE reefs SET reef_name = ? WHERE client_id = '. $id);
                $stmt->execute(array($reef_name));
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'ريف جديد</div>';
                echo '<a href="clients.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة الأرياف</a>';
                
            } else {
                echo 'You can\'t browse this page directly';
            }
        // Update Page End ====================================================================================================================
        // Insert Page Start ====================================================================================================================
        } elseif ($action == 'Insert') {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $pageTitle = "إضافة عميل جديد";
                $reef_name = $_POST['reef_name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $address = $_POST['address'];

                $stmt = $db->prepare('INSERT INTO clients (reef_name,phone,email,address) values (:client , :phone, :email ,:address)');
                $stmt->execute(array(
                    'client' => $reef_name,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $address));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'عميل جديد</div>';
                echo '<a href="clients.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة العملاء</a>';
            } else {
                header('Location: clients.php');
                exit();
            }
            
        } elseif ($action == 'Delete') {
            $id = $_GET['id']; 
            $stmt = $db->prepare("SELECT * FROM clients WHERE client_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count > 0) {
                $stmt = $db->prepare('DELETE FROM clients WHERE client_id = :zid');
                $stmt->bindParam(":zid", $id);
                $stmt->execute();
                echo '<h1 class="page-title text-center">تم الحذف</h1>';
                echo '<div class="alert alert-success" role="alret"> تم حذف بيانات' . $stmt->rowCount() . 'عميل </div>';
                echo '<a href="clients.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة العملاء</a>';
            }
        }
        // Insert Page End ====================================================================================================================
        echo '</div>';
    
} else {
    header('Location: index.php');
    exit();
}?>