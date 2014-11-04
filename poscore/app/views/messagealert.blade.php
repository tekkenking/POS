 <?php 
   $msgArray = AlertController::unreadInboxMessageAlert('all'); 
   $count = $msgArray['count']; 
   $messages = $msgArray['messages'];

      if( $count > 0 ){
        $alert_icon = 'icon-animated-vertical';
        $counter_color = 'badge-success';
      }else{
        $alert_icon = '';
        $counter_color = 'badge-grey';
      }
 ?>
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-envelope {{$alert_icon}}"></i>
            <span class="badge {{$counter_color}} counter_messagealert">{{$count}}</span>
          </a>

          <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer list_messagealert">
            @include('messagealert_list')
          </ul>

<script type="text/javascript">
$(document).ready(function(){

    //This is the counter for messagealert update
    //setInterval(function(){ 
     // $.get("{{URL::route('unreadinboxmessagealert_count')}}", function(data){
     //       $('#messageAlert .counter_messagealert').text(data.msgcount)
     // });
    //}, 10000); //10secs

   //This is to load the message alert when clicked on the alert
    $(this).on('click', '#messageAlert > a', function(e){
      if( $('#messageAlert').hasClass('open') === true )
        /*$.get("{{URL::route('unreadinboxmessagealert_list')}}", function(data){
             $('#messageAlert .list_messagealert').html(data);
        });*/

      $('#messageAlert .list_messagealert').load("{{URL::route('unreadinboxmessagealert_list')}}")
    });

    //Modal call for Add product
    $(this).on('click','[modal-url-quickview]',function(){
      var url = $(this).attr('modal-url-quickview');

      $.get(url, function(data) {

        $('#myModal').modal()
        .css({'width':'600px'})
        .centerModal()
        .find('.modal-body').html(data);

        $('#myModal > .modal-header h3')
        .text('Message: Quick view');

        $('#myModal > .modal-footer > [data-ref="submit-form"]')
        .hide();

      });

    });
});
</script>