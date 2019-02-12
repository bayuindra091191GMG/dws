
<div id="app">
    <aside class="main-sidebar fixed offcanvas shadow">
        <section class="sidebar">
            <div class="w-80px mt-3 mb-3 ml-3">
                <img src="{{ asset('img/basic/logo.png') }}" alt="">
            </div>
            <div class="relative">
                <a data-toggle="collapse" href="#userSettingsCollapse" role="button" aria-expanded="false"
                   aria-controls="userSettingsCollapse" class="btn-fab btn-fab-sm fab-right fab-top btn-primary shadow1 ">
                    <i class="icon icon-cogs"></i>
                </a>
                <div class="user-panel p-3 light mb-2">
                    <div>
                        <div class="float-left image">
                            <img class="user_avatar" src="{{ asset('img/dummy/u2.png') }}" alt="User Image">
                        </div>
                        <div class="float-left info">
                            <h6 class="font-weight-light mt-2 mb-1">Test User</h6>
                            <a href="#"><i class="icon-circle text-primary blink"></i> Online</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="collapse multi-collapse" id="userSettingsCollapse">
                        <div class="list-group mt-3 shadow">
                            <a href="#" class="list-group-item list-group-item-action ">
                                <i class="mr-2 icon-umbrella text-blue"></i>Profile
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="mr-2 icon-cogs text-yellow"></i>Settings
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="mr-2 icon-security text-purple"></i>Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="treeview">
                    <a href="{{ route('admin.dashboard') }}">
                    <i class="icon icon-sailing-boat-water purple-text s-18"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="icon icon-settings light-green-text s-18 "></i> <span>Data</span>
                        <i class="icon icon-angle-left s-18 pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.categories.index') }}">
                                <i class="icon icon-sticky-note text-green"></i>Category
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.currencies.index') }}">
                                <i class="icon icon-money text-green"></i>Currencies
                            </a>
                        </li>
                    </ul>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.contact-messages.index') }}">
                                <i class="icon icon-message text-green"></i>Contact Us
                            </a>
                        </li>
                    </ul>

                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.subscribes.index') }}">
                                <i class="icon icon-user-circle text-green"></i>Subscribes
                            </a>
                        </li>
                    </ul>

                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.vouchers-categories.index') }}">
                                <i class="icon icon-vcard text-green"></i>Voucher Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.vouchers.index') }}">
                                <i class="icon icon-vcard text-green"></i>Vouchers
                            </a>
                        </li>
                    </ul>

                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.faqs.index') }}">
                                <i class="icon icon-document text-green"></i>FAQ
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="icon icon-settings light-green-text s-18 "></i> <span>Product</span>
                        <i class="icon icon-angle-left s-18 pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.product.index') }}">
                            <i class="icon icon-user-circle text-green"></i>List Product
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.product.create') }}">
                            <i class="icon icon-user-circle text-green"></i>Add New Product
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="icon icon-settings light-green-text s-18 "></i> <span>Setup</span>
                        <i class="icon icon-angle-left s-18 pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.store-address.index') }}">
                                <i class="icon icon-address-book text-green"></i>Store Addresses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.admin-users.index') }}">
                                <i class="icon icon-user-circle text-green"></i>Admin Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}">
                                <i class="icon icon-users text-green"></i>Users
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>
</div>