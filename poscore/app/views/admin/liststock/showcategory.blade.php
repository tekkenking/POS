@section('sidebar')
<div class="red logoarea">
	<a href={{URL::route('adminShowCategory', array(slug($brand->name)))}}>{{imging($brand['brandlogo'])}}</a>
</div>


<li>
	<a href="#myModal" modal-url-add-product-category={{URL::route( 'adminAddCategory', array($brand->id,'addproductcategory') )}} title="Add product category">
		<i class="icon-plus bigger-230 blue"></i>
		<span class="menu-text">Add product category</span>
	</a>
</li>

<li>
	<a href="#myModal" modal-url-add-service-category={{URL::route( 'adminAddCategory', array($brand->id, 'addservicecategory') )}} title="Add service category">
		<i class="icon-plus bigger-230 orange"></i>
		<span class="menu-text">Add service category</span>
	</a>
</li>

<li>
	<a href="#" multiple-checkbox>
		<i class="icon-ok bigger-230 green"></i>
		<span class="menu-text">Check all categories</span>
	</a href="#">
</li>

<li>
	<a class="multiple_delete" href="#">
		<i class="icon-trash bigger-160 red"></i>
		<span class="menu-text">Delete selected items</span>
	</a>
</li>

<li>
	<a href="../stock">
		<i class="icon-arrow-left bigger-160 orange"></i>
		<span class="menu-text">One step back</span>
	</a>
</li>
@stop


<div class="alert  alert-info" style="margin-bottom:5px">
	<i class="icon-list bigger-130"></i>
	<h4 style="display:inline-block" class="small">
		CATEGORY
		<small class="blue">
			<strong>
			<i class="icon-double-angle-right"></i>
			{{{ucwords($brand->name)}}}
			</strong>
		</small>
	</h4>
</div>
<div class="categories-container">
@if( !empty($category) )
<?php $counter=0 ?>

	@foreach( $category as $cat )
		
		@if($counter == 0) <div class="row-fluid"> @endif

		@if($counter <= 4)
			<div class="span3 widget-container-span deletethis">
				<div class="widget-box light-border">
					<div class="">
						<h3 class="smaller green" style="margin:0">
							<span class="editable-view" editable-view-id="{{$cat['id']}}">
								{{{ucfirst($cat['name'])}}}
							</span>
						</h3>
					</div>

					<div class="widget-header widget-header-small header-color-dark">
						<h5></h5>
						<div class="widget-toolbar">
							<a href="#" class="togglepublished" status-id={{$cat['id']}} status-url={{URL::route('categoryStatus')}}> 
								<i class="@if($cat['published'] == 1) green icon-ok-sign @else red icon-minus-sign @endif bigger-130"></i>
							</a> 
						</div>

						<div class="widget-toolbar">
							<a href="#" class="editable-controller"><i class="icon-pencil orange bigger-130"></i></a>
							<a class="editable-model" data-pk="{{$cat['id']}}" data-title="Update category name"></a>
						</div>

						<div class="widget-toolbar">
							<a href="#" class="styledcheckbox action-buttons" checkbox-value="{{$cat['id']}}"></a>
						</div>
					</div>

				<a href={{URL::route('adminShowProduct', array(slug($brand->name), slug($cat['name'])) )}} class="category-link-container">
					<div class="widget-body">
						<div class="widget-main padding-6">
@if($cat['type'] == 'service') <i class="icon-star light-blue"></i> @endif
PRODUCTS:  <span class="red bolder">{{{Productcategory::countProducts($cat['id'])}}}</span>
						</div>
					</div>
				</a>
				</div>
			</div>
			<?php $counter++; ?>
		@endif

		@if($counter >= 4)
			<?php $counter = 0; ?>
		@endif

		@if($counter == 0) </div> @endif

	@endforeach
@endif
</div>


<script>
$(document).ready(function(){

//Toggles status for products
		$('.togglepublished').toggleStatus();

/*********** EDITABLE STARTS HERE  *********/
		var editable_id, that;

		$('.editable-controller').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			editable_id = $(this).closest('.widget-header').find('.editable-model').attr('data-pk');
			that = '[data-pk="'+  editable_id +'"]';
			$(that).editable({
				display:function(value){
					if( $.trim(value) !== '' )
						$('[editable-view-id="'+editable_id+'"]').text(value);
				},
				emptytext:'',
				type: 'text',
				url: "{{URL::route('updatecategory')}}",
				name:'name',
				placement:'bottom'
			});

			$(that).editable('toggle');
		});
/*********** EDITABLE ENDS HERE  *********/

	$('.styledcheckbox').styledCheckbox({
		iconSize: 'bigger-120',
		iconcheck: 'icon-check-empty',
		iconchecked: 'icon-check-sign',
		name:'checkbox',
		checkedClass: 'white-text',
	});

	$('.multiple_delete').deleteItemx({
			url: "{{URL::route('deletecategory')}}",
			afterDelete:checkStockAlertAfterPayment,
			afterDelete_args:"{{URL::route('outofstockwarning_count')}}",
			ajaxRefresh: true,
		});

	//calling modalbox for adding product category
	var addProductCategory = function (e){
		var $that = $(this), url = $(this).attr('modal-url-add-product-category');

		$that.off('click.addProductCategory', addProductCategory);

		$.get(url, function(data) {

			var modalClone = cloneModalbox($('#myModal'));

			modalClone
			.modal()
			.css({'width':'600px', 'margin-top':'-20px'})
			.find('.modal-body')
			.html(data)
			.css({'max-height':'400px', 'overflow':'auto'})
				.end()
			.find('.modal-header h3')
			.html('Add product categories');

			$that.on('click.addProductCategory', addProductCategory);
		});
	}

	$("a[modal-url-add-product-category]").on('click.addProductCategory', addProductCategory);


	//calling modalbox for adding service category
	var addServiceCategory = function (e){
		var $that = $(this), url = $(this).attr('modal-url-add-service-category');

		$that.off('click.addServiceCategory', addServiceCategory);

		$.get(url, function(data) {

			var modalClone = cloneModalbox($('#myModal'));

			modalClone
			.modal()
			.css({'width':'600px', 'margin-top':'-20px'})
			.find('.modal-body')
			.html(data)
			.css({'max-height':'400px', 'overflow':'auto'})
				.end()
			.find('.modal-header h3')
			.html('Add service categories');

			$that.on('click.addServiceCategory', addServiceCategory);
		});
	}

	$("a[modal-url-add-service-category]").on('click.addServiceCategory', addServiceCategory);

});
</script>