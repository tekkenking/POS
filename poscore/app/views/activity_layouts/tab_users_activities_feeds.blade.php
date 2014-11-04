@section('sidebar')

@include('admin.usersplace_sidemenu')

@stop

<div class="widget-box">
	<div class="widget-header">

		<h4> <i class="red icon-rss bigger-140"></i> Activity Feeds <span class="feedloaderplace" style="margin-left:30px"></span></h4>

		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs">
				<li class="active">
					<a href="#staffsactivities" data-toggle="tab" class="ajaxable" data-mode="staffsactivities" data-url={{URL::route('adminstaffsactivityfeeds')}}>
						<i class="green icon-tasks bigger-120"></i>
							<span style="display:inline-block">Staffs Activities</span>
					</a>
				</li>

				<li>
					<a href="#staffssaleactivities" data-toggle="tab" class="ajaxable" data-mode="staffssaleactivities" data-url={{URL::route('adminstaffssalesactivityfeeds')}}>
						<i class="blue icon-money bigger-120"></i>
							<span style="display:inline-block">Last 10 Sales</span>
					</a>
				</li>

			</ul>
		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<div class="padding-8 overflow-visible tab-content">
				<div class="tab-pane active" id="staffsactivities">
					@include('activity_layouts.activities_feed_2columns')
				</div>
			</div>
		</div>
	</div>

</div>

<script>

$(document).ready(function(){
	$('.ajaxable').ajaxLoadTabContent({
		//loadInterval: 5000, //5 Secs
		loader: 'loading...',
		loaderTargetPlace:'.feedloaderplace',
		extraParamsCallback: 'getProductModeParam(that)',
	});

	//Would add active feature to side menu
	$('#admin_sidemenu_activityfeeds').addClass('active');

	//Adding active to the topmenu
	$('#adminTopmenu > li').eq(2).addClass('active');
	//Adding active to the topmenu submenu
	$('#adminTopmenu > li.active > ul li').eq(0).addClass('active');
});

function getProductModeParam(that){
		targetDiv = that.attr('data-mode');
		$('.tab-content > div').prop('id', targetDiv);
		return targetDiv;
	}

</script>