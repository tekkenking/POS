<div class="widget-box no-border">
	<div class="widget-header header-color-blue2">
	<h3 class=""> {{ucwords($brand->name)}} </h3>
		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs padding-32">
				<li class="active">
					<a href="#retail" data-toggle="tab" class="ajaxable" data-mode="retail" data-url={{URL::route('adminShowProduct', array(slug($brand['name']), slug($cat)) )}}>
						<i class="green icon-home bigger-110"></i>
						Retail
					</a>
				</li>

				<li class="">
					<a href="#wholesale" data-toggle="tab" data-mode="wholesale" class="ajaxable" data-url={{URL::route('adminShowProduct', array(slug($brand['name']), slug($cat)) )}}>
						Wholesale
					</a>
				</li>
				<!--<li class="">
					<a href="#distributor" data-toggle="tab" data-mode="distributor" class="ajaxable" data-url={{URL::route('adminShowProduct', array(slug($brand['name']), slug($cat)) )}}>
						Distributor
					</a>
				</li>
				<li class="">
					<a href="#majordistributor" data-toggle="tab" data-mode="majordistributor" class="ajaxable" data-url={{URL::route('adminShowProduct', array(slug($brand['name']), slug($cat)) )}}>
						Major Distributor
					</a>
				</li>-->
			</ul>
		</div>
	</div>


	<div class="widget-body">
		<div class="widget-main no-padding">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="retail">
					@include('admin.liststock.showproduct')
				</div>
			</div>
		</div>
	</div>

</div>


<script>

$(document).ready(function(){
	$('.ajaxable').ajaxLoadTabContent({
		extraParamsCallback: "getProductModeParam(that)",
		loader: '<span style="text-align:center; color:#222"><i class="icon-spinner icon-spin"></i> Loading...</span>',
		loaderTargetPlace: '.loadertargetplace',
	});
});

function getProductModeParam(that){
	targetDiv = that.attr('data-mode');
	$('.tab-content > div').attr('id', targetDiv);
//header-color-blue2
	var colors = {
			'retail':'blue2',
			'wholesale' : 'green',
			'distributor': 'orange',
			'majordistributor':'purple'
		};

	$('.widget-header').removeClass('header-color-blue2 header-color-green header-color-orange header-color-purple').addClass('header-color-'+colors[targetDiv]);

	return targetDiv;
}

</script>