<!-- Main Sidebar Container -->
<aside class="sr-m-bar main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a class="brand-link">
        <img src="<?= $logo_xs ?>" alt="My App" class="brand-image img-circle elevation-3" />
        <span class="brand-text font-weight-light" style="color: #fff;">My App</span>

        <!-- #app-logo-xs is uses to add logo in print, pdf ect -->
        <img id="app-logo-xs" src="data:image/png;base64, <?= $logo_xs_base64 ?>" style="display: none;" />
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="dependencies/AdminLTE-3.0.2/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" />
                </div>
                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2"><?= $nav_menu ?></nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>