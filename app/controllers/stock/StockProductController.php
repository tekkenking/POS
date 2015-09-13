<?php

class StockProductController extends \StockBaseController
{
	public function showProduct($brandname, $catname){
		$brandname = urlunslug($brandname);

		$catname = urlunslug($catname);

		$brand 	=Brand::where('name','=',$brandname)->first();

		$productcat = Productcategory::where('brand_id', '=', $brand->id)
									->where('name', '=', $catname)
									->select('id', 'type')
									->first();

		$productsObj = Productcategory::find($productcat->id)->products();

		$mode = Input::get('mode', 'retail');

		$products = $productsObj->select(
			'id',
			'productcat_id',
			'brand_id',
			'barcodeid',
			'name',
			'quantity',
			'almost_finished',
			'costprice',
			'published',
			$mode.'_price as price',
			$mode.'_discount as discount',
			$mode.'_discountedprice as discountedprice',
			$mode.'_totalprice as totalprice'
		);

		if( $products == null ){
			return $this->callErrorPage(404);
		}

		$products = $products->orderBy('id', 'desc')->get();

		$data['products'] = $products->toArray();

		$data['sum_total'] = $this->_sumTotalprice($data['products']);
		$data['sum_cost'] = $this->_sumTotalcost($data['products']);
		$data['profit_margin'] = $data['sum_total'] - $data['sum_cost'];

		$data['addProductLink'] = array('brand_id'=>$brand->id, 'productcat_id'=>$productcat->id);
		$data['mode'] =$mode;

		$data['brand'] = $brand;
		$data['cat'] = $catname;

		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap', 'bootstrap_editable');
		Larasset::start('header')->css('bootstrap_editable');

		if( ! Request::ajax() ){
			$data['modes'] = Mode::listModes();

			$this->layout->title = $brand['name'] .' - ' . $catname;

			$blade = ( $productcat->type === 'service' ) ? 'showservice' : 'tab_showproduct';

			$this->layout->content = View::make('admin.liststock.' . $blade, $data);
		}else{
			return View::make('admin.liststock.showproduct', $data);
		}

	}

	private function _sumTotalcost($arrayData){
		$store = 0;

		foreach( $arrayData as $v ){
			if( $v['totalprice'] > 0 ){
				$store += $v['costprice'] * $v['quantity'];
			}
		}

		return $store;
	}

	private function _sumTotalprice($arrayData){
		$store = 0;
		foreach( $arrayData as $v ){
			$store += $v['totalprice'];
		}

		return $store;
	}

	public function addProducts($brandid, $catid){
		Larasset::start('header')->css('magicsuggest');
		Larasset::start('footer')->js('magicsuggest');

		$productcat = Productcategory::find($catid)->toArray();

		$brand = Brand::find($productcat['brand_id'])->toArray();

		$data['productcat'] = $productcat;
		$data['brand'] = $brand;

		return View::make('admin.create.addproduct', $data);
	}

	public function saveaddProducts(){
		$productcat_id = Input::get('productcat_id');
		$products = json_decode(Input::get('name'));

		if( ! empty($products) ){
			$catName = Productcategory::find($productcat_id)->pluck('name');
			$BrandName = Productcategory::find($productcat_id)->brand->pluck('name');
			//$catName = $BrandID_CatName->name;

			//Log area
			$log['products'] ="[ $BrandName / $catName ] = " . implode(' | ',$products);
			$log['stocktype'] = 'create';
			$this->_saveActivityLog($log);
		}
		
		return Product::saveAll($products, $productcat_id);
	}

