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
                $un = $stmt->fetchAll();

                if($count > 0) { 
                    $sp;
                    foreach($un as $u) {
                        $sp += $u['unit_space'];
                    }
                    if ($sp >= 1) {
                        echo '<option disabled  value="'.$unit['unit_id'].'">'.$unit['unit_name'].'</option>';
                    } else {
                        echo '<option  value="'.$unit['unit_id'].'">'.$unit['unit_name'].'</option>';    
                    }
                    
                } else {
                    echo '<option  value="'.$unit['unit_id'].'">'.$unit['unit_name'].'</option>';
                }
            
            }
        }else{
            echo '<option value="">لا توجد قطع </option>';
        }
    }elseif(!empty($_POST["unit_id"])){
        
        
                $unit_id = $_POST['unit_id'];
             
            
                $stmt = $db->prepare('SELECT * FROM contract_units WHERE unit_id = ?');
                $stmt->execute(array($unit_id));
                $count = $stmt->rowCount();
                $un = $stmt->fetchAll();
                

                if($count > 0) { 
                    
                    $sp;
                    foreach($un as $u) {
                        $sp += $u['unit_space'];
                    }
                    echo $sp;
                    if ($sp == 0) {
                        echo '<option> اختر المساحة </option>';
                        '<option> echo $sp; </option>';
                        echo '<option value="0.5">نصف فدان</option><option value="1"> فدان</option>';
                    } elseif ($sp == 0.5) {
                        echo '<option> اختر المساحة </option>';
                        echo '<option value="0.5">نصف فدان</option>';    
                    } else {
                        echo '<option>  المساحة بالكامل محجوزة </option>';
                    }
                    
                } else {
                    echo '<option> اختر المساحة </option>';
                    echo '<option value="0.5">نصف فدان</option><option value="1"> فدان</option>';
                }
            }
    
} else {
    echo 'Worng';
}
?>