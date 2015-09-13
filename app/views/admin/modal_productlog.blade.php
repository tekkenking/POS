<div id="wideAjaxSearch" class="wideAjaxSearch status-msg"></div>
		{{Form::open(array('route'=>'productsaverecord', 'id'=>'record', 'class'=>'form-horizontal' ))}}
		{{Form::hidden('product_id', $product['id'])}}
		{{Form::hidden('name', $product['name'])}}
		<div class="input-append">
			{{Form::text('quantity_removed', '', array('class'=>'span3', 'id'=>'reduce_log', 'placeholder'=>'Enter quantity for reduction'))}}
			<button data-ref="submit-form" type="submit" class="btn btn-purple btn-small">
				<i class="icon-warning-sign icon-1x"></i> Update
			</button>
		</div>

		<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">
			{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}
		</span>
	{{Form::close()}}


{{-- SEPARATE --}}


<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th><small>Q. Deducted</small></th>
       <th><small>T. Unit Price</small></th>
       <th><small>T. Discount Price</small></th>

      <th><small>Q. Remaining</small></th>
      <th><small>T. Unit Price Remaining</small></th>
     
      <th><small>Admin Name</small></th>
      <th><small>Date</small></th>
    </tr>
  </thead>
  <tbody class="logbody">

  @if($records != null AND !empty($records))
  	@foreach($records as $record)
	    <tr>
	      <td></td>
	      <td>{{{$record['quantity_removed']}}}</td>
	      <td>N{{{format_money($record['total_lostprice'])}}}</td>
	      <td>N{{{format_money($record['total_discountprice'])}}}</td>
	      <td>{{{$record['quantity_remaining']}}}</td>
	      <td>N{{{format_money($record['total_remainingprice'])}}}</td>
	      <td>Generic</td>
	      <td>{{{$record['created_at']}}}</td>
	    </tr>
	@endforeach
   @else
   		<tr class="emptyLog">
   			<td colspan="8" style="text-align:center">
   				<h3 class="blue"><i class="icon-smile icon-3x"></i> No record yet!</h3>
   			</td>
   		</tr>
   @endif
  </tbody>
</table>

<script>
$(document).ready(function(){

	$('[data-rel="tooltip"]').tooltip();

	$('[data-ref="submit-form"]').on('click',function(e){
		e.preventDefault();

		$(this).ajaxrequest_wrapper({
			url: $('form#record').attr('action'),
			validate: { 
						quantity_removed:
							{
								required:'Field can not be empty', 
								integer:'value must be number'
							}
					},
			wideAjaxStatusMsg: '.wideAjaxSearch',
			//ajaxRefresh: '.modal-body',
			functionCallback:"updateList(data)",
		});
	});


});

function updateList(data){
	if(data !== undefined &&  data.data !== undefined){
			var htmlLogBody = '';
			htmlLogBody +=  "<tr>";
		    htmlLogBody +=  "<td></td>";
		    htmlLogBody +=  "<td>"+ data.data.quantity_removed +"</td>";
		    htmlLogBody +=  "<td>N"+ format_money(data.data.total_lostprice, 2) +"</td>";
		    htmlLogBody +=  "<td>N"+ format_money(data.data.total_discountprice, 2)+"</td>";
		    htmlLogBody +=  "<td>"+ data.data.quantity_remaining +"</td>";
		    htmlLogBody +=  "<td>N"+ format_money(data.data.total_remainingprice, 2) +"</td>";
		    htmlLogBody +=  "<td>Generic</td>";
		    htmlLogBody +=  "<td>"+ data.data.created_at +"</td>";
		    htmlLogBody +=  "</tr>";

		    if( $('.logbody tr.emptyLog').text() !== '' ){
		    	$('.logbody').html(htmlLogBody);
		    }else{
		    	$('.logbody').append(htmlLogBody);
		    }
	}
}

</script>