	private function _update_quantity($product, $inputs)
	{

		//Keeping records model instantiated
		$stocklog = new Stocklog;

		//Log Current quantity in stock
		$stocklog->current_quantity = $product->quantity;


		/** WE FIRST UPDATE THE QUANTITY  **/
		//Available symbols and names
		$avail_symb = array( '-' => 'subtract', '+' => 'add' );

		//We check if maths symbols is there
		$mathsSymbol = $inputs['value'][0];

		//Log add quantity with its maths symbol or not
		$stocklog->added_quantity = (int)$inputs['value'];

		if( array_key_exists($mathsSymbol, $avail_symb) ){

			$inputs['value'] = substr($inputs['value'], 1);

			$quantitySymbol = $avail_symb[$mathsSymbol];

			if( $avail_symb[$mathsSymbol] == 'add' ){
				$product->quantity = $product->quantity + (int)$inputs['value']; 
			}

			if( $avail_symb[$mathsSymbol] == 'subtract' ){
				$product->quantity = $product->quantity - (int)$inputs['value']; 
			}

		}else{
			$product->quantity = (int)$inputs['value'];
			$quantitySymbol = '';
		}

		//Save the stock log
		$this->_stockLog($stocklog, $product);

		/** WE UPDATE ALL THE ACTIVE MODES TOTAL PRICE **/
		if( !empty( $modes = Mode::listModes()) )
		{
			foreach ($modes as $value) 
			{
				$mode_discount = $value.'_discount';
				$mode_discountedprice = $value.'_discountedprice';
				$mode_price = $value.'_price';
				$mode_totalprice = $value.'_totalprice';

				$priceToUse = ( $product->$mode_discount == 0 )
				? $product->$mode_price
				: $product->$mode_discountedprice;

				$product->$mode_totalprice =  $product->quantity * $priceToUse;

			}
		}

		$mode = $inputs['mode'];
		$mode_totalprice = $mode.'_totalprice';
		$mode_price = $mode.'_price';
		$mode_discountedprice = $mode.'_discountedprice';
		$mode_discount = $mode.'_discount';

		//Save product stock activity
		$brandLog = $product->brand->name;
		$productcatLog = $product->categories->name;
		$log['products'] = "[ $brandLog / $productcatLog ] = <b class='orange'>" . $product->name .'</b>';
		$log['stocktype'] = 'update';
		$log['stocktype_update'] = 'quantity';
		$log['stocktype_update_quantity'] = $quantitySymbol;
		$log['stocktype_old'] = $stocklog->current_quantity;
		$log['stocktype_new'] = (int)$inputs['value'];

		$this->_saveActivityLog($log);

		//Ajax response
		$ajax['value'] = $product->quantity;
		$ajax['total_price'] = format_money($product->$mode_totalprice);
		$ajax['totalcostprice'] = format_money( $product->costprice * $product->quantity );
		$ajax['discounted_price'] = format_money($product->$mode_discountedprice);
		$ajax['ctype'] = 'quantity';
		$ajax['status'] = 'success';
		return $ajax;
	}

	private function _update_price($product, $inputs)
	{
		$inputs['value'] = $new = unformat_money($inputs['value']);

		$mode = $inputs['mode'];

		$mode_price = $mode.'_price';
		$mode_discount = $mode.'_discount';
		$mode_discountedprice = $mode.'_discountedprice';
		$mode_totalprice = $mode.'_totalprice';
		
		$old_amount = $product->$mode_price;

		$product->$mode_price = $inputs['value'];

		if( $product->$mode_discount == 0 )
		{
			$priceToUse = $product->$mode_price;
			$product->$mode_discountedprice = 0.00;
		}else{
			$priceToUse = $product->$mode_price - ( ( $product->$mode_price * $product->$mode_discount) / 100 );
			$product->$mode_discountedprice = $priceToUse;
		}

		$product->$mode_totalprice = ( $inputs['cat_type'] === 'product' ) 
									? $product->quantity * $priceToUse
									: $priceToUse;

		//LOG STOCK
		$stocklog = new Stocklog;
		$this->_stockLog($stocklog, $product);

		//SAVE USER STOCK ACTIVITY
		$brandLog = $product->brand->name;
		$productcatLog = $product->categories->name;
		$log['products'] = "[ $brandLog / $productcatLog ] = <b class='orange'>" . $product->name .'</b>';
		$log['stocktype'] = 'unitprice';
		$log['stocktype_unitprice_mode'] = $mode.' price';
		$log['amount'] = currency() . format_money($new) . 'k';
		$log['old_amount'] = currency() . format_money($old_amount) . 'k';
		$this->_saveActivityLog($log);


		//Ajax response return
		$ajax['value'] = format_money($product->$mode_price);
		$ajax['total_price'] = format_money($product->$mode_totalprice);
		$ajax['totalcostprice'] = format_money($product->quantity * $product->costprice);
		$ajax['discounted_price'] = format_money($product->$mode_discountedprice);
		$ajax['ctype'] = 'price';
		$ajax['status'] = 'success';
		return $ajax;
	}

