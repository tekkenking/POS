<div id="salesummary" style="height:250px">
	@include('admin.salesummarytable')
</div>

<div class="tab-footer">
TOTAL AMOUNT: <span style="font-size:16px" class="label label-important arrowed-in arrowed-right label-large">
{{currency()}}{{format_money($salesummary['totalamount'])}}k</span>
</div>

<script>
$(document).ready(function(){
	$('#salesummary').slimScroll({
		height: '350px',
		alwaysVisible : true,
		railVisible:true
	});
});
</script>