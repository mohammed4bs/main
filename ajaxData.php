<?php 

session_start();
// Check if there's a session with the user name============================
if (isset($_SESSION['username'])) {
    include 'init.php';

    if(!empty($_POST["company_id"])){
        //Fetch all state data
        $stmt = $db->prepare("SELECT * FROM reefs WHERE company_id = ? ORDER BY reef_id ASC");
        $stmt->execute(array($_POST['company_id']));
        $reefs = $stmt->fetchAll();
        //Count total number of rows
        $rowCount = $stmt->rowCount();
        echo $rowCount;
        //State option list
        if($rowCount > 0){
            echo '<option value="">اختر الريف</option>';
            foreach ($reefs as $reef) {
                echo '<option value="'.$reef['reef_id'].'">'.$reef['reef_name'].'</option>';
            }    
        }else{
            echo '<option value="">لا يوجد ريف</option>';
        }
    }elseif(!empty($_POST["reef_id"])){
        //Fetch all units data
        $stmt = $db->prepare("SELECT * FROM units WHERE reef_id = ? ORDER BY unit_id ASC");
        $stmt->execute(array($_POST['reef_id']));
        $units = $stmt->fetchAll();
        //Count total number of rows
        $rowCount = $stmt->rowCount();
        
        //City option list
        if($rowCount > 0){
            echo '<option value="">اختر القطعة</option>';
            
            foreach ($units as $unit) {
                $stmt = $db->prepare('SELECT * FROM contract_units WHERE unit_id = ?');
                $stmt->execute(array($unit['unit_id']));
                $count = $stmt->rowCount();
                $un = $stmt->fetch();

                if($count > 0) { 
                    echo '<option disabled  value="'.$unit['unit_id'].'">'.$unit['unit_name'].'</option>';
                      
                } else {
                    echo '<option  value="'.$unit['unit_id'].'">'.$unit['unit_name'].'</option>';
                }
            
            }
        }else{
            echo '<option value="">لا توجد قطع </option>';
        }
    }elseif(!empty($_POST["unit_id"])){
        
        
                $unit_id = $_POST['unit_id'];
             
                $stmt = $db->prepare('SELECT space_f, space_q, space_s FROM units WHERE unit_id = ?');
                $stmt->execute(array($unit_id));
                $sp = $stmt->fetch();
                $count = $stmt->rowCount();
                if ($count > 0 ) {
                    $fdan = $sp['space_f'];
                    $qirat = $sp['space_q'];
                    $sahm = $sp['space_s'];
                    // Convert sahm to qirat
                    $value = $sahm / 24;
                    // add sahm to qirat
                    $value = $value + $qirat;
                    // convert the sum to fdan
                    $value = $value / 24;
                    // add sum to fdan
                    $value = $value + $fdan; 

                    echo '<option  value="'.  $value .'">' .$fdan . '  فدان و ' . $qirat . ' قيراط و' .
                    $sahm . ' سهم ' . '</option>';
                }
                

                
            
    }
} else {
    echo 'Worng';
}
?>