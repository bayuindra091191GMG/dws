<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-account-settings-variant"></i>
                        <span class="hide-menu">Setup </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.admin-users.index') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Admin Users </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                                <i class="mdi mdi-note-plus"></i>
                                <span class="hide-menu"> Users </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-receipt"></i>
                        <span class="hide-menu">Forms </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="form-basic.html" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Form Basic </span>
                            </a>
                        </li>
                        <li class="sidebar-item"><a href="form-wizard.html" class="sidebar-link"><i class="mdi mdi-note-plus"></i><span class="hide-menu"> Form Wizard </span></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->