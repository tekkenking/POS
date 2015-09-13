            <li class="nav-header">
              <i class="icon-envelope-alt"></i>
              <span style="border-bottom: 0 none; color: #708090; display: inline; font-size: 13px; font-weight: bold;">@if($count == 0) 0 message alert @elseif($count == 1) 1 new message alert @else {{$count}} new message alerts @endif</span>
              <a href="{{URL::route('messageinbox')}}" class="" style="position: absolute; right: 4px; font-weight: bold; font-size: 11px; top:-2px;">Goto inbox</a>

               
            </li>
@if( $count > 0 )
       @foreach( $messages as $message)
            <li style="margin-bottom:3px">
              <a href="#" style="cursor:default">
                <?php 
                    $genderx =  User::where('id', $message['from'])->pluck('gender');
                    $imagex = "uploads/img/". $genderx ."5.png"; 
                ?>
                {{HTML::image($imagex, 'Staff Avatar', array('width'=>'48', 'class'=>'img-circle'))}}

                <span class="msg-body">
                  <span class="msg-title">
                    <span class="blue">{{ucwords(User::getUsernameByID($message['from'], false))}}:</span>
                      {{$message['subject']}}
                  </span>

                  <span class="msg-time">
                    <i class="icon-time"></i>
                    <span>{{ago_date_format($message['created_at'])}}</span>
                    <button href="#myModal" modal-url-quickview={{URL::route('quickview', array($message['id']))}} class="btn btn-yellow btn-minier pull-right"><i class="icon-external-link"></i> view</button>
                  </span>
                </span>
              </a>
            </li>
        @endforeach
            <li>
              <!--<a href="#">
                See all messages
                <i class="icon-arrow-right"></i>
              </a>-->
            </li>
@endif