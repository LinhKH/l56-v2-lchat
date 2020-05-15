<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function test()
    {
        $products = ProductCategory::whereNull('category_id')->get()->toArray();
        
        $arrSaleItem = [];

        $arrIdHarueneHist = array_column($products, 'id');

        if(!empty($arrIdHarueneHist)) {
            $apoSaleItem =  ProductCategory::whereIn('category_id', $arrIdHarueneHist)->get()->toArray();

            foreach ($apoSaleItem as $key => $item) {
                $arrSaleItem[$item['category_id']] [] = $item;
            }
        }
        if(!empty($products)) {
            foreach ($products as $index => $level_2) {

                $products[$index]['child_categories'] = isset($arrSaleItem[$level_2['id']]) ? $arrSaleItem[$level_2['id']] : [];

                if(!empty($products[$index]['child_categories'])) {
                    $arrId = array_column($products[$index]['child_categories'], 'id');
                    if(!empty($arrId)) {
                        $apoSale =  ProductCategory::whereIn('category_id', $arrId)->get()->toArray();

                        foreach ($apoSale as $key => $item) {
                            $arrSale[$item['category_id']] [] = $item;
                        }
                    }

                    foreach ($products[$index]['child_categories'] as $key_3 => $level_3) {
                        $products[$index]['child_categories'][$key_3]['child_categories2'] = isset($arrSale[$level_3['id']]) ? $arrSale[$level_3['id']] : [];

                        $productproductcategory0 =  ProductProductCategory::where('product_category_id', $level_3['id']);
                        $products[$index]['child_categories'][$key_3]['child_categories2'][$key_3]['product_count'] = $productproductcategory0->count();

                        if(!empty($products[$index]['child_categories'][$key_3]['child_categories2'] )) {
                            foreach ($products[$index]['child_categories'][$key_3]['child_categories2'] as $key_4 => $level_4) {
                                // if($key_4 == 0) continue;
                                $productproductcategory =  ProductProductCategory::where('product_category_id', $level_4['id']);
                                $products[$index]['child_categories'][$key_3]['child_categories2'][$key_4]['product_count'] = $productproductcategory->count();
                                $products[$index]['child_categories'][$key_3]['child_categories2'][$key_4]['products'] = array_column($productproductcategory->get()->toArray(), 'product_id');

                                // dd($count);
                            }
                        }
                        
                    }
                }
            }
        }

        foreach ($products as $i0 => $parentCategory) {
            foreach($parentCategory['child_categories']  as $i1 => $category) {
                // dd($category['child_categories2']);
                $parentCategory['child_categories'][$i1]['product_count'] = collect($category['child_categories2'])->sum('product_count');
            }
            $products[$i0]['product_count'] = collect($parentCategory['child_categories'])->sum('product_count');
        }
        dd($products);

        return array_values($products);
    }
}
