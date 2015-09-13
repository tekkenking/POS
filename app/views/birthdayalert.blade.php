  <?php 
    $alertArray = AlertController::birthdayAlert(); 
    $count = $alertArray['count'];
    $customer_dob = $alertArray['customer_dob'];

      if( $count > 0 ){
        $alert_icon = 'icon-animated-bell';
        $counter_color = 'badge-warning';
      }else{
        $alert_icon = '';
        $counter_color = 'badge-grey';
      }
  ?>

   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="icon-gift {{$alert_icon}}"></i>
      <span class="badge {{$counter_color}} counter_birthday {{Session::get('birthdayalert_sound', '')}}">{{$count}}</span>
    </a>
  <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer list_birthday">
    @include('birthday_list')
  </ul>

  <script type="text/javascript">
  $(document).ready(function(){

    //This is a Sound notification reminder
       if( $('#birthdayAlert .counter_birthday').text() > 0 ){
           setInterval(function(){ 
            if( $('#birthdayAlert .counter_birthday').hasClass('stopsound') === false ){
             soundNotification('s3whistle');
            }
          }, 60000); //5mins sound reminder
      }

      //This part would stop the notification sound reminder
      $('#birthdayAlert > a').on('click', function(){
          $('#birthdayAlert .counter_birthday').addClass('stopsound');
          <?php Session::put('birthdayalert_sound', 'stopsound'); ?>
      });

      $("a[modal-data='view_all_dob']").on('click',function(){
        //var url;
        var data =  {{json_encode($customer_dob)}};
        // _debug(data);
        var dob_table = "<table class='table table-bordered'>";
        dob_table += "<thead>";
        dob_table += "<th>Date</th>";
        dob_table += "<th>Name</th>";
        dob_table += "<th>Phone</th>";
        dob_table += "<th>Email</th>";
        dob_table += "<th>Total purchased</th>";
        dob_table += "<th>Last visited</th>";
        dob_table += "</thead>";
        dob_table += "<tbody>";
        $.each(data, function(i,v){
          dob_table += "<tr>";
          dob_table += "<td>"+format_date(v.birthday)+"</td>";
          dob_table += "<td><span class='bolder'>"+v.name.capitalize()+"</span></td>";
          dob_table += "<td>"+v.phone+"</td>";
          dob_table += "<td>"+v.email+"</td>";
          var tp =  (v.customerlog === null ) ? 0.00 : v.customerlog.alltime_spent;
          dob_table += "<td><span class='red bolder'>N"+ format_money(tp, 2) +"k</span></td>";
          var tv = (v.customerlog === null ) ? 'Never purchased' : v.customerlog.updated_at.split(' ')[0];
          dob_table += "<td><span class='blue bolder'>"+ tv +"</span></td>";

          dob_table += "</tr>";
        });
        dob_table +="</tbody>";
        dob_table +="</table>";

           $('#myModal').clone().addClass('myModalCloned')
          .modal()
          .css({'width':'800px', 'margin-top':'-20px'})
          .centerModal()
          .find('.modal-body').html(dob_table).css({'max-height':'400px', 'overflow':'auto'})
            .end()
          .find('.modal-header h3')
          .text('Viewing all birthday notifications')
            .end()
          .find('[data-ref="submit-form"]').hide();
      });
  })
  </script>