<h3 class="blue nomargin bolder"> RETAIL DETAILS </h3>
<hr class="no-margin">
<div class="row-fluid">
	<div class="span12 purple">
		<div class="span7">
			<h3 class="nomargin bolder nomargin">- Total Worth:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($retail_price)}}</h3>
		</div>
	</div>	
</div>

<div class="row-fluid">
	<div class="span12 purple">
		<div class="span7">
			<h3 class="bolder nomargin">- Total Unit Costprice:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($costprice)}}</h3>
		</div>
	</div>
</div>	

<div class="row-fluid">
	<div class="span12 green">
		<div class="span7">
			<h3 class="bolder nomargin">- Total Profit Worth:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($retail_profit)}}</h3>
		</div>
	</div>
</div>


<h3 class="blue nomargin bolder"> WHOLESALE DETAILS </h3>
<hr class="no-margin">
<div class="row-fluid">
	<div class="span12 red">
		<div class="span7">
			<h3 class="bolder nomargin">Total Worth:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($wholesale_price)}}</h3>
		</div>
	</div>	
</div>

<div class="row-fluid">
	<div class="span12 red">
		<div class="span7">
			<h3 class="bolder nomargin">Total Unit Costprice:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($costprice)}}</h3>
		</div>
	</div>
</div>	

<div class="row-fluid">
	<div class="span12 green">
		<div class="span7">
			<h3 class="bolder nomargin">Total Profit Worth:</h3>
		</div>
		<div class="span5">
			<h3 class="bolder nomargin">{{Config::get('software.currency_logo')}}{{format_money($wholesale_profit)}}</h3>
		</div>
	</div>
</div>

