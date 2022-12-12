<nav class="navbar horizontal-layout-2 col-lg-12 col-12 p-0 d-flex flex-row align-items-start" style="
">
      <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('home')}}">
            @if(session('logo') == "")
            Sistema
            @else
            Sistema
            @endif
          </a>
          <a class="navbar-brand brand-logo-mini img-fluid" href="{{ route('home')}}">
            Sistema
          </a>

        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center pr-0">
          @if(in_array('usuarios',session('permisosMenu')))
          <ul class="navbar-nav header-links">
            {{-- <li class="nav-item">
              <a href="#" class="nav-link">Tareas <span class="badge badge-success ml-1">New</span></a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="mdi mdi-bookmark-plus-outline"></i>Metas</a>
            </li> --}}
            <li class="nav-item active">
              <a href="{{ route('reportes.modulos')}}" class="nav-link"><i class="mdi mdi-elevation-rise"></i>Reportes</a>
            </li>
            
          </ul>
          @endif
          <ul class="navbar-nav ml-auto dropdown-menus">
            {{-- <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-message-text-outline"></i>
                <span class="count bg-warning">2</span>
              </a>
              <div class="dropdown-menu dropdown-menu-left navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <div class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have 7 unread mails
                  </p>
                  <span class="badge badge-inverse-info badge-pill float-right">View all</span>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="../../images/faces/face1.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal text-dark mb-1">David Grey
                      <span class="float-right font-weight-light small-text text-gray">1 Minutes ago</span>
                    </h6>
                    <p class="font-weight-light small-text mb-0">
                      The meeting is cancelled
                    </p>
                  </div>
                </a>
              </div>
            </li> 
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count bg-success">4</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <a class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have 4 new notifications
                  </p>
                  <span class="badge badge-pill badge-inverse-info float-right">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-success">
                      <i class="mdi mdi-alert-circle-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                    <p class="font-weight-light small-text mb-0">
                      Just now
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-warning">
                      <i class="mdi mdi-comment-text-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                    <p class="font-weight-light small-text mb-0">
                      Private message
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-info">
                      <i class="mdi mdi-email-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                    <p class="font-weight-light small-text mb-0">
                      2 days ago
                    </p>
                  </div>
                </a>
              </div>
            </li> --}}
            <!-- Authentication Links -->
          @guest
            <li class="nav-item dropdown d-none d-xl-inline-block">
              <a class="nav-link" href="{{ route('login') }}"><span class="mr-3">{{ __('Login') }}</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
          @else

          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="mr-3">Hola, {{ Auth::user()->name }} !</span><img class="img-xs rounded-circle" src="{{ asset('images/logo_ticketing_1.png')}}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <a class="dropdown-item p-0">
                <div class="d-flex border-bottom">
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right"><i class="mdi mdi-account-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-alarm-check mr-0 text-gray"></i></div>
                </div>
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </li>
          <li class="d-block d-md-none">
            <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 <i class="mdi mdi-logout text-white"></i>
            </a>
          </li>

          @endguest

          <!-- Authentication Links -->
          </ul>
          <button type="button" class="navbar-toggler d-block d-md-none"><i class="mdi mdi-menu"></i></button>
        </div>
      </div>
      <div class="container">
        <div class="nav-bottom border-bottom border-secondary">
          <ul class="navbar-nav">
            <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
              <a class="nav-link count-indicator" href="{{ route('home') }}">Inicio</a>
            </li>
            @if(in_array('bd',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('bd.index') }}">
              <a class="nav-link count-indicator" title="Base de Datos" id="project-dropdown" href="{{ route('bd.index')}}">B.D</a>
            </li>
            @endif
            @if(in_array('estudiantes',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('caii.index') }}">
              <a class="nav-link count-indicator" id="finance-dropdown" href="{{ route('caii.index') }}" >CAII</a>
            </li>
            @endif
            @if(in_array('eventos',session('permisosMenu')))
            <li class="nav-item dropdown {{ setActive('eventos.index') }}">
              <a class="nav-link count-indicator" id="project-dropdown" href="{{route('eventos.index')}}">Eventos</a>
            </li>
            @endif
            
            
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="usuarios" href="#" data-toggle="dropdown">
               <i class="mdi mdi-settings"></i>
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="usuarios">
                <ul>
                  @if(in_array('usuarios',session('permisosMenu')))
                  <li class="dropdown-item"><a href="{{ route('ajustes.edit',['ajuste'=>'1']) }}" class="dropdown-link">Ajustes Generales</a></li>
                  <li class="dropdown-item"><a href="{{ route('usuarios.index') }}" class="dropdown-link">Usuarios</a></li>
                  @endif
                  @if(in_array('roles',session('permisosMenu')))
                  <li class="dropdown-item"><a href="{{ route('roles.index') }}" class="dropdown-link">Roles</a></li>
                  @endif
                </ul>
              </div>
            </li>
            
          </ul>
          {{-- <ul class="navbar-nav ml-auto d-none d-lg-block">
            <li class="nav-item mr-4">
              <form action="#">
                <div class="form-group mb-0">
                  <div class="input-group search-field">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
                </div>
              </form>
            </li>
          </ul> --}}
        </div>
      </div>
    </nav>