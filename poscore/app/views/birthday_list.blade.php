
      <li class="nav-header">
        <i class="icon-gift"></i>
        @if($count == 0) 0 Birthday alert @elseif($count == 1) 1 Birthday alert @else {{$count}} Birthday alerts @endif
      </li>

@if( $count > 0 )
<?php $displayCounter = 0; $totalToDisplay = 5; $totalRemaining = 0; ?>
    @foreach($customer_dob as $dob)
        @if( $dob['birthday'] == sqldate('today') )
          <?php $when = 'Is today'; $label='label-purple label-large'; ?>
        @else
          <?php $when = 'In '. date_remaining($dob['birthday'], 'day'); $label='label-light label-large'; ?>
        @endif
      <li>
        <a href="#">
          <div class="clearfix">
            <span class="pull-left">
                <strong>{{ucwords($dob['name'])}}</strong>
                <br>
                <br>{{$dob['phone']}}
                <br><span class="bolder red">{{currency()}}{{format_money($dob['customerlog']['alltime_spent'])}}k</span>
            </span>
            <span class="pull-right">
              <span class="label {{$label}} pull-right">
               {{--tt($dob['birthday'], true)--}}
                {{dob_date_format($dob['birthday'])}}
              </span>
              <br>
              <br><span class="pull-right">{{$when}}</span>
              @if( $dob['customerlog']['updated_at'] !== NULL )
              <?php $lastvisited = ng_date_format($dob['customerlog']['updated_at']); ?>
              @else
                <?php $lastvisited = 'Never purchased'; ?>
              @endif
              <br><span class="blue bolder">{{$lastvisited}}</span>
            </span>
          </div>
        </a>
      </li>
        <?php ++$displayCounter; if( $displayCounter === $totalToDisplay ){ $totalRemaining = $count - $displayCounter; break; } ?>
    @endforeach
        <li>
          @if( $totalRemaining > 0 )
          <a href="#" modal-data="view_all_dob">
            <span class="bolder">
             {{$totalRemaining}} more. Click to see all <i class="icon-arrow-right"></i>
            </span>
          </a>
          @endif
        </li>
@endif