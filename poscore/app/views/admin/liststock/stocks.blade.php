@section('sidebar')

<li>
	<a href="#myModal" modal-url-brand="{{URL::route('admincreatebrand')}}" title="Create new stock">
		<i class="icon-plus bigger-160 blue"></i>
		<span class="menu-text">Create brand</span>
	</a>
</li>

<li>
	<a href="#" class="multipletogglestatus">
		<i class="icon-ok-sign bigger-80 green"></i><span class="menu-text">On</span> 
		| 
		<i class="icon-minus-sign bigger-120 red"></i><span class="menu-text">Off</span> Status
	</a>
</li>

<li>
	<a class="multiple_delete" href="#">
		<i class="icon-trash bigger-160 red"></i>
		<span class="menu-text">Delete selected items</span>
	</a>
</li>

@stop


<div class="alert  alert-info" style="margin-bottom:5px">
	<i class="icon-list bigger-130"></i>
	<h4 style="display:inline-block" class="small">LIST OF BRANDS</h4>
	<button class="btn btn-yellow btn-mini pull-right" id="store-worth" data-url="{{URL::route('store.worth')}}">Store worth</button>
</div>

<div class="row-fluid">
	<table id="sample-table-2" class="table table-striped table-bordered table-hover">
		<thead>
			<tr style="">
				<th class="center">
					<label>
						<input type="checkbox" id="default_checkbox"/>
						<span class="lbl"></span>
					</label>
				</th>
				<th>Brands</th>
				<th class="center">Status</th>
				<th>Total Categories</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		
 @if($brands != null)
	@foreach($brands as $brand)
			<tr id="data-{{$brand->id}}" class="deletethis">
				<td class="center">
					<label>
						<input type="checkbox" name="checkbox" value="{{$brand->id}}"/>
						<span class="lbl"></span>
					</label>
				</td>
				<td class="brand force-smalltext force-bolder">
					<div class="span12">
						<div class="span10">
							<a class="editable-view" editable-view-id="{{$brand->id}}" href={{URL::route('adminShowCategory', array(slug($brand->name)))}}>{{{ucwords($brand->name)}}}</a>
						</div>
						<div class="span1 action-buttons">
							<a href="#" title="Update brand name" class="editable-controller"><i class="icon-pencil green bigger-130"></i></a>
							<a class="editable-model" data-pk="{{$brand->id}}" data-title="Update brand name"></a>
						</div>
						<div class="span1 action-buttons">
							<a href="#myModal" title="Update brand logo" data-key="{{$brand->id}}" data-brandlogo-url="brandlogoupdate_temp"><i class="icon-picture"></i></a>
						</div>
					</div>
				</td>
				<td class="center"> 
					<a href="#" class="togglepublished force-underline" status-id={{$brand->id}} status-url={{URL::route('brandStatus')}}>
					<?php if($brand->published == 1){ 
								$status_class="green icon-ok-sign";
								$status_title = "Enabled";
							}else{
								$status_class="red icon-minus-sign";
								$status_title = "Disabled";
							}
					?>
						<i  title="{{$status_title}}" class="{{$status_class}} bigger-160"></i>
					</a> 
				</td>
				<td> {{{Brand::countCategories($brand->id)}}} </td>
				
				<td class="td-actions">
					<div class="hidden-phone visible-desktop action-buttons">
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
</div>

<div class="hide brandlogoupdate_temp">
	<div class="row-fluid">
		<div class="span12">
			<div class="span6">
				<div class="logoplace inline">
				</div>

				<div class="hide deletelogodiv">
					<button data-delete-logo="" class="btn btn-inverse btn-minier bolder" title="Delete logo"> <i class="icon-trash bigger-160"></i></button>
				</div>
			</div>

			<div class="span6">
				{{Form::open(array('route'=>'logo', 'files' => true, 'id'=>'update_brandlogo', 'class'=>'form-horizontal' ))}}
					<div class="error-msg"></div>
					{{Form::hidden('id', '', array('id'=>'update_brandlogo_field'))}}
					{{Form::hidden('operation', 'updatelogo')}}

					{{Form::file('brandlogo', array('id'=>'inputchoosepix'))}}

					<button disabled=true class="btn btn-success btn-small bolder update_brandlogo" type="submit"> <i class="icon-upload bigger-140"></i> Upload logo </button>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
							

<!--inline scripts related to this page-->
<script type="text/javascript">
$(function($) {

	//Adding active to the topmenu
	$(this).find('#adminTopmenu > li').eq(1).addClass('active');

	//Toggles status for products
		$('.togglepublished, .multipletogglestatus').toggleStatus();

/*********** EDITABLE STARTS HERE  *********/
		var editable_id, that;

		$('.editable-controller').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();

			editable_id = $(this).closest('td').find('.editable-model').attr('data-pk');
			that = '[data-pk="'+  editable_id +'"]';
			//This must be called at this stage
			//feditable(that);
			$(that).editable({
				display:function(value){
					if( $.trim(value) !== '' )
						$('[editable-view-id="'+editable_id+'"]').text(value);
				},
				emptytext:'',
				type: 'text',
				url: "{{URL::route('updatebrand')}}",
				name:'name',
			});

			$(that).editable('toggle');
		});
