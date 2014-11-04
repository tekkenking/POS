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
			$brandCat = Brand::find($brand_id)->categories->lists('name');

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
				//$log['categories'] = implode(' | ', $tempStoreCatsForLog['cats']);
				//$log['cat_brand'] = Brand::where('id',$brand_id)->pluck('name');
				//$log['stocktype'] = 'create_category';
				//$this->_saveActivityLog($log);
			}

		}else{
			$data['status'] = 'error';
			$data['message'] = 'Field can not be empty';
		}

		return Response::json($data);
	}

	public function deleteCategories(){
		$ids = explode(',', Input::get('javascriptArrayString'));
		Productcategory::destroy($ids);
		$data['status'] = 'success';
		$data['message'] = 'Deleted successfully';
		return Response::json($data);
	}

	public function updateCategories(){
		$inputs = Input::all();
		$brand = Productcategory::find($inputs['pk']);
		$brand->$inputs['name'] = strtolower(trim(extract_char($inputs['value'], array('text', 'int'))));
		$brand->save();
		$data['status'] = 'success';
		$data['message'] = 'updated successfully';
		return Response::json($data);
	}

	//Toggle Published state
	public function status(){
		$ids = Input::get('id');
		$ids = ( !is_array($ids) ) ? array($ids) : $ids;

		foreach($ids as $id){
			$productcat = Productcategory::find($id);
			$productcat->published = $updatedStatus = ($productcat->published == 0) ? 1 : 0;

			$productsID = $productcat->products->lists('id');
			
			if( !empty($productsID) ){
				//All the products are all mass toggled
				$this->statusChildrenProduct($productsID, $updatedStatus);
			}
			//Then we'll save the one for producttype
			$productcat->save();
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

}