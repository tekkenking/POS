<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">

      <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a href="{{URL::route('home')}}" class="brand pull-right" style="padding:0; margin-left:10px">
        {{HTML::image('vendor/bucketcodes/img/logo1_small_black.jpg', 'Glamour56_logo', array('width'=>'43'))}}
      </a>

      <!--<div class="pull-right" style="margin-right:30px; margin-top:5px"></div>-->

      <div class="nav-collapse collapse">

          <ul id="adminTopmenu" class="nav">

            <?php $menuq = array('dashboard', 'stock manager'); ?>
            @foreach($menuq as $mq)
              <?php unset($menuSet); $menuSet = MenuClass::getMenuArray($mq); ?>  
              @if( User::permitted( $menuSet['role'] ) )
                  <li>
                    <a href="{{URL::route($menuSet['urlname'])}}">
                      {{$menuSet['name']}}
                    </a>
                  </li>
              @endif
            @endforeach

              @if( User::permitted( 'role.admin') )
                <li class="dropdown">
                  <a data-toggle="dropdown" class="dropdown-toggle" href="#">More... <b class="caret"></b></a>
                  <ul class="dropdown-menu">

                    <?php $menux = array('staff/customer', 'history records', 'bank records', 'expenditures', 'vendors', 'stock update record'); ?>

                    @foreach($menux as $mx)
                    <?php unset($menuSet); $menuSet = MenuClass::getMenuArray($mx); ?>
                      <li>
                        <a href="{{URL::route($menuSet['urlname'])}}">
                          {{$menuSet['name']}}
                        </a>
                      </li>
                    @endforeach

                  </ul>
                </li>
              @endif

            </ul>


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
                 
              <?php unset($menuSet); $menuSet = MenuClass::getMenuArray('cart'); ?>
              @if( User::permitted( $menuSet['role'] ) )
                  <li>
                    <a href="{{URL::route($menuSet['urlname'])}}">
                     <i class="icon-shopping-cart"></i>
                      {{$menuSet['name']}}
                    </a>
                  </li>
              @endif

              <?php unset($menuSet); $menuSet = MenuClass::getMenuArray('systemsettings'); ?>
              @if( User::permitted( $menuSet['role'] ) )
                  <li>
                    <a href="{{URL::route($menuSet['urlname'])}}">
                     <i class="icon-cogs"></i>
                      {{$menuSet['name']}}
                    </a>
                  </li>

                  <li class="divider"></li>
              @endif
                  <li>
                   <a href="{{URL::route('logout')}}" class="">
                          <i class="icon-off"></i> Logout
                        </a>
                  </li>
                </ul>
              </li><!-- STAFFS DROP DOWN ENDS HERE -->
            </ul>


         {{--@include('searchbar')--}}

      </div><!--/.nav-collapse -->

    </div>
  </div>
</div>