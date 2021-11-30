<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.4/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kosugi&family=RocknRoll+One&display=swap" rel="stylesheet">


    <title>ローソンガチャ</title>
</head>

<body>

    <div class="pt-5 pb-2 container-md  text-center box-height">
        <div class="mx-auto ">

            <a href="/" class="bd-title">
                <h1>ローソンガチャ</h1>
            </a>

            <hr class="bd-line my-4">
        </div>
        <div class="container-md">
            <div class="btns mx-auto">
                <form action='/' method='POST'>
                    @csrf

                    <button type="submit" class="btn btn-outline-primary px-5">回す</button>
                    <div class="bg-black">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <p class="gacha-setting mx-auto">ガチャ設定</p>
                                    </button>
                                </h2>

                                <!-- アコーディオンメニューのコンテンツ -->
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                                    <div class="accordion-body mx-auto">

                                        <div class="genre-menu">

                                            <div class="form-group">
                                                <div class="ml-2 mr-2">
                                                    <div class="genres">
                                                        <div id="select-genres" class="margin-20">
                                                            <p>ジャンル</p>
                                                            <!-- パラメータ設定 -->
                                                            <multiselect v-model="value" :options="options" :multiple="true" :hide-selected="true" :close-on-select="false" :searchable="false" placeholder="未選択" select-label=""></multiselect>
                                                            <input type="hidden" name="selectItems" :value="value">
                                                            <input type="hidden" ref="selectedItems" value="{{ @$inputedParam['selectItems']}}">
                                                            <div class="btns-select">
                                                                <button type="button" class="btn btn-outline-primary px-5 select mr-1" v-on:click="selectAll">全て選択</button>

                                                                <button type="button" class="btn btn-outline-primary px-5 select ml-1" v-on:click="unSelectAll">全て解除</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <p>商品単価</p>

                                        <div class="margin-20 unit-price-menu form-inline">

                                            <!-- 商品単価 -->
                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="unit_price_min" value="{{ @$inputedParam['unit_price_min'] }}" class="form-control num-only" placeholder="0円">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>

                                                </div>
                                            </div>

                                            <span>
                                                ～
                                            </span>

                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="unit_price_max" value="{{ @$inputedParam['unit_price_max'] }}" class="form-control num-only" placeholder="上限なし">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>

                                        <p>合計金額</p>

                                        <div class="margin-20 total-amount-menu form-inline">

                                            <!-- 合計金額 -->
                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="total_amount_min" disabled="disabled" class="form-control num-only" placeholder="0円">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <span>
                                                ～
                                            </span>

                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="total_amount_max" value="{{ @$inputedParam['total_amount_max'] }}" class="form-control num-only" placeholder="上限なし">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>


                                        <p>1商品あたりのカロリー</p>

                                        <div class="margin-20 calorie-menu form-inline">

                                            <!-- カロリー -->
                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="unit_calorie_min" value="{{ @$inputedParam['unit_calorie_min'] }}" class="form-control num-only" placeholder="0kcal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <span>
                                                ～
                                            </span>

                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="unit_calorie_max" value="{{ @$inputedParam['unit_calorie_max'] }}" class="form-control num-only" placeholder="上限なし">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>

                                        <p>合計カロリー</p>

                                        <div class="margin-20 calorie-menu form-inline">

                                            <!-- カロリー -->
                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="total_calorie_min" disabled="disabled" value="{{ @$inputedParam['total_calorie_min'] }}" class="form-control num-only" placeholder="0kcal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <span>
                                                ～
                                            </span>

                                            <div class="form-group">
                                                <div class="ml-2 mr-2 input_box">
                                                    <input type="text" inputmode="numeric" name="total_calorie_max" value="{{ @$inputedParam['total_calorie_max'] }}" class="form-control num-only" placeholder="上限なし">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="searchclear bi bi-x-circle" viewbox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>

                            <!-- アコーディオンメニュー終了 -->
                        </div>
                    </div>
                </form>

                <div class="gacha-result mx-auto">
                    @isset($choiseProducts)
                        @foreach($choiseProducts as $choiseProduct)
                        <div class="product">

                            <div class="side-content">
                                <a href="{{ $choiseProduct['detail_page_link'] }}" target="_blank" rel="noopener noreferrer">
                                    <div class="product-name"><span class="genre-name">{{ $choiseProduct['genreName'] }}</span>{{ $choiseProduct['product_name'] }}</div>

                                </a>

                                <div class="product-info">
                                    <span>{{ $choiseProduct['product_price'] }}円
                                        {{ $choiseProduct['calorie'] }}kcal</span>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    @endisset



                    <div class="result-info">
                        @isset ($totalAmount)
                            <h2>{{ $resultMsg }}</h2>
                        @endisset
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- vue.js下から呼び出す -->
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>

    <script src="{{ asset('js/select.js') }}"></script>


</body>

</html>