/*********** EDITABLE ENDS HERE  *********/

		var oTable1 = $('#sample-table-2').dataTable( {
		"aoColumns": [
						{ "bSortable": false }, null, null,null, { "bSortable": false }
					],
		"bPaginate": false,
				} );
		
		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
			.each(function(){
				this.checked = that.checked;
				$(this).closest('tr').toggleClass('selected');
			});
		});
	
	
		/*$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
		function tooltip_placement(context, source) {
			var $source = $(source);
			var $parent = $source.closest('table')
			var off1 = $parent.offset();
			var w1 = $parent.width();
	
			var off2 = $source.offset();
			var w2 = $source.width();
	
			if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
			return 'left';
		}*/
		
		//Deleting table row
		$('.single_delete, .multiple_delete').deleteItemx({
			url: "{{URL::route('deletebrands')}}",
			afterDelete:checkStockAlertAfterPayment,
			afterDelete_args:"{{URL::route('outofstockwarning_count')}}"
		});

//Calling the store worth
	var storeWorth = function (e){

		$('#store-worth').off('click.store-worth');

		var url, modalClone = cloneModalbox( $('#myModal') );

		url = $(this).data('url');

		//_debug(url);

		$.get( url, function(data) {
			modalClone
			.modal()
			.css({width:'600px', 'margin-top':'-20px'})
			.centerModal()
			.find('.modal-body').html(data)
				.end()
			.find('.modal-header')
			.css({background:'grey'})
			.end()
			.find('.modal-header h3')
			.removeClass('grey lighter')
			.addClass('white bolder')
			.text('STORE WORTH AND PROFIT')
				.end()
			.find('[data-ref="submit-form"]').hide();

			$('#store-worth').on('click.store-worth', storeWorth);
		});

	};

	$('#store-worth').on('click.store-worth', storeWorth);
			 

//Adding Brand
	var addingBrand = function (e){

		$("a[modal-url-brand]").off('click.modal-url-brand');

		var url, modalClone = cloneModalbox( $('#myModal') );

		url = $(this).attr('modal-url-brand');

		$.get(url, function(data) {
			modalClone
			.modal()
			.css({'width':'400px', 'margin-top':'-20px'})
			.centerModal()
			.find('.modal-body').html(data).css({'max-height':'400px', 'overflow':'auto'})
				.end()
			.find('.modal-header h3')
			.text('CREATE BRAND')
				.end()
			.find('[data-ref="submit-form"]').hide();

			$("a[modal-url-brand]").on('click.modal-url-brand', addingBrand);
		});

	};

	$("a[modal-url-brand]").on('click.modal-url-brand', addingBrand);

//Changing brand logo or removing brand logo
	var changeBrandLogo = function (e){
		$("a[data-brandlogo-url]").off('click.changeBrandLogo');
		//_debug('camed');
		//return false;

		var pk = $(this).data('key'),
			temp = $('.'+ $(this).data('brandlogo-url') ).clone(),
			modalClone = cloneModalbox( $('#myModal') ),
			getlogo = "{{URL::route('logo')}}";

		$.get(getlogo, {id:pk, operation:'getlogo'}, function(response) {
			modalClone
			.css({'width':'600px', 'margin-top':'-20px'})
			.centerModal()
			.find('.modal-body').html(function(){

				//We set the Brand ID in the upload form as hidden input type
				$(temp).find('#update_brandlogo_field').val(response.id)

				//we add the image return from the server
				.end().find('.logoplace').attr('foundlogo', response.islogo).html(response.image)

				//We hide or show the delete button
				.end().find('.deletelogodiv').addClass(function(inddex){
					return (response.islogo === 'yes') ? 'inline' : '';
				})

				//we set the database ID of the brand to the delete button
				.end().find('.deletelogodiv > button').attr('data-delete-logo', response.id);

				//Return the none hidden version of the modalbox info
				return temp.show();
			}).css({'max-height':'400px', 'overflow':'auto'})
				
			.end().find('.modal-header h3')
			.html('Update <strong class="blue">'+ response.name.capitalize() +'</strong> logo')
				
			.end().find('[data-ref="submit-form"]')
			.hide()
			.end()
			.modal();

			$("a[data-brandlogo-url]").on('click.changeBrandLogo', changeBrandLogo);
		}, 'json');
	};

	$("a[data-brandlogo-url]").on('click.changeBrandLogo', changeBrandLogo);

	//Changing logo on ajax
	$(document).on('change.update_brandlogo','#inputchoosepix', function(e){
		//_debug('sae');
		$('.myModalCloned button.update_brandlogo').removeAttr('disabled');
	});


	//This is where we delete the logo
	$(this).on('click', 'button[data-delete-logo]', function(e){
		var getlogo = "{{URL::route('logo')}}",
			pk = $(this).attr('data-delete-logo'),
			$that = $(this);

		$.post(getlogo, {id:pk, operation:'deletelogo'}, function(response){
			$that.closest('.brandlogoupdate_temp')
				.find('.logoplace').attr('foundlogo', response.islogo).html(response.defaultlogo)
				.end().find('.deletelogodiv').addClass(function(index){
					$(this).removeClass('inline');
					return 'hide';
				});

		}, 'json');
	});
});
</script>
