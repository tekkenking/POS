<?php

class StockBrandController extends \StockBaseController{
	public function index(){
		//Larasset::start('header')->css('magicsuggest');
		//Larasset::start('footer')->js('magicsuggest');

		return View::make('admin.create.brand');
	}

	public function showBrands()
	{
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap', 'bootstrap_editable');
		Larasset::start('header')->css('bootstrap_editable');

		$ids = Brand::all()->lists('id');
		if( !empty($ids) ){
			$this->_monitorChildrenStatus($ids);
		}

		$data['brands'] = Brand::all();
		$this->layout->title = "Active Brands of products";
		$this->layout->content = View::make('admin.liststock.stocks', $data);
	}

	public function addbrand(){
		$brand = Input::get('brandname');
		$brandlogo = Input::file('brandlogo');

		$valid = [
				'brandname' => $brand,
				'brandlogo' => $brandlogo
			];

		Xvalidate::filter('createbrand');
		$validation = Xvalidate::these($valid);

		if($validation->passes()){

			$data['name'] = strtolower(trim(extract_char($brand, ['text', 'int'])));

			if( $brandlogo !== NULL ){
				$data['brandlogo'] = $this->_prepareImage($brandlogo);
			}

			//Log area
			$log['products'] = '[BRAND] ' . $data['name'];
			$log['stocktype'] = 'create';
			$this->_saveActivityLog($log);
			
			//Saving the brand into database
			$brandObj = Brand::Create($data);

			$result['status'] = 'success';
			$result['message'] = 'Created successfully!';
			$result['url'] = URL::route('adminstock');
			return Response::json($result);
		}else{
			return Response::json($validation->messages());
		}
	}

	private function _prepareImage($imgObj){
		//Putting the file into it's uploaded dir
		$logonew_name = Makehash::random('number', 20) .'.' . $imgObj->getClientOriginalExtension();
		$destinationPath = Config::get('software.dir_upload_logo');
		$imgObj->move($destinationPath, $logonew_name);
		return $logonew_name;
	}

	public function logo(){
		$funcall = Input::get('operation');
		$funcall = '_' . $funcall;
		$data = $this->$funcall();

		if( Request::ajax() ){
			return Response::json($data);
		}else{
			return $data;
		}
	}

	//This would get logo on request to change logo
	private function _getlogo(){
		Larasset::start('header')->css('bootstrap_editable');
		$brandID = Input::get('id');
		$brand = Brand::where('id', $brandID)->first();
		$brand->image = imging($brand->brandlogo);
		$brand->islogo = ( $brand->brandlogo !== '' ) ? 'yes' : 'no';
		return $brand;
	}

	private function _deletelogo(){
		$id = Input::get('id');

		//Lets get the info from db as obj;
		$brand = Brand::find($id);

		//Lets check if the image exists
		$file = Config::get('software.dir_upload_logo');
		if( $brand->brandlogo !== '' AND file_exists($file . $brand->brandlogo) ){
			//We delete the image from uploaded dir
			unlink($file . $brand->brandlogo);
		}

		//We delete the image from db by setting empty string
		$brand->brandlogo = '';

		//We update the changes
		$brand->save();

		$data['status'] = 'success';
		$data['message'] = 'Logo deleted successfully';
		$data['defaultlogo'] = imging();
		$data['islogo'] = 'no';
		return $data;
	}

	//WORK ON UPDATING A BRAND LOGO LATER
	private function _updatelogo(){
		//tt(Input::all());
		$brandID = Input::get('id');
		$brandlogo = Input::file('brandlogo');

		$newName = $this->_prepareImage($brandlogo);
		$brand = Brand::find($brandID);

		$brand->brandlogo = $newName;

			if(! $brand->save() ){
				$data['status'] = 'error';
				$data['message'] = 'An error occured';
			}else{
				$data['status'] = 'success';
				$data['message'] = 'Logo changed successfully';
			}
		return Redirect::route('adminstock');
	}

	public function updateBrand(){
		$inputs = Input::all();
		$brand = Brand::find($inputs['pk']);
		$formerName = $brand->name;
		$brand->$inputs['name'] = $newName = strtolower(trim(extract_char($inputs['value'], array('text', 'int'))));
		$brand->save();

		//Save product stock activity
		$log['products'] = 'Brand name change from ';
		$log['stocktype'] = 'update';
		$log['stocktype_update'] = 'name';
		$log['stocktype_update_oldname'] = $formerName;
		$log['stocktype_update_newname'] = $newName;
		$this->_saveActivityLog($log);

		$data['status'] = 'success';
		$data['message'] = 'updated successfully';
		return Response::json($data);
	}

