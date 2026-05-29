<aside class="main-sidebar">
    <section class="sidebar">
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

      <ul class="sidebar-menu" data-widget="tree">

        {{-- MAIN NAVIGATION: Visible strictly to Admins only --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="header">MAIN NAVIGATION</li>

            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>

            {{-- Analytics based on conversations and risk levels --}}
            <li class="{{ Request::is('analytics*') ? 'active' : '' }}">
              <a href="{{ route('analytics') }}">
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
                <li><a href="{{ route('counsillors.index') }}"><i class="fa fa-circle-o"></i> Counselor Directory</a></li>
                <li><a href="{{ route('counsillor_log') }}"><i class="fa fa-circle-o"></i> Assignment Logs</a></li>
              </ul>
            </li>

            {{-- User Management - For Admin and Anyms-Users --}}
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>User Management</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="admin/users"><i class="fa fa-circle-o"></i> Administrators</a></li>
                <li><a href="{{ route('anonymous.index') }}"><i class="fa fa-circle-o"></i> Anonymous Users</a></li>
              </ul>
            </li>
        @endif

        {{-- CARE & SUPPORT ENGINE: Visible to everyone logged in (Admin and Counselor alike) --}}
        <li class="header">CARE & SUPPORT ENGINE</li>

        {{-- Stress Modules --}}
        <li class="{{ Request::is('stress-modules*') ? 'active' : '' }}">
            <a href="{{ route('stress-modules.index') }}">
                <i class="fa fa-heart"></i> <span>Stress Modules</span>
            </a>
        </li>

        {{-- Peer Stories --}}
        <li class="{{ Request::is('peer-stories*') ? 'active' : '' }}">
            <a href="{{ route('peer-stories.index') }}">
                <i class="fa fa-book"></i> <span>Peer Stories</span>
            </a>
        </li>

        {{-- Counselor Module Portal --}}
        <li class="{{ Request::is('counselor-portal*') ? 'active' : '' }}">
            <a href="{{ route('counselor-portal.index') }}">
                <i class="fa fa-mortar-board"></i> <span>Counselor Module</span>
            </a>
        </li>

        {{-- SYSTEM CONTROL: Visible to everyone logged in --}}
        <li class="header">SYSTEM CONTROL</li>

        {{-- Settings --}}
        <li>
          <a href="settings">
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
</aside>
