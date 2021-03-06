<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">صيانة الريف الأوروبي</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">الرئيسية <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
               

                <a class="nav-link dropdown-toggle" href="clients.php?page=1" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                العملاء 
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="clients.php?action=Add">إضافة عميل جديد</a>
                    <a class="dropdown-item" href="clients.php?page=1"> إظهار جميع العملاء</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">إعداد 2</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="company.php?page=1">الشركات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reefs.php?page=1">الأرياف</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="units.php?page=1">الاراضي</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contracts.php?page=1">العقود</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                إعدادات 
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="settings/fee.php">إعداد قيمة الصيانة الشهرية</a>
                    <a class="dropdown-item" href="#"> اعداد</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">إعداد 2</a>
                </div>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> -->
        </ul>
        
        <form class="form-inline my-2 my-lg-0" action="dashboard.php" method="GET">
            <input class="form-control mr-sm-2 client_search" type="text" name="client" placeholder="بحث عن عميل" aria-label="Search">
            <button class="btn btn-info mr-auto my-2 my-sm-0" style="margin-right:-10px; border-left-top-radius:0;" type="submit">بحث</button>
        </form>
        
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" style="margin-right:50px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                خيارات المستخدم
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">تغيير الباسوورد</a>
                <a class="dropdown-item" href="logout.php">تسجيل الخروج</a>
                
            </div>
        </div>
    </div>
    </nav>