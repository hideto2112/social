$(document).ready(function(){

    // 検索フォームクリック時動作
    $('#search_text_input').focus(function(){
        if(window.matchMedia( "(min-width: 800px)" ).matches){
            $(this).animate({width: '250px'}, 500);
        }
    });

    // 検索ボタン
    $('.button_holder').on('click', function(){
        document.search_form.submit();
    })

    // プロフィール投稿ボタン
    $('#submit_profile_post').click(function(){
        $.ajax({
            type:    "POST",
            url:     "includes/handlers/ajax_submit_profile_post.php",
            data:    $('form.profile_post').serialize(),
            success: function(msg){
                $("#post_form").modal('hide');
                location.reload();
            },
            error:   function(){
                alert('Failue');
            }
        });
    });
});

// 要素外をクリックした場合に各ウインドウを非表示にする
$(document).click(function(e){
    // 検索結果
    if(e.target.class != "search_results" && e.target.id != "search_text_input"){
        $(".search_results").html("");
        $(".search_results_footer").html("");
        $(".search_results_footer").toggleClass("search_results_footer_empty");
        $(".search_results_footer").toggleClass("search_results_footer");
    }

    // 通知内容
    if(e.target.class != "dropdown_data_window"){
        $(".dropdown_data_window").html("");
        $(".dropdown_data_window").css({"padding": "0px", "height": "0px"});
    }
});

function getUsers(value, user){
    $.post("includes/handlers/ajax_friend_search.php", {query:value, userLoggedIn:user}, function(data){
        $(".results").html(data);
    })
}

function getDropdownData(user, type){
    if($(".dropdown_data_window").css("height") == "0px"){

        let pageName;

        if(type == 'notification'){
            pageName = "ajax_load_notifications.php";
            $("span").remove("#unread_message");
        }else if(type == 'message'){
            pageName = "ajax_load_messages.php";
            $("span").remove("#unread_message");
        }

        let ajaxeq = $.ajax({
            url:  "includes/handlers/" + pageName,
            type: "POST",
            data: "page=1&userLoggedIn=" + user,
            cache: false,

            success: function(response){
                $(".dropdown_data_window").html(response);
                $(".dropdown_data_window").css({"padding": "0px", "height": "200px", "border": "1px solid #bdc3c7", "border-top": "none"});
                $("#dropdown_data_type").val(type);
            }
        });

    }else{
        $(".dropdown_data_window").html("");
        $(".dropdown_data_window").css({"padding": "0px", "height": "0px", "border": "none"});
    }
}

function getLiveSearchUsers(value, user){
    $.post("includes/handlers/ajax_search.php", {query:value, userLoggedIn:user}, function(data){
        if($(".search_results_footer_empty")[0]){
            $(".search_results_footer_empty").toggleClass("search_results_footer");
            $(".search_results_footer_empty").toggleClass("search_results_footer_empty");
        }

        $(".search_results").html(data);
        $(".search_results_footer").html("<a href='search.php?q=" + value +"'>結果をすべて見る</a>");

        if(data == ""){
            $(".search_results_footer").html("");
            $(".search_results_footer").toggleClass("search_results_footer_empty");
            $(".search_results_footer").toggleClass("search_results_footer");
        }
    });
}