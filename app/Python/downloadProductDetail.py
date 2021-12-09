from pathlib import Path
from bs4 import BeautifulSoup
import re
import os
import csv
from time import sleep


def format_line(lines):
    products_list = []
    line_list = []

    # <li>タグ区切りで配列に格納
    for line in lines:
        line_list.append(str(line))
        if("</li>" in str(line)):

            line = ','.join(line_list)
            # bs4.element.Tagに変換する(str型で格納してしまうとsoup.select等ができなくなるため)
            soup = BeautifulSoup(line, "html.parser", from_encoding='utf-8')
            products_list.append(soup)
            line_list = []

    return products_list

def download_product_detail(products, dict_genre, is_machikado=False):
    """
    商品ID,ジャンルID,商品名,単価,カロリー,画像パス,詳細ページへのリンクを格納した配列を返す
    """

    products_details = []
    for product in products:

        # ジャンルID取得
        genre_id = dict_genre['genre_id']
        # 商品ID
        product_id = re.search(r'\d{7}', product.find('a')['href'].replace('.html', '')).group()
        # 商品名
        product_name = product.select_one("li > p.ttl").get_text()

        # 全角スペースが含まれている場合は半角スペースに変換する(文字化け対策)
        if("　" in product_name):
            product_name = convertToHalfWidthSpace(product_name)
            
        print("商品名:{}".format(product_name))

        # 単価
        prouct_price = product.select_one("li > p.price").get_text()

        # カロリーを取得するためのn番目の子要素
        # li:nth-of-type(1) > p:nth-of-type(calorie_child)
        calorie_child = int(3)

        calorie = product.select_one(
            "li > p:nth-of-type(3)".format(calorie_child)).get_text()

        is_new_release = product.select_one("li > p.ico_new")
        
        if(is_new_release):
            # 新発売要素が存在するとセレクターが一段下がる
            print("この商品は新発売だよ{}".format(product))
            calorie = product.select_one(
                "li > p:nth-of-type({})".format(calorie_child + 1)).get_text()

        # カロリーが書いていない場合はcsvに登録しない
        if(not("kcal" in calorie)):
            continue
        if("　" in calorie):
            # 全角スペースを半角スペースに変換する
            calorie = convertToHalfWidthSpace(calorie)
        print("カロリー：{}".format(calorie))

        # 画像が保存されているパス
        image_path = "images/{}/{}.jpg".format(dict_genre['genre_name'], product_id)

        # 詳細ページへのリンク
        detail_page_link = "https://www.lawson.co.jp{}".format(
            product.find('a')['href'])
        # print("詳細ページ:{}".format(detail_page_link))
        products_details.append([product_id, genre_id, product_name,
                                prouct_price, calorie, image_path, detail_page_link])

    try:
        with open('csvs/{}.csv'.format(dict_genre['genre_name']), 'a', encoding="UTF-8") as f:
            writer = csv.writer(f)
            for products_detail in products_details:
                print("{}を書き込みます".format(products_detail))
                writer.writerow(products_detail)
    except Exception as e:
        print(e)
        print('csvの書き込みに失敗しました')
    # googleDriveにcsvファイルが上がるのに時間がかかるため少し待つ
    # sleep(3)

def convertToHalfWidthSpace(str):
    '''
    全角スペースを半角スペースに変換する
    '''
    print("全角スペースを発見しました。商品名：{}".format(str))
    str = " ".join(str.split())
    print("半角スペースに変換しました{}".format(str))
    return str


def parse_url(soup, url_name):

    # ジャンル・URL解析
    if ("select" in url_name):

        # selectが終わった位置
        front_index = re.search(r'select', url_name).end()

        # urlにindex.htmlが含まれていない場合は末尾に追加する
        if(not(re.search(r'index', url_name))):
            print("{}にindex.htmlを追加".format(url_name))
            url_name = url_name + "index.html"

        # indexが始まった位置
        back_index = re.search(r'index', url_name).start()

        # urlからジャンルを抽出
        genre_name = url_name[front_index+1:back_index - 1]

        print("ジャンルを取得しました{}".format(genre_name))

        # 商品情報を取得
        lines = soup.select("#sec-02 > ul.col-4.heightLineParent > li")
        print(type(lines[0]))

        # liタグ区切りで配列に格納
        products = format_line(lines)
        print(type(products[0]))

        return genre_name, products

    elif("machikadochubo" in url_name):

        # 商品情報を取得
        lines = soup.select("#sec-02 > ul.col-4.heightLineParent > li")
        print("lines{}".format(lines))

        # liタグ区切りで配列に格納
        products = format_line(lines)
        print(products)

        return products

    elif(not("goods" in url_name)):

        # originalが終わった位置
        front_index = re.search(r'original', url_name).end()
        # urlからジャンルを抽出
        genre_name = url_name[front_index+1:-1]

        if("index" in str(genre_name)):
            genre_name = genre_name.replace("/index.html", '')

        print("ジャンルを取得しました{}".format(genre_name))

        # 商品情報を取得
        lines = soup.select("#sec-02 > ul > li")
        print(type(lines))

        # liタグ区切りで配列に格納
        products = format_line(lines)

        return genre_name, products

    # どこにも該当しない場合はnullを返す
    return None, None
