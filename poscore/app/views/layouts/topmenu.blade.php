<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">

      <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

        <a href="{{URL::route('home')}}" class="brand" style="padding:0; float:right; margin-left:20px;">
          {{HTML::image('vendor/bucketcodes/img/logo1_small_black.jpg', 'Glamour56_logo', array('width'=>'43'))}}
        </a>
       @if(Auth::check())
        <div class="pull-right">
            <ul class="nav ace-nav pull-right">
 @include('alerts')

              <li class="light-blue">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                  {{HTML::image('uploads/img/profile.png','', array('class'=>'nav-user-photo', 'width'=>'50px'))}}
                  <span class="user-info" style="color:white">
                    <small>Welcome,</small>
                    {{ucwords(Auth::user()->username)}} 
                  </span>

                  <i class="icon-caret-down"></i>
                </a>

                <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                  <li>
                    <a href={{URL::route('viewchangepassword')}}>
                      <i class="icon-lock"></i>
                      Change password
                    </a>
                  </li>

                  <?php unset($menuSet); $menuSet = MenuClass::getMenuArray('stock manager'); ?>
                  @if( User::permitted( $menuSet['role'] ) )
                      <li>
                        <a href="{{URL::route($menuSet['urlname'])}}">
                          <i class="icon-list"></i>
                          Manage Stock
                        </a>
                      </li>
                  @endif

                  <?php unset($menuSet); $menuSet = MenuClass::getMenuArray('dashboard'); ?>
                  @if( User::permitted( $menuSet['role'] ) )
                      <li>
                        <a href="{{URL::route($menuSet['urlname'])}}">
                         <i class="icon-signin"></i> Go to Admin
                        </a>
                      </li>
                  @endif

                  <li class="divider"></li>
                  <li>
                    <a href="{{URL::route('logout')}}">
                      <i class="icon-off"></i>
                      Logout
                    </a>
                  </li>
                </ul>
              </li><!-- STAFFS DROP DOWN ENDS HERE -->
              
            </ul>

        </div>

      <div class="nav-collapse collapse">
        <ul class="nav" id="frontpageTopmenu">
          <li><a href={{URL::route('home')}}>Search products / Cart</a></li>
         <!-- <li><a href={{URL::route('search')}}>Search</a></li>-->
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">More... <b class="caret"></b></a>
            <ul class="dropdown-menu">
               @if( User::permitted( 'role.sales manager') )
                <li>
                  <a href={{URL::route('todaysale')}}>Todays sales</a>
                </li>
               @endif
              <li>
                <a href={{URL::route('sale_entries')}}>Bank entries</a>
              </li>
              <li>
                <a href={{URL::route('sales.expenditure')}}>Expenditures</a>
              </li>
            </ul>
          </li>
          
        </ul>
          {{--@include('searchbar')--}}
      </div><!--/.nav-collapse -->
    @endif
    </div>
  </div>
</div>