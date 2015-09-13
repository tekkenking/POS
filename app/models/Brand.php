<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Brand extends Eloquent {

	protected $guarded = array('id');

	public static $rules = array();

	use SoftDeletingTrait;
	//protected $softDelete = true;

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'brands';

	public static function boot(){
		parent::boot();

		//This event will delete all related model in category model
		static::deleted(function($brand){
			$r = $brand->categories()->lists('id');
			if( !empty($r) ){ Productcategory::destroy($r); }
		});

		static::updated(function($brand){
			//$r = $brand->categories()->lists('id');
		});
	}

	public function categories(){
		return $this->hasMany('productcategory', 'brand_id');
	}

	//public function producttypes(){
	//	return $this->hasMany('producttype', 'brand_id');
	//} 

	public function products(){
		return $this->hasMany('product', 'brand_id');
	}

	public static function countCategories($brand_id){
		return Brand::find($brand_id)->categories->count();
	}

	public static function countProductTypes($brand_id){
		return Brand::find($brand_id)->producttypes->count();
	}

	public static function countProducts($brand_id){
		return Brand::find($brand_id)->products->count();
	}

	public static function totalProductWorth($brand_id){
		$prices = Brand::find($brand_id)->products->lists('total_price');
			if($prices != null AND !empty($prices)){
				foreach($prices as $price){
					@$p += $price; 
				}

				return $p;
			}
		return 0;
	}

	public function getBrandlogoAttribute($value)
	{
		if( $value === null ){
			return ( $value === null ) ? '' : $value;
		}
	}

	public function getPublishedAttribute($value)
	{
		return (int)$value;
	}

}