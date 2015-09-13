<?php

class StockProductCategoryController extends \StockBaseController{
	
	public function showCategory($brandname)
	{
		$brandname = urlunslug($brandname);

		$brand = Brand::where('name','=',$brandname)->first();

		//This will trigger 404 Error page if The brand requested does not exit
		if( $brand == null ){ return $this->callErrorPage(404); }

		$ids = Productcategory::where('brand_id','=',$brand->id)->lists('id');
		
		$this->_monitorChildrenStatus($ids);

		$category = Brand::find($brand->id)->categories->toArray();

		$data['category'] = $category;
		$data['brand'] = $brand;

		$this->layout->title = $brand->name . " - Categories";
		$this->layout->content = View::make('admin.liststock.showcategory', $data);

		Larasset::start('header')->css('bootstrap_editable');
		Larasset::start('footer')->js('bootstrap_editable');
	}

	public function addCategory($id, $type){
		Larasset::start('header')->css('magicsuggest');
		Larasset::start('footer')->js('magicsuggest');

		$data['brand_id'] = $id;
		return View::make('admin.create.'.$type, $data);
	}

	public function saveAddCategory(){
		$catsArray = json_decode(Input::get('category'));
		$brand_id = Input::get('brand_id');
		$cat_type = Input::get('cat_type');

		if(!empty($catsArray)){
			
			//We fetch the name of categories under that brand
			$brandModel =  Brand::find($brand_id);
			$brandCat = $brandModel->categories->lists('name');
			$brandName = $brandModel->name;

			$tempStoreCatsForLog['cats'] = array();

			foreach($catsArray as $category){
				//We check if the new category existed in the list of categories under the brand
				if( in_array($category, $brandCat) ){
					$data['status'] = 'error';
					$data['message'] = $category . ' already exist';
				}else{

					$cat = new Productcategory;
					$cat->name = strtolower(trim(extract_char($category, array('text', 'int'))));
					$cat->brand_id = $brand_id;
					$cat->type = $cat_type;

					$tempStoreCatsForLog['cats'][] = $cat->name;

					if( ! $cat->save() ){
						$data = $cat->validatorStatus;
					}elseif(empty($data)){
						$data['status'] = 'success';
						$data['message'] = 'Created successfully';
					}

				}
			}

			if( ! empty($tempStoreCatsForLog['cats']) ){
				//Log area
				$log['products'] = "[ $brandName ] = " . implode(' | ', $tempStoreCatsForLog['cats']);
				$log['stocktype'] = 'create';
				$this->_saveActivityLog($log);
			}

		}else{
			$data['status'] = 'error';
			$data['message'] = 'Field can not be empty';
		}

		return Response::json($data);
	}

	public function deleteCategories(){
		$ids = explode(',', Input::get('javascriptArrayString'));

		//Log
		$cats = [];
		foreach( $ids as $id ){
			$cats[] = Productcategory::where('id', $id)->pluck('name');
		}

		$brandName = Productcategory::find($ids[0])->brand()->pluck('name');

		//Then temp delete them
		Productcategory::destroy($ids);

		if( !empty($cats) ){
			$log['products'] ="Category Belonging To <b class='orange'>". $brandName ."</b> Brand = <b>" .implode(' | ', $cats) . '</b>';
			$log['stocktype'] = 'delete';
			$this->_saveActivityLog($log);
		}

		$data['status'] = 'success';
		$data['message'] = 'Deleted successfully';
		return Response::json($data);
	}

	public function updateCategories(){
		$inputs = Input::all();
		$brand = Productcategory::find($inputs['pk']);
		$parent = $brand->brand->name;
		$formerName = $brand->$inputs['name'];
		$brand->$inputs['name'] = $newName = strtolower(trim(extract_char($inputs['value'], array('text', 'int'))));
		$brand->save();

		//Save product category activity
		$log['products'] = "[$parent]";
		$log['stocktype'] = 'update';
		$log['stocktype_update'] = 'name';
		$log['stocktype_update_oldname'] = $formerName;
		$log['stocktype_update_newname'] = $newName;
		$this->_saveActivityLog($log);

		$data['status'] = 'success';
		$data['message'] = 'updated successfully';
		return Response::json($data);
	}

	//Toggle Published state
	public function status(){
		$ids = Input::get('id');
		$ids = ( !is_array($ids) ) ? array($ids) : $ids;

		$dx = [];
		foreach($ids as $id){
			$productcat = Productcategory::find($id);
			$brandName = $productcat->brand->pluck('name');
			$productcat->published = $updatedStatus = ($productcat->published == 0) ? 1 : 0;

			$productsID = $productcat->products->lists('id');

			$dx[] = $productcat->name;
			
			if( !empty($productsID) ){
				//All the products are all mass toggled
				$this->statusChildrenProduct($productsID, $updatedStatus);
			}
			//Then we'll save the one for producttype
			$productcat->save();
		}

		if( !empty($dx) ){
			$log['products'] = 'Category belonging to <b class="orange">' . $brandName . '</b> brand = <b>' . implode(' | ', $dx) .'</b>';
			$log['products_status'] = (int)$updatedStatus;
			$log['stocktype'] = 'status';
			$this->_saveActivityLog($log);
		}

		$data['status'] = 'success';
		$data['message'] = 'status updated';
		return Response::json($data);
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
			$cat = Productcategory::find($id);
			$status = $cat->products->lists('published');

			$cat->published = (in_array(1, $status)) ? 1 : 0;
			$cat->save();
		}
	}

	private function _saveActivityLog($log){
		doUserActivity::saveActivity('stock', $log);
	}

}