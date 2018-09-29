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
                $stmtupdate = $db->prepare('UPDATE maint SET balance = :zblnc, billed_to_date = :dt WHERE contract_id = :con');
                $stmtupdate->execute(array(
                    ':zblnc' => $new_balance,
                    ':con'   => $con_id,
                    ':dt'    => date('Y-m-d')
                ));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'الرصيد </div>';
                echo '<a href="contracts.php?page=1" class=" btn btn-group-vertical"> رجوع الي الصفحة الرئيسية</a>';
                echo '<a href="dashboard.php" class=" btn btn-secondary">  بحث آخر</a>';
            }
        } elseif ($action == 'payElec') {
            echo 'Welcome To Pay Elec';
            echo 'Welcome To Pay Maint';
            $con_id = $_GET['conid'];
            $url = "?action=updateElec&cid=" . $con_id;
            

            ?>
            <form action="<?php echo $url ?>" method="POST">
                <div class="input-group">
                            
                            <div class="input-group-prepend">
                                <span class="input-group-text">أدخل المبلغ المدفوع</span>
                            </div>
                            <input type="hidden" name="cid" value="<?php $con_id ?>" />
                            <input type="text" aria-label="المبلغ" name="elec_payment" class="form-control">
                            <div class="input-group-prepend">
                                <input type="submit" class="btn btn-outline-secondary" value="دفع"/>
                            </div>
                        
                </div>
            </form>
            <?php
        } elseif ($action == 'updateElec') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo 'Welcome To Update Elec';
                // catch payment made
                $con_id = $_GET['cid'];
                
                $paid_elec = $_POST['elec_payment'];
                
                // fetch balance
                $stmt = $db->prepare('SELECT elec_balance FROM elec WHERE contract_id =?');
                $stmt->execute(array($con_id));
                $elec_blnc = $stmt->fetch();
                
                // calculate new balance
                $new_elec_balance = $elec_blnc['elec_balance'] - $paid_elec;
                
                // update the balance
                $stmtupdate = $db->prepare('UPDATE elec SET elec_balance = :zblnc, billed_to_date = :dt WHERE contract_id = :con');
                $stmtupdate->execute(array(
                    ':zblnc' => $new_elec_balance,
                    ':con'   => $con_id,
                    ':dt'    => date('Y-m-d')
                ));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'الرصيد </div>';
                echo '<a href="contracts.php?page=1" class=" btn btn-group-vertical"> رجوع الي الصفحة الرئيسية</a>';
                echo '<a href="dashboard.php" class=" btn btn-secondary">  بحث آخر</a>';
            }
            
        } elseif($action == 'addCurrentElec') {
            
            $con_id = $_GET['conid'];
            $url = "?action=updateCurrentElec&cid=" . $con_id;
            

            ?>
            <form action="<?php echo $url ?>" method="POST">
                <div class="input-group">
                            
                            <div class="input-group-prepend">
                                <span class="input-group-text">أدخل القراءة الجديدة</span>
                            </div>
                            
                            <input type="text" aria-label="القراءة الجديدة" name="current_reading" class="form-control">
                            <div class="input-group-prepend">
                                <input type="submit" class="btn btn-outline-secondary" value="حفظ"/>
                            </div>
                        
                </div>
            </form>
            <?php

        } elseif($action == 'updateCurrentElec') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // catch payment made
                $con_id = $_GET['cid'];
                
                $current_reading = $_POST['current_reading'];
                
                // fetch balance
                $stmt = $db->prepare('SELECT elec_balance,prev_reading,rate FROM elec WHERE contract_id =?');
                $stmt->execute(array($con_id));
                $elecs = $stmt->fetch();
                
                // calculate new balance
                $diff_reading = $current_reading - $elecs['prev_reading'];
                $elec_value = $diff_reading * $elecs['rate'];
                $new_elec_balance = $elecs['elec_balance'] + $elec_value;
                echo $diff_reading . '<br>';
                echo $elec_value . '<br>';
                echo $new_elec_balance . '<br>';
                // update the balance
                $stmtupdate = $db->prepare('UPDATE elec SET 
                elec_balance = :zblnc, 
                current_reading = :cr, 
                prev_reading = :pr, 
                current_reading_date = :dt 
                WHERE contract_id = :con');
                $stmtupdate->execute(array(
                    ':zblnc' => $new_elec_balance,
                    ':cr'    => $current_reading,
                    ':pr'    => $current_reading,
                    ':dt'    => date('Y-m-d'),
                    ':con'   => $con_id
                ));
                echo '<h1 class="page-title text-center"> تم الحفظ </h1>';
                echo '<div class="alert alert-success" role="alret"> تم حفظ بيانات' . $stmt->rowCount() . 'الرصيد </div>';
                echo '<a href="contracts.php?page=1" class=" btn btn-group-vertical"> رجوع الي الصفحة الرئيسية</a>';
                echo '<a href="dashboard.php" class=" btn btn-secondary">  بحث آخر</a>';
            }
            
        }
} else {
    header('Location: dashboard.php');
    exit();
}