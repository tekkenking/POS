<?php

	use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Productcategory extends Eloquent {

	use SoftDeletingTrait;

	protected $guarded = array('id');

	 protected $fillable = array('brand_id', 'name', 'created_at', 'updated_at');

	public static $rules = array();

	public $validatorStatus;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'productcategories';

	public function brand(){
		return $this->belongsTo('brand', 'brand_id');
	}

	//public function producttypes(){
	//	return $this->hasMany('producttype', 'productcat_id');
	//}

	public function products(){
		return $this->hasMany('product', 'productcat_id');
	}

	public static function boot(){
		parent::boot();

		//This event will delete all related model in product model
		static::deleted(function($productcat){
			$r = $productcat->products()->lists('id');
			if( !empty($r) ){ Product::destroy($r); }
		});

		//Validation on create
		static::creating(function($productcat){
			return $productcat->isValid();
		});
	}

	public function isValid(){
		Xvalidate::filter('productcategories');
		$validation = Xvalidate::these($this->toArray());

		if ( ! $validation->passes() ){
		    	$this->validatorStatus = $validation->messages();
		    	return false;
		    }

		return true;
	}

	public static function getOnlyAllList($col){
		return  self::all()->lists($col);
	}

	public static function uniqueCatString($col){
		if( ($r = self::all()->lists($col)) != null ){
			return implode(',',array_keys(array_unique((array_flip($r)))));
		}else{
			return null;
		}
	}

	//public static function countProductTypes($cat_id){
	//	return self::checkCounts(self::find($cat_id)->producttypes);
	//}

	public static function countProducts($cat_id){
		return self::checkCounts(self::find($cat_id)->products);
	}

	private static function checkCounts($result){
		if ( $result != null AND !empty($result) ){
			return $result->count();
		}else{
			return 0;
		}
	}

	public static function categoryWorth($cat_id){
		$prices = self::find($cat_id)->products->lists('total_price');
			if($prices != null AND !empty($prices)){
				foreach($prices as $price){
					@$p += $price; 
				}

				return $p;
			}
		return 0;
	}

	public function getPublishedAttribute($value)
	{
		return (int)$value;
	}	

	public function getBrandIdAttribute($value)
	{
		return (int)$value;
	}

}