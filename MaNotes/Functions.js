//$( document ).ready(function() {

    this_div = $("#note_show,#addEditNote");
  // Handler for .ready() called.
    $("#save_colors").click(function(){
        changeColor($(this));
        console.log($(this));
    });
    $("#show_delete,#save_delete").click(function(){
        if ( window.console && window.console.log ) {
            id= $(this).parent().attr('data');
            deleteNote(id);
        }changeColor(btn)
    });
    $("#add_note").click(function(){
        if ( window.console && window.console.log ) {
            //addNote();
            $("#addEditNote").show();
            $("#addEditNote .control_btns").attr('data',"0")
            $("#ae_title").removeClass("colors_red colors_green colors_yellow colors_blue");
            $("#ae_note").removeClass ("colors_red colors_green colors_yellow colors_blue");

            $("#ae_title").val("");
            $("#ae_note").val("");
            $("#ae_title").attr("class","colors_blue");
            $("#ae_note").attr("class","colors_blue");
            $("#save_colors").removeClass("colors_red colors_green colors_yellow colors_blue");
            $("#save_colors").addClass("colors_blue");
        }
    });
    $("#save_save").click(function(){
    id = $(this).parent().attr('data');
    if (id >0 ) editNote(id);
    else        addNote();
    });
    $("#show_edit").click(function(){
        id = $(this).parent().attr('data');
        if (id>0){
            //console.log("edit"); 
            $(this).parent().parent().hide();
            getNoteToEdit(id);
        }
    });
    $("p.Note").on("click",function(){
        showNote($(this).parent().attr('id'));
    });
    $(".btn-back").on("click",function(){ 
        $(this).parent().parent().hide();
    });

    $("body").on("click",function(e){
    console.log($(e.target).is('button'));
        if($(e.target).is('div') || $(e.target).is('button') || $(e.target).is('input') || $(e.target).is('textarea')){
            //e.preventDefault();
            return;
        }
        $(".btn-back").parent().parent().hide();
    });
    
    $(".share button").on("click",function(){ 
        note_id = $(this).parent().parent().parent().attr('id');
        parent_div = $(this).parent();
        url = "controller.php";
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                id_online : note_id,
                action : "shareNote"
            },
            success : function(data){
            if(data!=0){
                parent_div.html("<a href='shared_note.php?id="+data+"'  target='_blank'>Shared</a>");
            }
                //putNote(data);

            }
        });
    });
    function showNote(id){
        url = "controller.php";
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                id_online : id,
                action : "getNote"
            },
            success : function(data){
                putNote(data);

            }
        });
    }
    function getNoteToEdit(id){
        url = "controller.php";
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                id_online : id,
                action : "getNote"
            },
            success : function(data){
                $("#addEditNote").show();
                note = JSON.parse(data);
                console.log(note);
                $("#ae_title").val(b64_to_utf8(note.title));
                $("#ae_note").val(b64_to_utf8(note.note));
                
                $("#ae_title").removeClass("colors_red colors_green colors_yellow colors_blue");
                $("#ae_note").removeClass ("colors_red colors_green colors_yellow colors_blue");
                
                $("#ae_title").attr("class","colors_"+note.color);
                $("#ae_note").attr("class","colors_"+note.color);
                $("#addEditNote .control_btns").attr("data",note.id_online);
                $("#save_colors").removeClass("colors_red colors_green colors_yellow colors_blue");
                $("#save_colors").addClass("colors_"+note.color);
                $("#save_colors").attr("data",note.color);
                
            }
        });
    }
    function deleteNote(id){
        url = "controller.php";
        
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                id_online : id,
                action : "deleteNote"
            },
            success : function(data){
                //console.log(id);
                $( "#"+id ).remove();
                this_div.hide();
                console.log(this_div);
                showMsg("Note Deleted successfully !","error");

            }
        });
    }
    function editNote(id){
        url = "controller.php";
        title = $("#ae_title").val();
        note = $("#ae_note").val();
        color = $("#save_colors").attr("data");
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                action    : "editNote",
                id_online : id,
                title     : title,
                note      : note,
                color     : color
            },
            success : function(data){
                window.location.href = location.protocol + '//' + location.host + location.pathname+'?msg_to_show=Note Modified successfully !'; 
                //$(this).parent().parent().hide();
            }
        });
    }
    function addNote(){
        url = "controller.php";
        title = $("#ae_title").val();
        note = $("#ae_note").val();
        color = $("#save_colors").attr("data");
        $.ajax({
            type : 'POST',
            url : url,
            data : {
                action  : "addNote",
                title   : title,
                note    : note,
                color  : color
            },
            success : function(data){
                //window.load();
               window.location.href = location.protocol + '//' + location.host + location.pathname+'?msg_to_show=Note added successfully !'; 
                this_div.hide();
                

            }
        });
    }
    function changeColor(btn){
        cls = ["blue","red","green","yellow"];
        cl = btn.attr("data");
        btn.removeAttr("data");
        nextI=0;
        if (cls.indexOf(cl)+1 > 3) nextI=0;
        else                       nextI=cls.indexOf(cl)+1;
        btn.removeClass("colors_red colors_green colors_yellow colors_blue");
        btn.addClass("colors_"+cls[nextI]);
        btn.attr("data",cls[nextI]);
        $("#ae_title").removeClass("colors_red colors_green colors_yellow colors_blue");
        $("#ae_note").removeClass ("colors_red colors_green colors_yellow colors_blue");
        $("#ae_title").attr("class","colors_"+cls[nextI]);
        $("#ae_note").attr("class","colors_"+cls[nextI]);
    }
    
    function showMsg(msg,type){
        this_msg = $('.msg_'+ type +'');
        this_msg.find("h3").text(msg);
        this_msg.animate({top:"1"}, 500);
        this_msg.css('visibility',"visible");
        setTimeout(function() {
            this_msg.css('top', -this_msg.outerHeight());
            this_msg.css('visibility',"Hidden");
        }, 3000);
        //alert(msg);
    }
    function putNote(data){
        note = JSON.parse(data);
        console.log(note);
        $("#note_show").show();
        $("#note_date_added").empty();
        $("#note_title").empty();
        $("#note_body_p").empty();
        $("#note_date_added").append(note.date_added);
        $("#note_title").append(b64_to_utf8(note.title));
        $("#note_body_p").append(nl2br(b64_to_utf8(note.note)));
        $("#note_show").attr("class",note.color);
        $("#note_show .control_btns").attr("data",note.id_online);
        $("#show_colors").removeClass("colors_red colors_green colors_yellow colors_blue");
        $("#show_colors").addClass("colors_"+note.color);
        
    }
    function utf8_to_b64( str ) {
      return window.btoa(unescape(encodeURIComponent( str )));
    }

    function b64_to_utf8( str ) {
      return decodeURIComponent(escape(window.atob( str )));
    }
    function nl2br (str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
    
//});
