         {{Form::open(array('route'=>'searchproduct', 'id'=>'searchform', 'class'=>'navbar-search')) }}
            <div class="input-icon">
              {{Form::text('adminsearch', '', array('placeholder'=>'Search for records and customers', 'class'=>'span4'))}}
              <i class="icon-search"></i>
            </div>
          {{Form::close()}}