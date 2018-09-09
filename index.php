<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: dashboard.php');
        exit();
    }
    $pageTitle = 'تسجيل الدخول';
    $noNavbar = '';
    print_r($_SESSION);
    include "init.php";
    include "settings/fee.php";
    include $tbl . "header.php";


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        $stmt = $db->prepare('SELECT user_id, username, password FROM users WHERE username = ? AND password = ? AND group_id = 1 LIMIT 1');
        $stmt->execute(array($username , $password));
        $row = $stmt->rowCount();
        
        
        if ($row > 0 ) {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
        }
    }
?>
<div class="container">  
    <h1>مرحبا </h1>
    <h3> الصيانة الشهرية حاليا هي  <?php echo $fee; ?> جنية <h3>
    <div class="row">
        <div class="col-md-4 offset-4">
            
            <form class="login form-group" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <h2 class="text-center" >تسجيل الدخول</h2>
                <input class="form-control" type="text" name="username" placeholder="اسم المستخدم" autocomplete="off"/><br/>
                <input class="form-control" type="password" name="password" placeholder="كلمة السر" autocomplete="new-password"/><br />
                <input class="btn btn-lg btn-dark btn-block" type="submit" value="تسجيل الدخول" />
            </form>
        </div>
    </div>
</div>
<?php 
    include $tbl . "footer.php";
?>