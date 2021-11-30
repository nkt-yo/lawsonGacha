<?php

namespace App\Libs;

class Gacha {
    public array $genres;
    public array $matchGenresList;
    public int $unitPriceMin;
    public int $unitPriceMax;
    public int $totalAmountMin;
    public int $totalAmountMax;
    public int $totalCalorieMin;
    public int $totalCalorieMax;

    function __construct() {
        $this->matchGenresList = [];
        $this->unitPriceMin = 0;
        $this->unitPriceMax = 0;
        $this->totalAmountMin = 0;
        $this->totalAmountMax = 0;
        $this->unitCalorieMin = 0;
        $this->unitCalorieMax = 0;
        $this->totalCalorieMin = 0;
        $this->totalCalorieMax = 0;        
        $this->makeGenresId();
    }

    public function choiceProducts($parsedParam,$products){

        $choiseProducts = [];
        $choiseProducts['totalAmount'] = 0;
        $choiseProducts['totalCalorie'] = 0;
        
        foreach ($products as $key => $value) {
            
            // 合計価格の上限値確認
            if($parsedParam['totalAmount']['max'] >= $choiseProducts['totalAmount'] + $value['product_price']){
                // 合計カロリーの上限値確認
                if($parsedParam['totalCalorie']['max'] >= $choiseProducts['totalCalorie'] + $value['calorie']){

                    // 商品情報を格納する
                    $choiseProducts['totalAmount'] = $choiseProducts['totalAmount'] + $value['product_price'];
                    $choiseProducts['totalCalorie'] = $choiseProducts['totalCalorie'] + $value['calorie'];
                    $choiseProducts['choiseProducts'][$key] = $value;
                    $choiseProducts['choiseProducts'][$key]['genreName']  = $this->genres[$value['genre_id'] - 1];
                }
            }
            
        }

        if(!$choiseProducts['totalAmount']){
            // ひとつもマッチしなかった場合
            return null;
        }
        return $choiseProducts;
    }

    public function parsePostParameter(){

        if(isset($_POST['selectItems']) && !($_POST['selectItems'] === "")){
            $this->matchGenres($_POST['selectItems']);
        }else{
            $this->matchGenres(null,true);
        }

        if(!(count($this->matchGenresList))){
            // ひとつもジャンルがマッチしていない場合は入力値に不正な文字列があるのでデフォルト表示に切り替える
            $this->matchGenres(null,true);
            $_POST['selectItems'] = "";
        }

        // 数値以外の文字列
        $re = '/[^0-9]/';

        // 商品単価の下限値設定

        if(isset($_POST['unit_price_min']) && !($_POST['unit_price_min'] === "") && !(preg_match($re, $_POST['unit_price_min']))){
            $this->unitPriceMin = $_POST['unit_price_min'];
        }else{
            $this->unitPriceMin = 0;
        }

        // 商品単価の価上限設定
        if(isset($_POST['unit_price_max']) && !($_POST['unit_price_max'] === "")&& !(preg_match($re, $_POST['unit_price_max']))){
            $this->unitPriceMax = $_POST['unit_price_max'];
        }else{
            $this->unitPriceMax = 99999;
        }

        // 合計値の下限値設定
        if(isset($_POST['total_amount_min']) && !($_POST['total_amount_min'] === "")&& !(preg_match($re, $_POST['total_amount_min']))){
            $this->totalAmountMin = $_POST['total_amount_min'];
        }else{
            $this->totalAmountMin = 0;
        }

        // 合計値の上限値設定
        if(isset($_POST['total_amount_max']) && !($_POST['total_amount_max'] === "")&& !(preg_match($re, $_POST['total_amount_max']))){
            $this->totalAmountMax = $_POST['total_amount_max'];
        }else{
            $this->totalAmountMax = 99999;
        }

        // 1つあたりの商品カロリーの下限値設定
        if(isset($_POST['unit_calorie_min']) && !($_POST['unit_calorie_min'] === "")&& !(preg_match($re, $_POST['unit_calorie_min']))){
            $this->unitCalorieMin = $_POST['unit_calorie_min'];
        }else{
            $this->unitCalorieMin = 0;
        }

        // 1つあたりの商品カロリーの上限値設定
        if(isset($_POST['unit_calorie_max']) && !($_POST['unit_calorie_max'] ==="")&& !(preg_match($re, $_POST['unit_calorie_max']))){
            $this->unitCalorieMax = $_POST['unit_calorie_max'];
        }else{
            $this->unitCalorieMax = 99999;
        }

        // カロリー合計値の下限値設定
        if(isset($_POST['total_calorie_min']) && !($_POST['total_calorie_min'] === "")&& (!(preg_match($re, $_POST['total_calorie_min'])))){
            $this->totalCalorieMin = $_POST['total_calorie_min'];
        }else{
            $this->totalCalorieMin = 0;
        }

        // カロリー合計値の上限値設定
        if(isset($_POST['total_calorie_max']) && !($_POST['total_calorie_max'] ==="") && (!(preg_match($re, $_POST['total_calorie_max'])))){
            $this->totalCalorieMax = $_POST['total_calorie_max'];
        }else{
            $this->totalCalorieMax = 99999;
        }

        return [
            'matchGenresList' => $this->matchGenresList
            ,

            'unitPrice' => [
                'min' => $this->unitPriceMin,
                'max' =>  $this->unitPriceMax,
            ],

            'totalAmount' => [
                'min' => $this->totalAmountMin,
                'max' =>  $this->totalAmountMax,
            ],

            'unitCalorie' => [
                'min' => $this->unitCalorieMin,
                'max' =>  $this->unitCalorieMax,
            ],
            'totalCalorie' => [
                'min' => $this->totalCalorieMin,
                'max' =>  $this->totalCalorieMax,
            ],
        ];
        
    }


