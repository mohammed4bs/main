<?php 

session_start();

if (isset($_SESSION['username'])) {
    $pageTitle = "العملاء";
    include 'init.php';
    
    $stmt = $db->prepare("SELECT * FROM clients");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    echo '<div class="container">';
    echo '<h1 class=" title align-item-center"> عملاء الريف الاوروبي </h1>';
    ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
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

            </tr>

        </thead>
        <tbody>
            <?php
                foreach($rows as $row) {
                    echo '<tr>';
                        echo '<td>' . $row['client_name'] . '</td><td>' . $row['phone'] . '</td><td>' . $row['email'] . '</td><td>' . $row['address'] . '</td>';

                    echo '</tr>';
                }
            ?>

        </tbody>

    </table>
    <a href="#" class="btn btn-outline-dark btn-lg"> إضافة عميل جديد</a> 
    <?php
    echo '</div>';

}else {
    header('Location: index.php');
    exit();
}