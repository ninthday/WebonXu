$(document).ready(function() {
    $('input[name="chkLanguage[]"]').click(function() {
        strRtn = $('input[name="chkLanguage[]"]:checkbox:checked').map(function() {
            return $(this).val();
        }).get().join('+');
        showList(strRtn, 'uname');
    });

    showList('ja+zh+zh-TW');
    
    $("#ptlist tbody").on('click','.tw-mention',function(){
        var poster_name = $(this).text();
        var lang = $(this).closest('td').next('td').text();

        showPostTweet(poster_name, lang, function(result) {
            if (result) {
                $("#sch-name").text(poster_name);
            }
        });
    });
    
    $(".list-sort").click(function(){
        var strSort = '';
        if($(this).text() === 'Poster'){
            strSort = 'uname';
        }else if($(this).text() === 'Count'){
            strSort = 'cnt';
        }
        
        var strRtn = $('input[name="chkLanguage[]"]:checkbox:checked').map(function() {
            return $(this).val();
        }).get().join('+');
        showList(strRtn, strSort);
    });
    
    $("#downloadExcelXml").click(function() {
        var strRtn = $('input[name="chkLanguage[]"]:checkbox:checked').map(function() {
            return $(this).val();
        }).get().join('+');
        window.open("downlaodPEPoster.php?lang=" + encodeURIComponent(strRtn),
                "下載分析內容",
                "toolbar=0,location=0,directories=0,menubar=0,scrollbars=1,width=100,height=100");
    });
});

function showList(strLang, strSort) {
    $.ajaxSetup({
        cache: false
    });

    var jqxhr = $.getJSON('ajax_PEPoster.php', {
        type: 'ptlist',
        lang: strLang,
        ob: strSort
    });

    jqxhr.success(function(data) {
        if (data.rsStat) {
            buildList(data.rsContents);
        } else {
            showErrorMsg(data.rsContents);
        }
    });
}

function buildList(aryLists) {
    var strList = '';
    for (var i = 0; i < aryLists.length; i++) {
        strList += '<tr><td><a class="tw-mention" href="#">' + aryLists[i].from_user + '</a></td>';
        strList += '<td>' + aryLists[i].language + '</td>';
        strList += '<td>' + aryLists[i].cnt + '</td></tr>';
    }
    $("#ptlist tbody").children().remove();
    $("#ptlist tbody").append(strList);
}

function showPostTweet(uname, lang, callback) {
    var jqxhr = $.getJSON("ajax_PEPoster.php", {
        type: 'pcont',
        uname: uname,
        lang: lang
    });

    jqxhr.done(function(data) {
        $("#queryResult li").remove();
        $.each(data.rsContents, function(key, tweet) {
            $("#queryResult").append(showResult(tweet));
        });
        callback(true);
    });

    jqxhr.fail(function() {
        callback(false);
    });
}

function showResult(tweet) {
    var strRtn = '', strLang = '', strType = '';
    strRtn += '<li class="well"><div class="row">';
    strRtn += '<div class="col-md-12"><img class="img-rounded user-pic" src="http://api.twitter.com/1/users/profile_image/' + tweet.data_from_user + '">';
    strRtn += '<strong>' + tweet.data_from_user_name + '&nbsp;</strong><code>@' + tweet.data_from_user + '</code><br>';
    strRtn += '<small>' + tweet.TWTime + '</small>&nbsp;';
    switch (tweet.lang_detection) {
        case 'zh-TW':
            strLang = '繁中';
            break;
        case 'zh':
            strLang = '簡中';
            break;
        case 'en':
            strLang = '英文';
            break;
        case 'ja':
            strLang = '日文';
            break;
        default:
            strLang = '其他';
    }

    strRtn += '<span class="pull-right"><span class="label label-success">' + strLang + '</span></span></div></div>';
    strRtn += '<div class = "row result_content">' + tweet.data_text + '</div></li>';

    return strRtn;
}