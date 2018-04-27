<div class="row row-offcanvas row-offcanvas-left active">
    <!-- sidebar -->
    <div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">
        <ul class="nav" id="menu">
            <li><a href="{{ route('book.index') }}"><i class="fa fa-list-alt"></i> <span class="collapse visible-xs">Book</span></a></li>
            <li><a href="{{ route('categories.index') }}"><i class="fa fa-list"></i> <span class="collapse visible-xs">Categories</span></a></li>
            <li><a href="{{ route('employees.index') }}"><i class="fa fa-list"></i> <span class="collapse visible-xs">Employees</span></a></li>
            <li><a href="{{ route('positions.index') }}"><i class="fa fa-list"></i> <span class="collapse visible-xs">Positions</span></a></li>
            <li><a href="#"><i class="fa fa-paperclip"></i> <span class="collapse visible-xs">Saved</span></a></li>
            <li><a href="#"><i class="fa fa-refresh"></i> <span class="collapse visible-xs">Refresh</span></a></li>
            <li>
                <a href="#" data-target="#item1" data-toggle="collapse"><i class="fa fa-list"></i> <span class="collapse visible-xs">Menu <span class="caret"></span></span></a>
                <ul class="nav nav-stacked collapse left-submenu" id="item1">
                    <li><a href="google.com">View One</a></li>
                    <li><a href="gmail.com">View Two</a></li>
                </ul>
            </li>
            <li>
                <a href="#" data-target="#item2" data-toggle="collapse"><i class="fa fa-list"></i> <span class="collapse visible-xs">Menu <span class="caret"></span></span></a>
                <ul class="nav nav-stacked collapse" id="item2">
                    <li><a href="#">View One</a></li>
                    <li><a href="#">View Two</a></li>
                    <li><a href="#">View Three</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="glyphicon glyphicon-list-alt"></i> <span class="collapse visible-xs">Link</span></a></li>
        </ul>
    </div>
    <div class="column col-sm-9 col-xs-11" id="main">
        <p><a href="javascript:void(0)" data-toggle="offcanvas"><i class="fa fa-navicon fa-2x"></i></a></p>
        @yield('container')
    </div>
    <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</div>