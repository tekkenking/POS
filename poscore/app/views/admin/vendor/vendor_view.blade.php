<div class="row-fluid">

	<div class="inline pull-right" style="position:absolute; left:4em; top:1em;">
	    <button class="btn btn-small btn-danger multiple_delete">Delete all selected</button>
	</div>

	{{--$entries->links()--}}

	<div id="create-task-msg-error" class="error-msg"></div>

	<table id="vendors-table" class="table table-bordered table-hover">
		<thead>
			<tr style="" class="blue">
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>Name</th>
				<th>Email</th>
				<th>Phone number</th>
				<th>Address</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@if( $entries !== '' || $entries !== null )
				@foreach( $entries as $entry )
				<tr id="data-{{$entry->id}}" class="bolder force-smalltext deletethis">
					<td class="center">
						<label>
							<input type="checkbox" name="checkbox" value="{{$entry->id}}"/>
							<span class="lbl"></span>
						</label>
					</td>

					<td>
						@if($entry->comment !== '')
							<i class="icon-book orange comment" title="{{$entry->comment}}" style="cursor:pointer"></i>
						@endif
						<span class="name">{{istr::title($entry->name)}}</span>
					</td>

					<td>
						<span class="email">{{istr::title($entry->email)}}</span>
					</td>

					<td>
						<span class="phone">{{istr::title($entry->phone)}}</span>
					</td>

					<td>
						<span class="address"> {{istr::title($entry->address)}} </span>
					</td>

					<td class="delete td-actions">
						<div class="hidden-phone visible-desktop action-buttons">
							<a class="blue vendor_edit" href="#" edit_id="{{$entry->id}}" 
							edit_url="{{URL::route('vendor.showedit')}}">
								<i class="icon-edit bigger-130"></i>
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a class="red single_delete" href="#">
								<i class="icon-trash bigger-130"></i>
							</a>
						</div>
					</td>
				</tr>
				@endforeach
			@endif
		</tbody>
	</table>

	{{--$entries->links()--}}
</div>

{{--Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker')->show('scripts')--}}

<script type="text/javascript">
	$(function(){
		/***Deleting table row***/
		$('.single_delete, .multiple_delete').deleteItemx({
			url: "{{URL::route('vendor.delete')}}"
		});

		/***Dataset table***/
		var oTable1 = $('table#vendors-table').dataTable( {
						"aoColumns": [
							{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
						],

						"bPaginate": false,
					} );
		
		/****Checkboxes****/
		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
			.each(function(){
				this.checked = that.checked;
				$(this).closest('tr').toggleClass('selected');
			});
		});

		//Pagination relook
		$('.pagination').addClass('no-margin');

		
		/*****Call modal for Edit vendor*****/
		var vendorEdit = function(e) {
			e.preventDefault();
			var rowID 	= $(this).attr('edit_id'),
				url 	= $(this).attr('edit_url'),
				$that	= $(this);

				//Unbind click event
				$that.off('click.vendorEdit', vendorEdit);
				
				//Lets highlight the active role
				$(this).closest('tr').addClass('khaki-bg');
				
				//Lets get the edit form in a modalbox
				$.get(url, {id:rowID}, function(data) {

					cloneModalbox( $('#myModal') )
					.css({'width':'550px'})
					.centerModal()
					.find('.modal-body').html(data)
					.end()
					.find('.modal-header h3')
					.text('Edit Vendor')
					.end()
					.find('.modal-footer [data-ref="submit-form"]')
					.hide()
					.end()
					.modal();

					//Rebind click event
					$that.on('click.vendorEdit', vendorEdit);

				}, 'html');

		};

		$('.vendor_edit').on('click.vendorEdit', vendorEdit)

		//Lets Unhighlight the active role
		$(document).on('hidden', '.myModalCloned', function(){
			$('tr.khaki-bg').removeClass('khaki-bg');
		});

	});
</script>