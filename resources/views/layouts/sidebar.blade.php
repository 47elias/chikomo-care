<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          {{-- Pulling 'name' from the users table --}}
          <p>{{ auth()->user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
          <a href={{ route('dashboard') }}>
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        {{-- Analytics based on conversations and risk levels --}}
        <li class="{{ Request::is('analytics*') ? 'active' : '' }}">
          <a href={{ route('analytics') }}>
            <i class="fa fa-pie-chart"></i>
            <span>Analytics Insights</span>
          </a>
        </li>

        {{-- Counselor Management --}}
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user-md"></i> <span>Counselors</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href={{ route('counsillors.index') }}><i class="fa fa-circle-o"></i> Counselor Directory</a></li>
            <li><a href={{ route('counsillor_log') }}><i class="fa fa-circle-o"></i> Assignment Logs</a></li>
          </ul>
        </li>

        {{-- User Management - For Admin and Anyms-Users[cite: 1] --}}
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>User Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="admin/users"><i class="fa fa-circle-o"></i> Administrators</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Anonymous Users</a></li>
          </ul>
        </li>

        <li class="header">SYSTEM CONTROL</li>

        {{-- Settings --}}
        <li>
          <a href="#">
            <i class="fa fa-gears"></i> <span>Settings</span>
          </a>
        </li>

        {{-- Chikomo Care Public View --}}
        <li>
          <a href="/">
            <i class="fa fa-laptop"></i>
            <span>View Site</span>
          </a>
        </li>

        {{-- Logout --}}
        <li>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i>
            <span>Logout</span>
          </a>
          <form id="logout-form" action="#" method="POST" style="display: none;">
              @csrf
          </form>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>
