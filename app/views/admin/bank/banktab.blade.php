<div class="widget-container-span ui-sortable">
	<div class="widget-box">
		<div class="widget-header">
			<h4 class="lighter">Bank deposit entry</h4>

			<div class="widget-toolbar no-border">
				<ul id="myTab2" class="nav nav-tabs">
					<li class="active">
						<a href="#viewentries" data-toggle="tab" class="ajaxable" data-mode="viewentries" data-url={{URL::route('entries')}}>View entries</a>
					</li>

					<li>
						<a href="#newentry" data-toggle="tab" class="ajaxable" data-mode="newentry" data-url={{URL::route('entries')}}>New entry</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="widget-body">
			<div class="widget-main padding-12 no-padding-left no-padding-right">
				<div class="tab-content padding-4">
					<div class="tab-pane in active" id="viewentries">

						@include('admin.bank.bank_viewentries')

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$('form').iePlaceholder();

	//Adding active to the topmenu
	$('#adminTopmenu > li').eq(2).addClass('active');
	//Adding active to the topmenu submenu
	$('#adminTopmenu > li.active > ul li').eq(2).addClass('active');

	//Lets hide the sidebar
	$('.ajaxable').ajaxLoadTabContent({
		extraParamsCallback: "getTarget(that)",
		//loader: '<span style="text-align:center"><i class="icon-spinner icon-spin"></i> Loading...</span>',
		//loaderTargetPlace: '.loadertargetplace',
	});

	//Function in g56_functions.js
	removeSidebarAndFitPage();

});

function getTarget(that){
	targetDiv = that.attr('data-mode');
	$('.tab-content > div').attr('id', targetDiv);
	return targetDiv;
}
</script>