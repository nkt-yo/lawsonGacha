from downloadProductDetail import parse_url, download_product_detail
import requests
from bs4 import BeautifulSoup

# 該当するジャンルがあれば商品を取得する
allow_genre = {'rice': '0001', 'sushi': '0002', 'bento': '0003', 'chilledbento': '0004', 'sandwich': '0005', 'bakery': '0006', 'pasta': '0007', 'noodle': '0008',
               'icecream': '0009', 'salad': '0010', 'gratin': '0011', 'soup': '0012', 'konamono': '0013', 'fry': '0014', 'machikadochubo': '0015', 'coffee': '0016',
               'dessert': '0017', 'chilled': '0018', 'gateau': '0019', 'kenkosnack': '0020', 'osozai': '0021', 'cutvegetable': '0022', 'meat': '0023', 'liquid': '0024',
               'paste': '0025', 'pickles': '0026', 'frozen': '0027', 'dairy': '0028', 'cupnoodle': '0029', 'instant': '0030', 'snack': '0031', 'dry': '0032'}
machikado_genres = ['sandwich', 'bento', 'rice']
SELECT_LINK_MENU = "https://www.lawson.co.jp/recommend/original/select/toiletry/index.html"
URL_RECOMMEND = "https://www.lawson.co.jp/recommend/index.html"

def convert_dict_genre(genre_name):
    """
    {ジャンル名:ジャンルコード}を返す
    """
    return dict(genre_id=allow_genre[genre_name], genre_name=genre_name)


url = requests.get(URL_RECOMMEND)
url.encoding = url.apparent_encoding  # 文字化け対策

soup = BeautifulSoup(url.content, "html.parser", from_encoding='utf-8')
product_links = soup.select("#navArea > section.navItem01 > div > ul > li:nth-of-type(2) > ul > li")
print(product_links)

isSelect = False
for link in product_links:
    # <class 'bs4.element.Tag'>だからstrに変換しないとだめ
    if(('select' in str(link)) and (not(isSelect))):
        isSelect = True
        url = requests.get(SELECT_LINK_MENU)
        url.encoding = url.apparent_encoding  # 文字化け対策

        soup = BeautifulSoup(url.content, "html.parser", from_encoding='utf-8')
        select_links = soup.select("#sec-02 > ul.contentsNav2 > ul > li")

        for select_link in select_links:

            print(select_link)
            # urlに<a class="active">が含まれていない場合(選択済みのページではない)
            if(not("active" in str(select_link))):
                select_link = "https://www.lawson.co.jp/{}".format(select_link.find('a')['href'])
                url = requests.get(select_link)
                url.encoding = url.apparent_encoding  # 文字化け対策

                soup = BeautifulSoup(url.content, "html.parser", from_encoding='utf-8')
                print("select_link={}".format(select_link))
                genre_name, products = parse_url(soup, select_link)

            if(genre_name in allow_genre):
                #取得対象のジャンルであれば取り出す
                dict_genre = convert_dict_genre(genre_name)
                download_product_detail(products, dict_genre)

    # まちかど厨房の場合は取得方法が異なる
    elif("machikadochubo" in str(link)):
        print("machikadochuboしゅとく!")

        for machikado_genre in machikado_genres:
            link = "https://www.lawson.co.jp/recommend/original/machikadochubo/" + \
                machikado_genre + "/index.html"
            url = requests.get(link)
            url.encoding = url.apparent_encoding  # 文字化け対策

            soup = BeautifulSoup(url.content, "html.parser", from_encoding='utf-8')

            products = parse_url(soup, link)
            dict_genre = convert_dict_genre(machikado_genre)
            print(dict_genre)
            download_product_detail(products, dict_genre, True)

    elif(not('select' in str(link))):
        link = "https://www.lawson.co.jp/{}".format(link.find('a')['href'])
        url = requests.get(link)
        url.encoding = url.apparent_encoding  # 文字化け対策

        soup = BeautifulSoup(url.content, "html.parser", from_encoding='utf-8')
        genre_name, products = parse_url(soup, link)
        print(type(products))

        if(genre_name in allow_genre):
            dict_genre = convert_dict_genre(genre_name)
            download_product_detail(products, dict_genre)
