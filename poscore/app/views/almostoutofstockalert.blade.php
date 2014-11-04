  <?php 
    $alertArray = AlertController::productAlmostOutOfStock('all'); 
    $count = $alertArray['count'];
    $products = $alertArray['products'];

      if( $count > 0 ){
        $alert_icon = 'icon-animated-bell';
        $counter_color = 'badge-important';
      }else{
        $alert_icon = '';
        $counter_color = 'badge-grey';
      }
  ?>

   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="icon-bell-alt {{$alert_icon}}"></i>
      <span class="badge {{$counter_color}} counter_outofstock">{{$count}}</span>
    </a>
  <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer list_outofstock">
    @include('almostoutofstock_list')
  </ul>
  
<script>
$(document).ready(function(){
    //This is to load the stock alert items when clicked on the alert
    $(this).on('click', '#productAlert > a', function(e){
      if( $('#productAlert').hasClass('open') === true )
      $('#productAlert .list_outofstock').load("{{URL::route('outofstockwarning_list')}}")
    });

    $(this).on('click', "a[modal-data='view_all_stockalert']", function(){
        //var url;
        var data =  {{json_encode($products)}};
        // _debug(data);
        var ofs_table = "<table class='table table-bordered'>";
        ofs_table += "<thead>";
        ofs_table += "<th>Name</th>";
        ofs_table += "<th>Qty in stock</th>";
        ofs_table += "<th>Status</th>";
        ofs_table += "<th>Goto</th>";
        ofs_table += "</thead>";
        ofs_table += "<tbody>";
        $.each(data, function(i,v){
          var isRed = (v.quantity > 0) ? '' : 'red bolder';
          ofs_table += "<tr class="+ isRed +">";
          ofs_table += "<td><span class='bolder'>"+v.name.capitalize()+"</span></td>";
          ofs_table += "<td>"+v.quantity+"</td>";
          var status = (v.quantity > 0) ? 'Almost out of stock' : 'Out of stock';
          ofs_table += "<td>"+status+"</td>";
          ofs_table += "<td><a href='"+ v.linktoproduct +"' class='bolder'>stock</a></td>";

          ofs_table += "</tr>";
        });
        ofs_table +="</tbody>";
        ofs_table +="</table>";

          $('#myModal').clone().addClass('myModalCloned')
          .modal()
          .css({'width':'800px', 'margin-top':'-20px'})
          .centerModal()
          .find('.modal-body').html(ofs_table).css({'max-height':'400px', 'overflow':'auto'})
            .end()
          .find('.modal-header h3')
          .text('Viewing all stock notifications')
            .end()
          .find('[data-ref="submit-form"]').hide();
      });
});
</script>