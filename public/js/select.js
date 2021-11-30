Vue.component("multiselect", window.VueMultiselect.default)
let selectedItems = null;
let domSelectedItems= document.querySelector("#select-genres > input:nth-child(4)").value;
if(domSelectedItems.length){
    selectedItems = domSelectedItems.split(',');
}

new Vue({
    el: "#select-genres",
    data() {
        return {
            value: selectedItems,
            options: [
                "おにぎり", "お寿司", "お弁当", "チルド弁当", "サンドイッチ・ロールパン", "ベーカリー", "パスタ", "そば・うどん・中華麺",
                "アイス・フローズン", "サラダ", "グラタン・ドリア", "スープ", "お好み焼・たこ焼き・他", "揚げ物",
                "コーヒー", "デザート", "チルド飲料", "焼菓子", "ナチュラルローソン菓子", "お惣菜", "カット野菜・フルーツ", "加工肉",
                "水物", "練物", "漬物", "冷凍食品", "乳製品", "カップ麺", "即席食品", "缶詰・乾物・乾麺"
            ]
        }
    },
    methods: {
          selectAll(){
            this.value = this.options
          },
          unSelectAll(){
            this.value = []

          }

    }

})
