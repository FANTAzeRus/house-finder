<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
	public function init()
	{
		$ret = ['success' => false];

		$request = House::get(['bedrooms', 'bathrooms', 'storeys', 'garages']);
		if ($request) {
			$ret['success'] = true;
			$ret['init_data'] = [
				'bedrooms' => $request->pluck('bedrooms')->unique()->sort()->values(),
				'bathrooms' => $request->pluck('bathrooms')->unique()->sort()->values(),
				'storeys' => $request->pluck('storeys')->unique()->sort()->values(),
				'garages' => $request->pluck('garages')->unique()->sort()->values(),
			];
		}

		return $ret;
	}

	public function search()
	{
		$ret = ['success' => false];

		$filters = request()->all();
		if ($filters) {
			$request = House::select('id', 'price', 'name', 'bedrooms', 'bathrooms', 'storeys', 'garages');
			foreach ($filters as $key => $val) {
				if ($key === 'name') {
					$request->where(DB::raw('lower(' . $key . ')'), 'like', '%' . strtolower($val) . '%');
				} elseif ($key === 'price_from' && $filters['price_from'] > 0 && $filters['price_to'] > 0) {
					$request->whereBetween('price', [$filters['price_from'], $filters['price_to']]);
				} elseif ($key !== 'price_from' && $key !== 'price_to') {
					$request->where($key, $val);
				}
			}

			// return $request->toSql();
			$result = $request->get();

			if ($result->count()) {
				$ret['success'] = true;
				$ret['finded'] = $result;
			}
		}

		return $ret;
	}
}
