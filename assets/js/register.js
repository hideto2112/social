$(document).ready(function(){
    // ログインフォームを非表示にし、新規登録フォームを表示する
    $("#signup").click(function(){
        $("#first").slideUp("slow", function(){
            $("#second").slideDown("slow");
        });
    });

    // 新規登録フォームを非表示にし、ログインフォームを表示する
    $("#signin").click(function(){
        $("#second").slideUp("slow", function(){
            $("#first").slideDown("slow");
        });
    });
});