<?php

	use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Product extends Eloquent {

	use SoftDeletingTrait;

	protected $guarded = array('id');

	public static $rules = array();

	public $updateType;

	public static function boot(){
		parent::boot();

		static::updating(function($query){
			if($query->updateType === 'name'){
				return $query->isProductAmongLists();
			}elseif( $query->updateType === 'barcodeid' ){
				return $query->isBarcodeAmongAllProducts();
			}
		});
	}

	//Used when creating or updating barcode
	public function isBarcodeAmongAllProducts(){
		//if( strtolower($this->barcodeid) === 'empty'){	return true; }
		if( strtolower($this->barcodeid) === 'empty'){	$this->barcodeid = ''; return true;}
		$prd = Static::where('barcodeid', $this->barcodeid)->first();
		//dd($prd);
		return ( $prd === null) ? true : false;
	}

	//Used when changing product name
	public function isProductAmongLists(){
		$all = $this->toArray();

		$allsavedProducts = $this->mustBeUniqueToProductCategories($all['productcat_id']);

		return ( ! in_array($all[$this->updateType], $allsavedProducts) ) ? true : false;
	}

	//Used when creating new product
	public static function saveAll(Array $data = null, $productcatID){
		$me = new self;
		if( ($result = $me->isEmpty( $data )) != false ){
			return $result;
		}

		$productcat = Productcategory::find($productcatID);

		$allsavedProducts = $me->mustBeUniqueToProductCategories($productcatID);

		foreach( $data as $p ){
			if( in_array($p, $allsavedProducts) ){
				$data['status'] = 'error';
				$data['message'] = $p . ' already exist';
			}else{
				static::Create(array(
				'name'=>strtolower(trim(extract_char($p, array('text', 'int')))),
				'productcat_id' => $productcat->id,
				'brand_id' => $productcat->brand_id
				));
			}
		}


		if( !isset($data['status']) ){
			$data['status'] = 'success';
			$data['message'] = 'Saved! successfully';
		}

		return Response::json($data);
	}

	private function isEmpty(Array $products = null){
		if(empty($products) || $products == null){
			$data['status'] = 'error';
			$data['message'] = 'Field can not be empty';
			return Response::json($data);
		}
			return false;
	}

	private function mustBeUniqueToProductCategories($productcatID){
		return Productcategory::find($productcatID)->products->lists('name');
	}

	public function brand(){
		return $this->belongsTo('brand', 'brand_id');
	}

	public function categories(){
		return $this->belongsTo('productcategory', 'productcat_id');
	}

	public function getBarcodeidAttribute($value)
	{
		//tt($value);
		return ( $value === null ) ? '' : $value;
	}

	public function getPublishedAttribute($value)
	{
		return (int)$value;
	}

	public function getProductcatIdAttribute($value)
	{
		return (int)$value;
	}

}