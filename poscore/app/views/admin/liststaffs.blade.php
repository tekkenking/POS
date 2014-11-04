
<div class="row-fluid">
	<table id="liststaffs" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>Name</th>
				<th>Username</th>
				<th>Phone</th>
				<th>Status</th>
				<th>Online</th>
			</tr>
		</thead>

		<tbody>
		
 @if( isset($staffs) && $staffs != null)
	@foreach($staffs as $staff)
			<tr id="staff-{{$staff['id']}}" class="deletethis">
				<td class="center">
					<label>
						<input type="checkbox" name="checkbox" value="{{$staff['id']}}"/>
						<span class="lbl"></span>
					</label>
				</td>

				<td class="staffname">
					{{{ucwords($staff['name'])}}}
					<a href="#myModal" data-rel="tooltip" data-original-title="Default" ref-type="view" modal-url-staff={{URL::route('adminpreviewstaffprofile', array($staff['id']))}} title="Click to view and edit {{{$staff['name']}}}'s profile">
						<i class="icon-external-link-sign blue icon-2x pull-right"></i>
					</a>
				</td>

				<td class="staffusername">
					{{{ucwords($staff['username'])}}}
				</td>

				<td class="stafftoken">
					{{{ucwords($staff['phone'])}}}
				</td>

				<td class="staffstatus">
					@if( $staff['isenabled'] === 1 )
						Enabled
					@else
						Disabled
					@endif
				</td>

				<td class="staffonline">
					<span class="label label-large label-purple arrowed-right">
						@if( $staff['isloggedin'] === 1 )
							<i class="icon-circle light-green" title="online"></i>
						@else
							<i class="icon-circle light-red" title="offline"></i>
						@endif
							&nbsp;
						@if( $staff['loggedtime'] === '' )
							<span>Never logged in</span>
						@else
							<span>{{{format_date($staff['loggedtime'])}}}</span>
						@endif
					</span>
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

		var oTable1 = $('#liststaffs').dataTable( {
		"aoColumns": [
						{ "bSortable": false }, null, null, null, null, null
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

		//Modal call for Adjust lost products
		$("a[modal-url-staff]").on('click',function(e){
			//_debug(e);
			//return false;
			var url;

			url = $(this).attr('modal-url-staff');

			$.get(url, function(data) {

				$('#myModal').modal()
				.css({'width':'800px'})
				.centerModal()
				.find('.modal-body').html(data);

				$('#myModal > .modal-header h3')
				.text('Staff Preview and Edit')
				.css({'color':'white'}).removeClass('red lighter');

				$('#myModal > .modal-footer > [data-ref="submit-form"]')
				.hide();

			});
		});
			
	})
</script>