    public function matchGenres($selectItems,$isAllselect = false){

        if($isAllselect){
            foreach ($this->genres as $key => $value) {
                $this->matchGenresList[] = $key+1;
            }
            return;
            
        }
        

        $list = explode(',', $selectItems);
        $selectItemsList = [];
        foreach ($list as $value){
            $value = mb_ereg_replace('^[\s　]+|[\s　]+$', '', $value);
            if ($value !== ""){
                $selectItemsList[] = $value;
            }
        }


        
        // ジャンル名とジャンルIDを紐づけた配列を作成する
        foreach ($selectItemsList as $selectItem) {

            foreach ($this->genres as $key => $value) {
                if($selectItem ==  $value){
                    // マッチしたジャンルの商品IDを入れる
                    $this->matchGenresList[] = $key+1;
                }
            }

        }

             
    }

    public function makeGenresId(){
        $engGenres = ['rice', 'sushi', 'bento', 'chilledbento', 'sandwich', 'bakery','pasta', 'noodle', 
            'icecream', 'salad', 'gratin', 'soup','konamono', 'fry', 'machikadochubo', 'coffee',
            'dessert', 'chilled', 'gateau', 'kenkosnack','osozai', 'cutvegetable','meat', 'liquid',
            'paste', 'pickles', 'frozen','dairy','cupnoodle', 'instant', 'snack', 'dry'];

        $jpGenres = [
            "おにぎり", "お寿司", "お弁当",  "チルド弁当", "サンドイッチ・ロールパン", "ベーカリー", "パスタ", "そば・うどん・中華麺",
            "アイス・フローズン", "サラダ", "グラタン・ドリア", "スープ", "お好み焼・たこ焼き・他", "揚げ物", "まちかど厨房",
            "コーヒー", "デザート", "チルド飲料", "焼菓子", "ナチュラルローソン菓子", "お惣菜", "カット野菜・フルーツ", "加工肉",
            "水物", "練物", "漬物", "冷凍食品", "乳製品", "カップ麺", "即席食品", "お菓子", "缶詰・乾物・乾麺"
        ];

        $this->genres = [];

        foreach ($engGenres as $key => $value) {
            $this->genres[] = $jpGenres[$key];
        }
    }
}