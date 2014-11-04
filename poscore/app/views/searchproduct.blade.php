<div class="productsearch-wrapper">
	<h4 class="header smaller lighter purple"><i class="icon-search"></i> Search for available products here:  </h4>
	{{ Form::open(array('route'=>'searchproduct', 'id'=>'myform', 'class'=>'form-horizontal', 'style'=>'position:relative;' )) }}

	<div class="control-group row-fluid">
		<div class="input-prepend span12">
			
		{{Form::text('products', '', array('id'=>'inputbrandname', 'class'=>'span8 input-block-level searchproduct', 'id'=>'searchproduct', 'autocomplete'=>'off'))}}

			<div name="salemode" data-options="Retail:1, Wholesale:2" data-attr="validate='required'" rel="selectoption" data-default="1" setvaluefrom="text"></div>
		</div>
	</div>

	<div class="control qf_radio">
		<span class="bolder light-green"> Search item by: </span>
		<label for="by_name" class="inline">
			<input type="radio" name="qf" id="by_name" value="name" checked="checked"/>
		 	<span class="lbl muted"> Name </span>
		</label>
			&nbsp; &nbsp; &nbsp; &nbsp; 
		<label for="by_barcodeid" class="inline">
			<input type="radio" name="qf" id="by_barcodeid" value="barcodeid"/>
		 	<span class="lbl muted"> Barcode </span>
		</label>
	</div>

	{{Form::close()}}
</div>

<div class="alert-info hidden-phone" style="position: relative; padding:10px; min-height: 285px;">
	<h3 class="header smaller lighter blue"><i class="icon-info-sign"></i>Frequently asked questions:</h1>

<!-- Accordion starts here -->
	<div id="accordion2" class="accordion">
		<div class="accordion-group">
			<div class="accordion-heading">
			<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
				How do check for a product price?
			</a>
			</div>
			<div id="collapseOne" class="accordion-body collapse" style="height: 0px;">
				<div class="accordion-inner">
					All you have to do is type the name of the product in the search bar and the price of the product would be displayed among other results, similar to the product name.
				</div>
			</div>
		</div>

		<div class="accordion-group">
			<div class="accordion-heading">
			<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
					How do i add product to the cart?
			</a>
			</div>
			<div id="collapseTwo" class="accordion-body collapse" style="height: 0px;">
				<div class="accordion-inner">
					When you've seen the product you want to add to the cart; just click on it and it would be added to the cart automatically.
				</div>
			</div>
		</div>

		<div class="accordion-group">
			<div class="accordion-heading">
			<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
					How do i remove a product from the cart?
			</a>
			</div>
			<div id="collapseThree" class="accordion-body collapse" style="height: 0px;">
				<div class="accordion-inner">
					Just click on the red "<i class="icon-remove red"></i>" icon of the product.
				</div>
			</div>
		</div>

	</div>
	<!-- Accordion endS here -->
</div>




