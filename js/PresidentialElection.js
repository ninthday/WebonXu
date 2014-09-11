$(document).ready(function() {
    $("a.tw-mention").click(function(){
        var mention_name = $(this).text();
        showMention(mention_name, function(result){
            if (result) {
                $("#sch-name").text(mention_name);
                $("#dl-name").text(mention_name);
            }
        });
    });
    
    
    $("#downloadExcelXml").click(function() {
        var mention_name = $("#sch-name").text();
        window.open("downlaodMention.php?uname=" + mention_name,
                "下載分析內容",
                "toolbar=0,location=0,directories=0,menubar=0,scrollbars=1,width=100,height=100");
    });

    // Click Page 
    $("#page-list li a").click(function() {
        var nowPage = $(this).text();
        tweetSearch(nowPage, function(result) {
            if (result) {
//                alert(parseInt(nowPage));
                setPage(parseInt(nowPage));
            }
        });

        return false;
    });
    
    function showMention(uname, callback){
        var jqxhr = $.getJSON("ajax_PresidentialElection.php", {
            uname: uname
        });
        
        jqxhr.done(function(data) {
            $("#queryResult li").remove();
            $.each(data, function(key, tweet) {
                $("#queryResult").append(showResult(tweet));
            });
            callback(true);
        });

        jqxhr.fail(function() {
            callback(false);
        });
    }

    function tweetSearch(page, callback) {
        var twe_id = '', uname = '', b_date = '', e_date = '';

        if ($("#twi_id").val() !== "") {
            twe_id = $("#twi_id").val();
        }
        if ($("#uname").val() !== "") {
            uname = $("#uname").val();
        }

        if ($("#begin_date").val() !== "") {
            b_date = $("#begin_date").val();
        }

        if ($("#end_date").val() !== "") {
            e_date = $("#end_date").val();
        }

        $("input[name='lang']:checked").each(function(i) {
            langs[i] = this.value;
        });
        $("input[name='type']:checked").each(function(i) {
            types[i] = this.value;
        });

        var jqxhr = $.getJSON("ajax_data_download.php", {
            twid: twe_id,
            unme: uname,
            bdate: b_date,
            edate: e_date,
            lang: langs.join('+'),
            typ: types.join('+'),
            pg: page
        });

        jqxhr.done(function(data) {
            $("#queryResult li").remove();
            $.each(data.query_result, function(key, tweet) {
                $("#queryResult").append(showResult(tweet));
            });
            changePageList(data.page_list);
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

        switch (tweet.markup) {
            case 'M':
                strType = 'Mention';
                break;
            case 'R':
                strType = 'Retweet';
                break;
            default:
                strType = 'Original';
                break;
        }
        strRtn += '<span class="pull-right"><span class="label label-success">' + strLang + '</span>&nbsp;<span class="label label-info">' + strType + '</span></span></div></div>';
        strRtn += '<div class = "row result_content">' + tweet.data_text + '</div></li>';

        return strRtn;
    }
    function resetPage() {
        $("#now-page").val(1);
    }

    function setPage(nowpage) {
        $("#now-page").val(nowpage);
    }
    
    function changePageList(aryPageList){
        if(aryPageList[0][0]){
                $("#page-list li:eq(0)").attr('class','');
            }else{
                $("#page-list li:eq(0)").attr('class','disabled');
            }
        $("#page-list li:eq(0)").children("a").text(aryPageList[0][1]);
        
        for(var i=1; i<=10 ;i++){
            if(aryPageList[i][0]){
                $("#page-list li:eq("+ i +")").attr('class','');
            }else{
                $("#page-list li:eq("+ i +")").attr('class','disabled');
            }
            $("#page-list li:eq("+ i +")").children("a").text(aryPageList[i][1]);
        }
        if(aryPageList[11][0]){
                $("#page-list li:eq(11)").attr('class','');
            }else{
                $("#page-list li:eq(11)").attr('class','disabled');
            }
        $("#page-list li:eq(11)").children("a").text(aryPageList[11][1]);
    }
});


