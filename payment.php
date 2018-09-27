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
        } elseif ($action == 'payMaint') {
            echo 'Welcome To Pay Maint';
            $con_id = $_GET['conid'];
            $url = "?action=updateMaint&cid=" . $con_id;
            

            ?>
            <form action="<?php echo $url ?>" method="POST">
                <div class="input-group">
                            
                            <div class="input-group-prepend">
                                <span class="input-group-text">أدخل المبلغ المدفوع</span>
                            </div>
                            <input type="hidden" name="cid" value="<?php $con_id ?>" />
                            <input type="text" aria-label="المبلغ" name="payment" class="form-control">
                            <div class="input-group-prepend">
                                <input type="submit" class="btn btn-outline-secondary" value="دفع"/>
                            </div>
                        
                </div>
            </form>
            <?php

        } elseif ($action == 'updateMaint') {
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo 'Welcome To Update Maint';
                // catch payment made
                $con_id = $_GET['cid'];
                
                $paid_maint = $_POST['payment'];
                
                // fetch balance
                $stmt = $db->prepare('SELECT balance FROM maint WHERE contract_id =?');
                $stmt->execute(array($con_id));
                $blnc = $stmt->fetch();
                print_r($blnc);
                // calculate new balance
                $new_balance = $blnc['balance'] - $paid_maint;
                
                // update the balance
                $stmtupdate = $db->prepare('UPDATE maint SET balance = :zblnc');
                $stmtupdate->execute(array(
                    ':zblnc' => $new_balance
                ));
                echo 'Done';
            }
        } elseif ($action == 'payElec') {
            echo 'Welcome To Pay Elec';
        } elseif ($action == 'updateElec') {
            echo 'Welcome To update Elec';
        }
} else {
    header('Location: dashboard.php');
    exit();
}