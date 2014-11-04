
      <li class="nav-header">
        <i class="icon-warning-sign"></i>
        @if($count == 0) 0 stock alert @elseif($count == 1) 1 stock alert @else {{$count}} stock alerts @endif
      </li>
@if( $count > 0 )
<?php $displayCounter = 0; $totalToDisplay = 5; $totalRemaining = 0; ?>
@foreach( $products as $product )
      <li>
        <a href="{{$product['linktoproduct']}}">
          <div class="clearfix">
            <span class="pull-left">
                <strong>{{ucwords($product['name'])}}</strong>
                <br><small class="muted">@if($product['quantity'] > 0) <span class="bolder">Almost out of stock</span> @else <span class="red bolder">Out of stock</span> @endif</small>
            </span>
            <span class="pull-right">
              <span class="label label-yellow">
                {{$product['quantity']}}
              </span>
                <br><small class="muted">In stock</small>
            </span>
          </div>
        </a>
      </li>
       <?php ++$displayCounter; if( $displayCounter === $totalToDisplay ){ $totalRemaining = $count - $displayCounter; break; } ?>
@endforeach
        <li>
          @if( $totalRemaining > 0 )
          <a href="#" modal-data="view_all_stockalert">
            <span class="bolder">
             {{$totalRemaining}} more. Click to see all <i class="icon-arrow-right"></i>
            </span>
          </a>
           @endif
        </li>
     
@endif