	private function _update_costprice($product, $inputs)
	{
		$old_amount = $product->costprice;
		$product->costprice = $new = unformat_money($inputs['value']);

		$totalcostprice = $product->costprice * $product->quantity;

		//LOG STOCK
		$stocklog = new Stocklog;
		$this->_stockLog($stocklog, $product);

		//SAVE USER STOCK ACTIVITY
		$brandLog = $product->brand->name;
		$productcatLog = $product->categories->name;
		$log['products'] = "[ $brandLog / $productcatLog ] = <b class='orange'>" . $product->name .'</b>';
		$log['stocktype'] = 'costprice';
		$log['amount'] = currency() . format_money($new) . 'k';
		$log['old_amount'] = currency() . format_money($old_amount) . 'k';
		$this->_saveActivityLog($log);


		//Ajax response return
		$ajax['costprice'] = format_money($product->costprice);
		$ajax['totalcostprice'] = format_money($totalcostprice);
		$ajax['status'] = 'success';
		return $ajax;
	}

	private function _update_discount($product, $inputs)
	{
		$mode = $inputs['mode'];

		$mode_price = $mode.'_price';
		$mode_discount = $mode.'_discount';
		$mode_discountedprice = $mode.'_discountedprice';
		$mode_totalprice = $mode.'_totalprice';

		$old_discount = $product->$mode_discount;
		$old_price = $product->$mode_discountedprice;

		$product->$mode_discount = (float)$inputs['value'];

		$product->$mode_discountedprice = $product->$mode_price - (( $product->$mode_price * $product->$mode_discount) / 100);

		$product->$mode_totalprice =  ( $inputs['cat_type'] === 'product') 
										? $product->quantity * $product->$mode_discountedprice
										: $product->$mode_discountedprice;

		if( $product->$mode_discount == 0 ){
			$product->$mode_discountedprice = 0.00;
		}

		//LOG STOCK
		$stocklog = new Stocklog;
		$this->_stockLog($stocklog, $product);

		//LOG
		$brandLog = $product->brand->name;
		$productcatLog = $product->categories->name;
		$log['products'] = "[ $brandLog / $productcatLog ]";
		$log['product_name'] = $product->name;
		$log['stocktype'] = 'discount';
		$log['stocktype_unitprice_mode'] = $mode.' discount';
		$log['new_discount'] = $product->$mode_discount . '%';
		$log['old_discount'] = $old_discount . '%';
		$log['new_price'] = currency() . format_money($product->$mode_discountedprice) . 'k';
		$log['old_price'] = currency() . format_money($old_price) . 'k';
		$this->_saveActivityLog($log);

		//Ajax response return
		$ajax['value'] = $product->$mode_discount;
		$ajax['total_price'] = format_money($product->$mode_totalprice);
		$ajax['totalcostprice'] = format_money($product->quantity * $product->costprice);
		$ajax['discounted_price'] = format_money($product->$mode_discountedprice);
		$ajax['ctype'] = 'discount';
		$ajax['status'] = 'success';

		return $ajax;
	}

	private function _update_almost_finished($product, $inputs)
	{
		//Save product stock activity
		$brandLog = $product->brand->name;
		$productcatLog = $product->categories->name;
		$log['products'] = "[ $brandLog / $productcatLog ] = <b class='orange'>" . $product->name .'</b>';
		$log['stocktype'] = 'update';
		$log['stocktype_update'] = 'almost_finished';
		$log['stocktype_old'] = $product->almost_finished;
		$log['stocktype_new'] = (int)$inputs['value'];
		$this->_saveActivityLog($log);

		$product->almost_finished = (int)$inputs['value'];
	}

	private function _process_update_name_or_barcode($product, $inputs, $type)
	{
		$old = $product->$type;

		$brand = $product->brand->name;
		$productcat = $product->categories->name;

		$product->$type = $new = strtolower(trim(extract_char($inputs['value'], array('text', 'int'))));
		$ajax['id'] = $product->id;

		$product->updateType = $type;

		if( ! $product->save() ){
			$ajax[$type] = $old;
			$ajax['status'] = 'error';
			$ajax['message'] = $inputs['value'] . ' is already used in your stock..';
		}else{
			$ajax[$type] = $inputs['value'];

		//Save product stock activity
		$new = (strtolower(trim($new)) === 'empty') ? '******' : $new;
		$log['products'] = "[$brand / $productcat]";
		$log['stocktype'] = 'update';
		$log['stocktype_update'] = $type;
		$log['stocktype_update_oldname'] = ($old == '') ? '******' : $old;
		$log['stocktype_update_newname'] = $new;
		$this->_saveActivityLog($log);

		}
		return $ajax;
	}