<script>
	$(document).ready(function(){

		$('[rel="selectoption"]').bootstrap_selectoptions({
			//makeDefaultDuplicate: true,
			style: 'width:122px; overflow:hidden',
			hideSelected: true
		});

		//This would auto suggest the product you're looking for

		//$('#searchproduct').on('keydown', function(){
		//	var $shat = $(this);

		$('#searchproduct').ttypeahead({
			url: "{{URL::route('searchproduct')}}",
			updaterTemplate: "squeen_skin(item, map)",
			highlighterTemplate: "typhoon_skin(item, map, query)",
			csrf_token: $('input[name="_token"]').val(),
			barcodeLen:10
		});
		//});

		//This would prevent the PRODUCT search from responding to ENTERKEY
		//VERY IMPORTANT CODE FOR STABILITY
		$('#searchproduct').keydown(function(e){ 
			//_debug('keydown');
			if( e.which == 13 ){ 
				e.preventDefault(); 
				//_debug('tueyd');
			}
		});

	});

	//This is a customized function for TYPE HEAD highlighter
	function typhoon_skin(item, map, query){
		if(item === '') return false;
 
		var regex = new RegExp( '(' + query + ')', 'i' );
		var discountlabel ='', discount='';
		var htmlTemplate = '<div class="row-fluid">';
			htmlTemplate += '<div class="span12">';
			htmlTemplate += '<div class="span7" style="overflow: hidden; word-wrap:break-word; min-height:20px">';
			htmlTemplate += map[item].name.capitalize().replace(regex,"<span class='red'>$1</span>" );
			htmlTemplate += '</div>';
				if( map[item].categories.type === 'product'){
					htmlTemplate += '<div class="span5"><span class="badge badge-warning">'+ map[item].quantity +'</span> Qty in stock</div><br>';
				}
			htmlTemplate += '<div class="pull-left" style="white-space:nowrap">';
			htmlTemplate += '<span class="label label-large label-purple">' + map[item].brand.name.capitalize() + '</span>';

			//If discount is available
				if( map[item].discount > 0 ){
					discountlabel = 'label label-large label-yellow';
					discount = ' <span class="label label-large label-inverse">'+ map[item].discount +'%</span>';
				}

			htmlTemplate += ' <span class="label label-large label-info">'+ map[item].categories.name.capitalize() +'</span>';
			htmlTemplate += ' <span class="'+ discountlabel +'">{{currency()}}' + format_money(map[item].price - (map[item].price * map[item].discount/100),2) +'k</span>'+ discount;

			htmlTemplate += '</div>';
			htmlTemplate += '</div>';
			htmlTemplate += '</div>';
		return htmlTemplate;
	}

	function squeen_skin(item, map){
		var HTMLBODY, $finderTR, discount, productname, found = false, mode, isHidden='hidden', discounted_price='', crossPrice='';
			productname =  map[item].name.capitalize();
			mode = $('input[name="salemode"]').val();
			$finderTR = $('.cart-place').find('tr[idx='+map[item].id+']');
			//_debug($('.cart-place').find('tr[idx='+map[item].id+']').text());

				//We first check if the Item is listed
				if( $finderTR.text() !== ''){

					//Then we check if the Listed item with its salemode is not listed
					$('.cart-place').find('tr[idx='+map[item].id+'] td.salesmodename').each(function(){

						//If listed we assign the "found" variable to the salemode name
						if($(this).text() === mode){
							//var $currentInput = $finderTR.find('td.quantity input.quantity_input');
							//var updateQty = parseInt($currentInput.val()) + 1;
							//$currentInput.val(updateQty);
							found = mode;
						}
					});

					//If variable "found" is not false. It means the chosen item is already listed
					if( found !== false ){
						bootbox.alert('[ ' + found + ' ][ ' + productname + ' ] is already in the cart. You can change quantity in the cart');
					}else{
						//Else we list the chosen item
						getThem();
					}

					/*** WE ADD THE QUANTITY ON THE FLY ***/

			 	}else{
			 		//We list the chosen item as its never existed in the lists
			 		getThem();
			 	}


			 	function getThem(){
			 		discount = map[item].price - (map[item].price * map[item].discount/100);

				    HTMLBODY  = '<tr idx="'+ map[item].id + '">';
				    //HTMLBODY += '<td class="brandname">'+ map[item].brand.name.capitalize() +'</td>';
				     HTMLBODY += '<td class="quantity center" width="10%"><input type="text" autocomplete="off" value="1" class="quantity_input" /></td>';
				    HTMLBODY += '<td class="productname" width="40%">'+ map[item].brand.name.capitalize() +'/'+productname +'</td>';

				    if( map[item].discount > 0 ){
				    	isHidden = '';
				    	discounted_price = format_money(map[item].discounted_price);
				    	crossPrice = 'oldUnitPrice';
				    }

				    HTMLBODY += '<td class="unitprice" width="15%">';

		@if(User::permitted( 'role.stockmanager'))
				    HTMLBODY += '<a href="#" class="light-green cog_unitprice" data-pk='+map[item].id+'><i class="icon-cog"></i></a> ';
		@endif
				    HTMLBODY +='{{currency()}}<span class="unitprice '+crossPrice+'">'+ format_money(map[item].price, 2) +'</span>k';
				    HTMLBODY +='<span class="'+isHidden+'"><br>';

		@if(User::permitted( 'role.stockmanager'))
				    HTMLBODY +='<a href="#" class="red remove_discount_price_on_fly"><i class="icon-minus-sign"></i></a> ';
		@endif

				    HTMLBODY += '{{currency()}}<span class="discount_price_on_fly">'+discounted_price+'</span>k</span></td>';

				    HTMLBODY += '<td class="discount center" with="5%"><span class="discount" data-title="Enter discount">'+ map[item].discount +'</span>%</td>';

				    HTMLBODY += '<td class="total" with="20%">{{currency()}}<span class="total">'+ format_money(discount, 2) +'</span>k</td>';
				    //mode = ( map[item].categories.type === 'service' ) ? 'Services' : mode;
				    HTMLBODY += '<td class="salesmodename" width="15%">'+ mode +'</td>';
				    HTMLBODY += '<td class="action-buttons"><a href="::;" class="removeProduct red"><i class="icon-remove"></i></a></td>';
				    //Hidden TDs
				    HTMLBODY += '<td class="productcat hide">'+ map[item].categories.name +'</td>';
				    HTMLBODY += '<td class="quantityinstock hide">'+ map[item].quantity +'</td>';
				    HTMLBODY += '<td class="costprice hide">'+ map[item].costprice +'</td>';
				    HTMLBODY += '<td class="cat_type hide">'+ map[item].categories.type +'</td>';
				    HTMLBODY += '</tr>';
					
					//Appending the product to the already avaliable lists 
			     	$('.cart-place tbody').append(HTMLBODY);

			     	//Getting total amount
			     	var subtotalamount = getAmount() + discount;
			     	$('.subtotalamount').text(format_money(subtotalamount, 2));

			     	//We call a function to calculate the Vat and the Totalamount. RETURN JSON OBJECT
			     	//vatAndTotalAmount [ function in g56_function.js file ]
			     	var result = vatAndTotalAmount( subtotalamount );

			     	//Getting the total amount tendered after overall discount
			     	$('.totalamounttendered').text(format_money( getAmountTendered(), 2 ) );

			     	//Lets update the Item in our Cart
					updateCartAndActivateCheckoutButton();

			     	//Save the current presence of the cart
			     	HTMLBODY += '||'+ map[item].id+'||'+ mode;
			     	saveCart("{{URL::route('autoSaveCart')}}", { 
			     			cart_content : HTMLBODY, 
			     			subtotalamount : subtotalamount, 
			     			vat:result.vat, 
			     			totalamount :result.totalamount,  
			     			cart_quantity: $('.total_items_in_cart').text(), 
			     			enteroveralldiscount: $('.enteroveralldiscount input').val(), 
			     			totalamounttendered: unformat_money($('.totalamounttendered').text())
			     		});
			 	}

		//return map[item].name;
	}
</script>