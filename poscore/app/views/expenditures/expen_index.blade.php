<div class="widget-container-span ui-sortable">
	<div class="widget-box">
		<div class="widget-header">
			<h4 class="lighter">Expenditures</h4>

			<div class="widget-toolbar no-border">
				<ul id="myTab2" class="nav nav-tabs">
					<li class="active">
						<a href="#view" data-toggle="tab" class="ajaxable" data-mode="view" data-url={{URL::route('sales.expenditure')}}> View </a>
					</li>

					<li>
						<a href="#create" data-toggle="tab" class="ajaxable" data-mode="create" data-url={{URL::route('sales.expenditure')}}> Create </a>
					</li>
				</ul>
			</div>
		</div>

		<div class="widget-body">
			<div class="widget-main padding-12 no-padding-left no-padding-right">
				<div class="tab-content padding-4">
					<div class="tab-pane in active" id="view">

						@include('expenditures.expen_view')

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('form').iePlaceholder();

		//Lets hide the sidebar
		$('.ajaxable').ajaxLoadTabContent({
			extraParamsCallback: "getTarget(that)"
		});

		$('#frontpageTopmenu > li').eq(1).addClass('active');
		//Adding active to the topmenu submenu
		$('#frontpageTopmenu > li.active > ul li').eq(2).addClass('active');

	});

	function getTarget(that){
		targetDiv = that.attr('data-mode');
		$('.tab-content > div').attr('id', targetDiv);
		return targetDiv;
	}
</script>