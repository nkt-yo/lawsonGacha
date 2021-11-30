<?php

namespace App\Http\Controllers;

use App\Products;
use App\Libs\Gacha;

class GachaController extends Controller
{
    public function index()
    {
        return view('index',[
            // デフォルトは1500円
            'inputedParam' => ['total_amount_max' => 1500]
        ]);

    }

    /**
     * 回すボタン押下時
     */
    public function post()
    {
 
        $gacha = new Gacha();
        $parsedParam  = $gacha->parsePostParameter();


        // 指定した条件でガチャを回す
        $stmt = Products::whereIn('genre_id',$parsedParam['matchGenresList'])
            ->whereBetween(
                'product_price',[$parsedParam['unitPrice']['min'],$parsedParam['unitPrice']['max']],
            )
            ->whereBetween(
                'product_price',[$parsedParam['unitCalorie']['min'],$parsedParam['unitCalorie']['max']],
            )
            ->inRandomOrder()
            ->take(10)
            ->get();
                
        $choiseProducts = $gacha->choiceProducts($parsedParam,$stmt);
        
        if(!$choiseProducts){

            // 該当する商品が無かった場合
            return view('index',[
                'inputedParam' => $_POST,
                'totalAmount' => 0,
                'resultMsg' => "該当する商品はありませんでした",
            ]);
        }
        
        return view('index',[
            'inputedParam' => $_POST,
            'totalAmount' => $choiseProducts['totalAmount'],
            'resultMsg' => "合計:" . $choiseProducts['totalAmount'] . "円\t".$choiseProducts['totalCalorie'] . "kcal",
            'choiseProducts' => $choiseProducts['choiseProducts'],
        ]);


    }
}
