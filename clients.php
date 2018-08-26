<?php 

session_start();
// Check if there's a session with the user name============================
if (isset($_SESSION['username'])) {
    include 'init.php';

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
        $limit = 2; // rows per page limit ----------------------------
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
        echo '<div class="container">';
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
                            <td><a href="#" class="btn btn-outline-info">تعديل</a><a href="#" class="btn btn-danger"> حذف</a></td>';

                        echo '</tr>';
                    }
                ?>

            </tbody>

        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                for ($page=1; $page <= $total_pages ; $page++):?>
                <li class="page-item">
                    <a href='<?php echo "?page=$page"; ?>' class="page-link"><?php  echo $page; ?>
                    </a>
                </li>
          
            <?php endfor; ?>
            </ul>
        </nav>

        <form action="clients.php?action=Add">
            <input type="submit" class="btn btn-outline-dark btn-lg" value="إضافة عميل جديد">
        </form>
        <?php
        echo '</div>';

        include $tbl . 'footer.php';
        } elseif ($action == 'Add') {
            
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
            }
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="text" class="form-control-lg" name='client_name' placeholder="أسم العميل"  />
                <input type="text" class="form-control-lg" name='phone' placeholder="تليفون العميل"  />
                <input type="email" class="form-control-lg" name='email' placeholder="الايميل"  />
                <input type="text" class="form-control-lg" name='address' placeholder="العنوان"  />
                <input type="submit" class="btn btn-outline-secondary" value="حفظ" />
            </form>
            <?php
            
            echo 'welcome to Add Page';
        } elseif ($action == 'Edit') {
            $pageTitle = "تعديل بيانات عميل";
            echo 'welcome to Edit page';
        }
    
    
} else {
    header('Location: index.php');
    exit();
}