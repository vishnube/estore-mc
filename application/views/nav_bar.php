<!-- Navbar -->
<!-- class="main-header navbar navbar-expand navbar-dark navbar-lightblue" -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item dropdown user user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="dependencies/AdminLTE-3.0.2/dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt=" User Image"> -->
                <i class="fas fa-user" style="color: #ffc107; font-size:18px;"></i>
                <!-- <span class="hidden-xs">Alexander Pierce</span> -->
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="margin-top:7px;">
                <!-- User image -->
                <li class="user-header bg-lightblue">
                    <!-- <img src="dependencies/AdminLTE-3.0.2/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                    <span class="fa-stack fa-3x">
                        <i class="fas fa-circle fa-stack-2x text-white"></i>
                        <i class="fab fa-jenkins  fa-flip-horizontal fa-stack-1x text-info"></i>
                    </span>
                    <p>





                        <?= $this->mbr_name . ' - ' . implode(', ', array_values($this->usr_grp_opt)) ?>
                        <small>Member since <?= date('d F Y', strtotime($this->mbr_date)) ?></small>
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body" style="border-bottom: 2px solid #3c8dbc">
                    <div class="float-left" id="edit_user_profile">
                        <!-- Profile edit view @ users/edit_profile.php -->
                        <a class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="float-right">
                        <?php
                        // To work 'logout/ok' you should set config/routes.php as 
                        // $route['logout/ok'] = 'home/logout/ok'; 
                        echo anchor('logout/ok', 'Sign out', array('class' => 'btn btn-default btn-flat')) ?>
                    </div>

                </li>

            </ul>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <!-- <img src="dependencies/AdminLTE-3.0.2/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle" /> -->
                        <span class="fa-stack fa-3x">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <i class="fab fa-jenkins  fa-flip-horizontal fa-stack-1x text-info"></i>
                        </span>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <!-- <img src="dependencies/AdminLTE-3.0.2/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3" /> -->
                        <span class="fa-stack fa-3x">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <i class="fab fa-jenkins  fa-flip-horizontal fa-stack-1x text-info"></i>
                        </span>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <!-- <img src="dependencies/AdminLTE-3.0.2/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3" /> -->
                        <span class="fa-stack fa-3x">
                            <i class="fas fa-circle fa-stack-2x text-white"></i>
                            <i class="fab fa-jenkins  fa-flip-horizontal fa-stack-1x text-info"></i>
                        </span>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>







        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" id="settings-handler">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->