	private function _update_name($product, $inputs){
		$result = $this->_process_update_name_or_barcode($product, $inputs, 'name');

		if( isset($result['status']) && $result['status'] === 'success'){
			//LOG STOCK
			$stocklog = new Stocklog;
			$this->_stockLog($stocklog, $product);
		}

		return $result;
	}

	private function _update_barcodeid($product, $inputs){
		return $this->_process_update_name_or_barcode($product, $inputs, 'barcodeid');
	}

	public function updateProducts()
	{
		$inputs = Input::all();

		$xe = strtolower($inputs['name']);
		$mode = $inputs['mode'];

		//products, brands, productcategories
		$product = Product::with(
			array(
				//We eager loaded brand. Selecting on the id['required'] and name
				'brand'=> function($query){
					$query->select('id', 'name');
				}, 

				//We eager loaded categories. Selecting on the id['required'] and name
				'categories'=> function($query){
					$query->select('id', 'name');
				}
		))->find($inputs['pk']);

		$method = '_update_'.$xe;

		$ajax = $this->$method($product, $inputs);

		$ajax['id'] = $product->id;

		$product->save();

		return Response::json($ajax);
	}

	private function _stockLog($stocklog, $product){
		$stocklog->product_id 				= Input::get('pk');
		$stocklog->user_name 				= Auth::user()->username;
		$stocklog->brand_name 				= $product->brand->name;
		$stocklog->productcategory_name  	= $product->categories->name;
		$stocklog->product_name  			= $product->name;
		$stocklog->mode_id					= Mode::getModeIDFromName(Input::get('mode'));
		$stocklog->updated_quantity  		= $product->quantity;
		$stocklog->costprice 				= $product->costprice;
		$mode = Input::get('mode');

		$modeprice = $mode.'_price';
		$modediscount = $mode.'_discount';
		$modediscountedprice = $mode.'_discountedprice';
		$modetotalprice = $mode.'_totalprice';

		$stocklog->unitprice  				= $product->$modeprice;
		$stocklog->unitprice_discounted  	= $product->$modediscount;
		$stocklog->discount  				= $product->$modediscountedprice;
		$stocklog->total_price  			= $product->$modetotalprice;
		$stocklog->save();
	}

	public function deleteProducts(){
		$ids = explode(',', Input::get('javascriptArrayString'));

		//Log
		$products = [];
		foreach( $ids as $id ){
			$products[] = Product::where('id', $id)->pluck('name');
		}

		$dbID = $ids[0];
		$productModel = Product::find($dbID);
		$brandName = $productModel->brand()->pluck('name');
		$catName = $productModel->categories()->pluck('name');

		if( !empty($products) ){
			$log['products'] ="[ $brandName / $catName ]";
			$log['stock_items'] = implode(' | ', $products);
			$log['stocktype'] = 'delete';
			$this->_saveActivityLog($log);
		}

		Product::destroy($ids);
		$data['status'] = 'success';
		$data['message'] = 'Deleted successfully';
		return Response::json($data);
	}

	//Toggle Published state
	public function status(){
		$ids = Input::get('id');
		$ids = ( !is_array($ids) ) ? array($ids) : $ids;
			
			$dx = [];
			foreach($ids as $id){
				$product = Product::find($id);
				$dx[] = $product->name;
				$product->published = $updatedStatus = ($product->published == 0 ) ? 1 : 0;
				$product->save();
			}

		//Log.. This would let you know the product status as stockupdate
		$dbID = $ids[0];
		$brandName = Product::find($dbID)->brand()->pluck('name');
		$catName = Product::find($dbID)->categories()->pluck('name');

		if( !empty($dx) ){
			$log['products'] = "[ $brandName / $catName ] = <b class='orange'>" . implode(' | ', $dx) . '</b>';
			$log['products_status'] = $updatedStatus;
			$log['stocktype'] = 'status';
			$this->_saveActivityLog($log);
		}

		$data['status'] = 'success';
		$data['message'] = 'status updated';
		return Response::json($data);
	}

	private function _saveActivityLog($log){
		doUserActivity::saveActivity('stock', $log);
	}

}