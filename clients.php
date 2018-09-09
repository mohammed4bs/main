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

            $pageTitle = "العملاء";
        
        // Get all Clients==================================================
        $limit = 4; // rows per page limit ----------------------------
        $stmt = $db->prepare("SELECT * FROM clients");
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
        $show = "SELECT * FROM clients ORDER BY client_id ASC LIMIT $starting_limit, $limit";
        $rows = $db->prepare($show);
        $rows->execute();
        
        echo '<h1 class="page-title text-center"> عملاء الريف الاوروبي </h1>';
        
        ?>
        <table class="table table-striped table-hover ">
            <thead class="thead-dark">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        الأسم
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
                        تعديل أو حذف
                    </th>

                </tr>

            </thead>
            <tbody>
                <?php
                    foreach($rows as $row) {
                        echo '<tr>';
                            echo '<td>' . $row['client_id'] .  '</td><td>' . $row['client_name'] . '</td><td>' . $row['phone'] . '</td><td>' . $row['email'] . '</td><td>' . $row['address'] . '</td>
                            <td><a href="?action=Edit&id=' . $row['client_id'] . '" class="btn btn-outline-info">تعديل</a><a href="?action=Delete&id=' . $row['client_id'] . '" class="btn btn-danger confirm"> حذف</a></td>';

                        echo '</tr>';
                    }
                ?>

            </tbody>

        </table>
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

        
        <a href="?action=Add" class="btn btn-outline-dark btn-lg">إضافة عميل جديد</a>
        
        <?php
        

        include $tbl . 'footer.php';
        } elseif ($action == 'Add') {?>
            <h1 class="page-title text-center"> إضافة عميل جديد </h1>
            <form action="?action=Insert" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم العميل</label>
                        <input type="text" class="form-control col-8" name='client_name' required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> تليفون العميل</label>
                        <input type="text" class="form-control col-8" name='phone' required  />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> الايميل</label>
                        <input type="email" class="form-control col-8" name='email' required />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> العنوان</label>
                        <input type="text" class="form-control col-8" name='address'  required />
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
            $stmt = $db->prepare("SELECT * FROM clients WHERE client_id = :zid");
            $stmt->bindParam(":zid", $id);
            $stmt->execute();
            $row = $stmt->fetch();
            //print_r($row);
            ?>
            <h1 class="page-title text-center"> تعديل بيانات عميل </h1>
            <form action="?action=Update&id=<?php echo $id; ?>" method="POST">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> إسم العميل</label>
                        <input type="text" class="form-control col-8" name='client_name' value="<?php echo $row['client_name']; ?>" required  />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> تليفون العميل</label>
                        <input type="text" class="form-control col-8" name='phone' value="<?php echo $row['phone']; ?>" required  />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> الايميل</label>
                        <input type="email" class="form-control col-8" name='email' value="<?php echo $row['email']; ?>" required />
                    </div>
                    <div class="form-group col-6">
                        <label class="col-form-label col-4"> العنوان</label>
                        <input type="text" class="form-control col-8" name='address' value="<?php echo $row['address']; ?>"  required />
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
                $client_name = $_POST['client_name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $address = $_POST['address'];

                $stmt = $db->prepare('UPDATE clients SET client_name = ? , phone = ? , email = ? , address = ? WHERE client_id = '. $id);
                $stmt->execute(array($client_name , $phone, $email , $address));
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'عميل جديد</div>';
                echo '<a href="clients.php?page=1" class=" btn btn-group-vertical"> رجوع الي صفحة العملاء</a>';
                
            } else {
                echo 'You can\'t browse this page directly';
            }
        // Update Page End ====================================================================================================================
        // Insert Page Start ====================================================================================================================
        } elseif ($action == 'Insert') {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $pageTitle = "إضافة عميل جديد";
                $client_name = $_POST['client_name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $address = $_POST['address'];

                $stmt = $db->prepare('INSERT INTO clients (client_name,phone,email,address) values (:client , :phone, :email ,:address)');
                $stmt->execute(array(
                    'client' => $client_name,
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