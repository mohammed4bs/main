<?php
    session_start();
   
    if(isset($_SESSION['username'])) {
        $pageTitle = "لوحة التحكم";
        include 'init.php';
        include $tbl . 'header.php';
        ?>

        <div class="container">
            <div class="row">
                <div class="col-6 customer">
                    <h2>بيانات العميل</h2>
                    <hr class="hr-b text-right" />
                    <div class="row">
                        <div class="col-3">
                            <label> اسم العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> أحمد حلمي عبد الراضي </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> تليفون العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> 0102....... </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> ايميل العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> customer@gmail.com </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> عنوان العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> مصر الجديدة - القاهرة </b> <br />
                        </div>
                    </div>


                </div>
                <div class="col-6 customer">
                    <h2>بيانات القطع</h2>
                    <hr class="hr-b text-right" />
                    <div class="row">
                        <div class="col-3">
                            <label> اسم العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> أحمد حلمي عبد الراضي </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> تليفون العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> 0102....... </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> ايميل العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> customer@gmail.com </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> عنوان العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> مصر الجديدة - القاهرة </b> <br />
                        </div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-6 customer">
                    <h2>بيانات العميل</h2>
                    <hr class="hr-b text-right" />
                    <div class="row">
                        <div class="col-3">
                            <label> اسم العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> أحمد حلمي عبد الراضي </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> تليفون العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> 0102....... </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> ايميل العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> customer@gmail.com </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> عنوان العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> مصر الجديدة - القاهرة </b> <br />
                        </div>
                    </div>


                </div>
                <div class="col-6 customer">
                    <h2>بيانات العميل</h2>
                    <hr class="hr-b text-right" />
                    <div class="row">
                        <div class="col-3">
                            <label> اسم العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> أحمد حلمي عبد الراضي </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> تليفون العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> 0102....... </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> ايميل العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> customer@gmail.com </b> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label> عنوان العميل :</label>
                        </div>
                        <div class="col-9">
                            <b> مصر الجديدة - القاهرة </b> <br />
                        </div>
                    </div>


                </div>
            </div>

        </div>







        <?php
    } else {
        header('Location: index.php');
    }