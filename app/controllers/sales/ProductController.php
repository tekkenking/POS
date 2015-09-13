<?php

class ProductController extends \SalesBaseController
{
	public function searchproduct(){
		if( ! Auth::check() ){
			return Response::json(array('session'=>'expired'));
		}
			$q = Input::get('q');
			$mode = strtolower(str_replace(' ', '', Input::get('mode')));
			$field = Input::get('qf');
			$ql = (int)Input::get('l');

			//tt($mode);

			if( $field === 'name' ){
				$products = $this->_name($q, $mode, $ql);
			}else{
				$products = $this->_barcodeid($q, $mode);
			}

			return Response::json($products);
	}

	private function _barcodeid($q, $mode){
			$products = Product::with(
					array(

					'brand'=> function($query){
						$query->select('id', 'name');
					}, 

					'categories'=> function($query){
						$query->select('id', 'name');
					}

					))->where("barcodeid", '=', $q)
			->where('quantity', '>', 0)
			->where($mode.'_price', '>', 0)
			->where('published', '=', 1)
			->select(
					'id', 
					'name',
					'barcodeid',
					'costprice',
					'productcat_id', 
					'brand_id', 
					'quantity', 
					$mode.'_price as price',
					$mode.'_discount as discount',
					$mode.'_discountedprice as discounted_price',
					'published'
					)
			->get();

			//tt($products);
		return $products;
	}

	private function _name($q, $mode, $ql){
		//tt($mode);
			$products = Product::with(
					array(

					'brand'=> function($query){
						$query->select('id', 'name');
					}, 

					'categories'=> function($query){
						$query->select('id', 'name', 'type');
					}

					))->whereRaw("name LIKE '%".$q ."%'")
			//->where('quantity', '>', 0)
			->where($mode.'_price', '>', 0)
			->where('published', '=', 1)
			->select(
					'id', 
					'name',
					'barcodeid',
					'costprice',
					'productcat_id', 
					'brand_id', 
					'quantity', 
					$mode.'_price as price',
					$mode.'_discount as discount',
					$mode.'_discountedprice as discounted_price',
					'published'
					)
			->take($ql)
			->orderBy('quantity', 'DESC')
			->get();
			
			return $products;
	}
}