<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle user-avatar" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ auth()->user()->name }}</p>
          <a href="#" class="status-link"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control sidebar-search-input" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat sidebar-search-btn">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>

      <ul class="sidebar-menu" data-widget="tree">

        {{-- MAIN NAVIGATION: Visible strictly to Admins only --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="header">Main Navigation</li>

            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>

            <li class="{{ Request::is('analytics*') ? 'active' : '' }}">
              <a href="{{ route('analytics') }}">
                <i class="fa fa-pie-chart"></i>
                <span>Analytics Insights</span>
              </a>
            </li>

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

        {{-- CARE & SUPPORT ENGINE --}}
        <li class="header">Care &amp; Support Engine</li>

        <li class="{{ Request::is('stress-modules*') ? 'active' : '' }}">
            <a href="{{ route('stress-modules.index') }}">
                <i class="fa fa-heart"></i> <span>Stress Modules</span>
            </a>
        </li>

        <li class="{{ Request::is('peer-stories*') ? 'active' : '' }}">
            <a href="{{ route('peer-stories.index') }}">
                <i class="fa fa-book"></i> <span>Peer Stories</span>
            </a>
        </li>

        <li class="{{ Request::is('counselor-portal*') ? 'active' : '' }}">
            <a href="{{ route('counselor-portal.index') }}">
                <i class="fa fa-mortar-board"></i> <span>Counselor Module</span>
            </a>
        </li>

        {{-- SYSTEM CONTROL --}}
        <li class="header">System Control</li>

        <li>
          <a href="settings">
            <i class="fa fa-gears"></i> <span>Settings</span>
          </a>
        </li>

        <li>
          <a href="/">
            <i class="fa fa-laptop"></i>
            <span>View Site</span>
          </a>
        </li>

        <li>
          <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

<style>
    .main-sidebar {
        box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
    }

    /* User panel */
    .user-panel {
        padding: 18px 15px !important;
    }

    .user-panel .user-avatar {
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
        width: 45px;
        height: 45px;
    }

    .user-panel:hover .user-avatar {
        transform: scale(1.05);
    }

    .user-panel .info p {
        font-weight: 600;
        letter-spacing: 0.2px;
        margin-bottom: 3px;
    }

    .status-link {
        font-size: 12px;
        opacity: 0.85;
        transition: opacity 0.15s ease;
    }

    .status-link:hover {
        opacity: 1;
        text-decoration: none;
    }

    /* Search bar */
    .sidebar-form {
        padding: 0 12px 12px;
    }

    .sidebar-search-input {
        border-radius: 6px 0 0 6px !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .sidebar-search-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .sidebar-search-input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.25);
        box-shadow: none;
    }

    .sidebar-search-btn {
        border-radius: 0 6px 6px 0 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: none;
    }

    /* Section headers */
    .sidebar-menu > li.header {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        opacity: 0.55;
        padding: 16px 15px 8px;
        background: transparent !important;
    }

    /* Menu items */
    .sidebar-menu > li > a {
        border-radius: 6px;
        margin: 2px 8px;
        padding: 10px 12px;
        width: calc(100% - 16px);
        transition: background 0.15s ease, padding-left 0.15s ease;
    }

    .sidebar-menu > li > a:hover {
        background: rgba(255, 255, 255, 0.07);
        padding-left: 16px;
    }

    .sidebar-menu > li.active > a {
        background: rgba(255, 255, 255, 0.12) !important;
        border-left: 3px solid #3c8dbc;
        font-weight: 600;
    }

    .sidebar-menu > li > a > i.fa {
        width: 22px;
        text-align: center;
    }

    /* Treeview submenu */
    .sidebar-menu .treeview-menu {
        margin: 2px 8px 6px 8px;
        border-left: 1px solid rgba(255, 255, 255, 0.08);
        padding-left: 4px;
    }

    .sidebar-menu .treeview-menu > li > a {
        border-radius: 6px;
        padding: 8px 12px;
        margin: 1px 4px;
        transition: background 0.15s ease;
    }

    .sidebar-menu .treeview-menu > li > a:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    .sidebar-menu .treeview > a .pull-right-container {
        transition: transform 0.2s ease;
    }

    .sidebar-menu .treeview.menu-open > a .pull-right-container {
        transform: rotate(-90deg);
    }

    /* Logout emphasis */
    .logout-link {
        color: #e57373 !important;
    }

    .logout-link:hover {
        background: rgba(229, 115, 115, 0.1) !important;
    }
</style>
