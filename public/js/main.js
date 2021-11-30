// TOPへ戻るボタン
$(function() {
  var appear = false;
  var pagetop = $('#page_top');
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {  //300pxスクロールしたら

      if (appear == false) {
        appear = true;
        pagetop.stop().animate({
          'bottom': '50px' //下から50pxの位置に
        }, 300); //0.3秒かけて現れる
      }

    } else {

      if (appear) {
        appear = false;
        pagetop.stop().animate({
          'bottom': '-50px' //下から-50pxの位置に
        }, 300); //0.3秒かけて隠れる
      }

    }
  });

  pagetop.click(function () {

    $('body, html').animate({ scrollTop: 0 }, 10); 
    return false;
    
  });
});

// ページを読み込んだ時に要素が入力されているかチェックする
window.addEventListener('DOMContentLoaded', function() {

  $('.input_box').each(function(index, element) {
    // $()を付けないとDOM Elementが渡される
    toggle_button($(element));

  });
  
});

// 削除ボタン表示切替
$(function(){

  $('.input_box').on('input', function(){
    toggle_button($(this));
  });
});

// 削除ボタンが押されたら要素を削除
$(function(){

  $('.searchclear').click(function(){
      $(this).parent().find('input').val('');
      $(this).hide();

  });
});

function toggle_button(obj) {

  if(obj.find('input').val().length){
    obj.find('.searchclear').show();
  }else{
      obj.find('.searchclear').hide();
  }
}

$(document).ready(function(){
  $(".num-only").blur(function(){

      const regex = /[^0-9]/g;
      var str = $(this).val();
      let isFound= str.match(regex);

      if(isFound){
        $(this).val(str.replace(str, ""));
        toggle_button($(this).parent());
      }
      
  })
})