	public function deleteBrands(){
		$ids = explode(',', Input::get('javascriptArrayString'));
		//$ids = (!is_array($ids)) ? array($ids) : $ids;
			foreach( $ids as $id ){
				//Lets fetch the Brand Image name
				$logo = Brand::where('id', $id)->pluck('brandlogo');

				//Lets check if the file exists
				$file = Config::get('software.dir_upload_logo');
				if( $logo !== '' AND file_exists($file . $logo) ){
					unlink($file . $logo);
				}
				//tt($logo);
			}
		Brand::destroy($ids);
		$data['status'] = 'success';
		$data['message'] = 'Deleted successfully';
		return Response::json($data);
	}

	//Toggle Published state
	public function status(){
		$ids = Input::get('id');
		$ids = ( !is_array($ids) ) ? array($ids) : $ids;

		$brandArr = [];
		foreach($ids as $id){
			$brand = Brand::find($id);
			$brand->published = $updatedStatus = ($brand->published == 0) ? 1 : 0;
			$brandArr[] = $brand->name;
			$productcatsID = $brand->categories->lists('id');
			if( !empty($productcatsID) ){
				//All the products are all mass toggled
				$this->statusChildrenCategory($productcatsID, $updatedStatus);
			}
			//Then we'll save the one for producttype
			$brand->save();
		}

		if( !empty($brandArr) ){
			$log['products'] = "[BRANDS] " . implode(' | ', $brandArr);
			$log['products_status'] = $updatedStatus;
			$log['stocktype'] = 'status';
			$this->_saveActivityLog($log);
		}

		$data['status'] = 'success';
		$data['message'] = 'status updated';
		return Response::json($data);
	}

	private function statusChildrenCategory($productcatsID, $status){
		foreach($productcatsID as $productcat_id){
			$cat = Productcategory::find($productcat_id);
			$cat->published = $status;

			$productsID = $cat->products->lists('id');
			if( !empty($productsID) ){
				$this->statusChildrenProduct($productsID, $status);
			}

			$cat->save();
		}
	}

	//All Category children( Product type ) would be toggled
	/*private function statusChildrenProductType($producttypesID, $status){
		foreach($producttypesID as $producttype_id){
			$producttypes = Producttype::find($producttype_id);
			$producttypes->published = $status;

			$productIDs = $producttypes->products->lists('id');
			if( !empty($productIDs) ){
				$this->statusChildrenProduct($productIDs, $status);
			}

			$producttypes->save();
		}
	}*/

	//All product type children( Products ) would be toggled
	private function statusChildrenProduct($productsIDs, $status){
		foreach($productsIDs as $product_id){
			$product = Product::find($product_id);
			$product->published = $status;
			$product->save();
		}
	}

	private function _monitorChildrenStatus(Array $ids){
		foreach( $ids as $id ){
			$brand = Brand::find($id);
			$status = $brand->categories->lists('published');

			$brand->published = (in_array(1, $status)) ? 1 : 0;
			$brand->save();
		}
	}

	public function getStoreWorth()
	{
		$products = Product::select('quantity', 'costprice', 'retail_price', 'retail_discountedprice', 'wholesale_price', 'wholesale_discountedprice')
			->where('quantity', '>', 0)
			->get();

		if( $products !== null ){
			
			$costprice = 0;
			$retail_price = 0;
			$wholesale_price = 0;

				foreach( $products as $p ){

					//Calculate Retail details
					$costprice += $p->quantity * $p->costprice;

					if( $p->retail_discountedprice > 0 ){
						$retail_price += $p->quantity * $p->retail_discountedprice;
					}else{
						$retail_price += $p->quantity * $p->retail_price;
					}


					//Calculate wholesales details
					if( $p->wholesale_discountedprice > 0 ){
						$wholesale_price += $p->quantity * $p->wholesale_discountedprice;
					}else{
						$wholesale_price += $p->quantity * $p->wholesale_price;
					}
					
				}
		}

		$data['retail_profit'] = $retail_profit = $retail_price - $costprice;
		$data['wholesale_profit'] = $wholesale_profit = $wholesale_price - $costprice;
		$data['costprice'] = $costprice;
		$data['retail_price'] = $retail_price;
		$data['wholesale_price'] = $wholesale_price;

		return View::make('admin.liststock.store_worth', $data);
		//tt(format_money($retail_price), true);
		//tt($products->toArray());
	}

	private function _saveActivityLog($log){
		doUserActivity::saveActivity('stock', $log);
	}

}