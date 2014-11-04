<div class="row-fluid">
	<div id="extration_button" class="">
		<div class="btn-group">
			<button class="btn btn-small btn-yellow">Extract customer's:</button>

			<button class="btn btn-small btn-yellow dropdown-toggle" data-toggle="dropdown">
				<i class="icon-angle-down"></i>
			</button>

			<ul class="dropdown-menu dropdown-yellow">
				<li>
					<a href="#" modal-url-customer-extractor={{URL::route('extractor', array('email'))}}>Email</a>
				</li>
				<li>
					<a href="#" modal-url-customer-extractor={{URL::route('extractor', array('phone'))}}>Phone</a>
				</li>
			</ul>
		</div>
	</div>
	
	<br>

	<table id="listcustomers" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Birthday</th>
				<th><small>Customer Type</small></th>
				<th><small>Registered By</small></th>
				<th>Last visit</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		
 @if( isset($customers) && $customers != null)
	@foreach($customers as $customer)
			<tr id="customer-{{$customer['id']}}" class="deletethis">
				<td class="center">
					<label>
						<input type="checkbox" name="checkbox" value="{{$customer['id']}}"/>
						<span class="lbl"></span>
					</label>
				</td>

				<td class="customername">
					<a href={{URL::route('admingetcustomerhistory', array($customer['id'], 'today'))}} class="customernamelink">{{{ucwords($customer['name'])}}}</a>
					<a href="#myModal" data-rel="tooltip" data-original-title="Default" ref-type="view" modal-url-customer={{URL::route('adminpreviewcustomerprofile', array($customer['id']))}} title="Click to view and edit {{{$customer['name']}}}'s profile">
						<i class="icon-external-link-sign blue pull-right"></i>
					</a>
				</td>

				<td class="customerphone">
					{{{$customer['phone']}}}
				</td>

				<td class="customeremail">
					{{{$customer['email']}}}
				</td>

				<td class="customerbirthday">
					{{{dob_date_format($customer['birthday'])}}}
				</td>

				<td class="customermode">
					{{{ucwords(Mode::getModeNameFromID($customer['mode_id']))}}}
				</td>

				<td class="customercreatedby">
					{{{$customer['createdby']}}}
				</td>

				<td>
				@if( $customer['customerlog'] != null )
					{{{format_date($customer['customerlog']['updated_at'])}}}
				@else
					Never visited
				@endif
				</td>

				<td>
					<div class="hidden-phone visible-desktop action-buttons">
						<a class="red single_delete" href="#" ref-type="delete" ref-url="" title="Delete {{{ucwords($customer['name'])}}}">
							<i class="icon-trash bigger-130"></i>
						</a>
					</div>
				</td>
				
			</tr>
@endforeach	
@endif
		</tbody>
	</table>
</div>
							

<!--inline scripts related to this page-->
<script type="text/javascript">
	$(function() {

		var oTable1 = $('#listcustomers').dataTable( {
		"aoColumns": [
						{ "bSortable": false }, null, null, null, null, null, null, null, { "bSortable": false }
					] 
				} );
		
		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
			.each(function(){
				this.checked = that.checked;
				$(this).closest('tr').toggleClass('selected');
			});
		});

		//Preview and Edit Customers Info
		var customerPreviewEdit = function (e){
			var $that = $(this), url = $(this).attr('modal-url-customer');

			$that.off('click.customerPreviewEdit', customerPreviewEdit);

			$.get(url, function(data) {

				cloneModalbox( $('#myModal') )
				.css({'width':'800px'})
				.centerModal()
				.find('.modal-body').html(data)
				.end()
				.find('.modal-header h3')
				.text('Customer Preview and Edit')
				.css({'color':'white'}).removeClass('red lighter')
				.end()
				.find('.modal-footer > [data-ref="submit-form"]')
				.hide()
				.end()
				.modal();

				$that.on('click.customerPreviewEdit', customerPreviewEdit);

			});
		};
		$("a[modal-url-customer]").on('click.customerPreviewEdit', customerPreviewEdit);

		//Modal call for extract
		$("a[modal-url-customer-extractor]").on('click',function(){
			var url;
			url = $(this).attr('modal-url-customer-extractor');

			$.get(url, function(data) {

				cloneModalbox( $('#myModal') )
				.css({'width':'800px'})
				.centerModal()
				.find('.modal-body').html(data)
				.end()
				.find('.modal-header h3').text('Extracted')
				.css({'color':'white'}).removeClass('red lighter')
				.end()
				.find('.modal-footer > [data-ref="submit-form"]').hide()
				.end()
				.modal();

			});
		});

		//Deleting of items
		$('.single_delete, .multiple_delete').deleteItemx({
			url: "{{URL::route('admindeletecustomers')}}",
			rollNameClass:'customernamelink'
		});
			
	})
</script>