<?=$f->renderError()?>
<div class="btn-toolbar" style="text-align: center">
  <div class="btn-group">
    <a class="btn" id="bbgras">
      <i class="icon-bold"></i>
    </a>
    <a class="btn" id="bbitalic">
      <i class="icon-italic"></i>
    </a>
    <a class="btn" id="bbimg">
      <i class="icon-picture"></i>
    </a>
    <a class="btn" id="bburl">
      <i class="icon-share"></i>
    </a>
    <a class="btn" id="bbquote">
      <i class="icon-comment"></i>
    </a>
    <a class="btn" id="bbmp3">
      <i class="icon-music"></i>
    </a>
    <a class="btn" id="bbvideo">
      <i class="icon-facetime-video"></i>
    </a>
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="icon-text-height"></i>
      <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" id="fontsizechange">
      <li value="1"><a><?=__('Tiny')?></a></li>
      <li value="2"><a><?=__('Very small')?></a></li>
      <li value="3"><a><?=__('Small')?></a></li>
      <li value="4"><a><?=__('Medium')?></a></li>
      <li value="5"><a><?=__('Large')?></a></li>
      <li value="6"><a><?=__('Very large')?></a></li>
      <li value="7"><a><?=__('Huge !')?></a></li>
    </ul>
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="icon-tint"></i>
      <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" id="colorchange">
      <li value="Black"><a style="color:black"><?=__('Black')?></a></li>
      <li value="Red"><a style="color:red"><?=__('Red')?></a></li>
      <li value="Yellow"><a style="color:Yellow"><?=__('Yellow')?></a></li>
      <li value="Pink"><a style="color:Pink"><?=__('Pink')?></a></li>
      <li value="Green"><a style="color:Green"><?=__('Green')?></a></li>
      <li value="Orange"><a style="color:Orange"><?=__('Orange')?></a></li>
      <li value="Purple"><a style="color:Purple"><?=__('Purple')?></a></li>
      <li value="Blue"><a style="color:Blue"><?=__('Blue')?></a></li>
      <li value="Beige"><a style="color:Beige"><?=__('Beige')?></a></li>
      <li value="Brown"><a style="color:Brown"><?=__('Brown')?></a></li>
      <li value="Teal"><a style="color:Teal"><?=__('Teal')?></a></li>
      <li value="Navy"><a style="color:Navy"><?=__('Navy')?></a></li>
      <li value="Maroon"><a style="color:Maroon"><?=__('Maroon')?></a></li>
      <li value="LimeGreen"><a style="color:LimeGreen"><?=__('Lime green')?></a></li>
    </ul>
    </div>
  </div>

    <?=$f->render(array('style' => 'width:100%;min-height:200px', "placeholder" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sit amet nibh ac augue posuere laoreet vel a nunc. Vivamus at nibh vitae orci vulputate accumsan. Curabitur ut nisi at est faucibus fermentum vitae nec ante. Morbi vel dui a lacus ultrices pellentesque et nec ante. Aliquam mauris massa, consectetur eget suscipit ac, tempor et nisl. Etiam mollis mollis augue, vitae malesuada lacus congue sed. Maecenas orci nibh, congue sit amet volutpat quis, cursus ac eros. Sed magna tortor, eleifend et lacinia non, feugiat sit amet justo. Integer placerat adipiscing ultrices."))?>
    <div id="dropbox" style="text-align:center;">
      <span class="message"><?=__("Drop images here")?></span>
    </div>

    <style>
    .miseenforme img {
      margin-left: 5px;
      cursor: pointer;
      vertical-align: middle;
    }
    #dropbox{
      border-radius:6px;
      position: relative;
      margin:5px auto 5px;
      min-height: 100px;
      max-height: 150px;
      white-space:nowrap;
      overflow: auto;
      overflow-y: hidden; 
      padding: 10px 0 10px 0;
        width: 100%;
      /*box-shadow: inset 0px 0px 10px 1px rgba(0, 0, 0, 0.5);*/
      border: 1px solid #CCC;
      background: #fff;
    }

    #dropbox .message{
      font-size:24pt;
        text-align: center;
        padding-top:40px;
        display: block;
        opacity: 0.15;
        color: #000;
        padding-bottom: 50px;
    }

    #dropbox .message i{
      color:#ccc;
      font-size:10px;
    }

    #dropbox:before{
      border-radius:3px 3px 0 0;
    }
  /*-------------------------
	Image Previews
--------------------------*/

#dropbox .preview{
	width:200px;
	height: 130px;
	margin: 0 0 0 10px;
	position: relative;
	text-align: center;
  display: inline-block;
  vertical-align: middle;
}

#dropbox .preview img{
	max-width: 150px;
	max-height:113px;
	border:3px solid #fff;
	display: block;
  
	box-shadow:0 0 2px #000;
}

#dropbox .imageHolder{
	display: inline-block;
	position:relative;
}

#dropbox .uploaded{
	position: absolute;
	top:0;
	left:0;
	height:100%;
	width:100%;
	background: url('/images/icones/32x32/accept.png') no-repeat center center rgba(255,255,255,0.5);
	display: none;
}

#dropbox .preview.done .uploaded{
	display: block;
}

/*-------------------------
	Progress Bars
--------------------------*/

#dropbox .progressHolder{
	position: absolute;
	background-color:#252f38;
	height:12px;
	width:100%;
	left:0;
	bottom: 0;

	box-shadow:0 0 2px #000;
}

#dropbox .progress{
	background-color:#2586d0;
	position: absolute;
	height:100%;
	left:0;
	width:0;

	box-shadow: 0 0 1px rgba(255, 255, 255, 0.4) inset;

	-moz-transition:0.50s;
	-webkit-transition:0.50s;
	-o-transition:0.50s;
	transition:0.50s;
}

#dropbox .preview.done .progress{
	width:100% !important;
}
    </style>
    <script src="/js/filedrop.js"></script>
    <script>
    function bbtag(opentag, closetag, textarea) {
      if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
        var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? opentag + caretPos.text + closetag + ' ' : opentag + caretPos.text + closetag;
        if (temp_length == 0) {
          caretPos.moveStart("character", -closetag.length);
          caretPos.moveEnd("character", -closetag.length);
          caretPos.select();
        }
        else
          textarea.focus(caretPos);
      }
      else if (typeof(textarea.selectionStart) != "undefined") {
        var begin = textarea.value.substr(0, textarea.selectionStart);
        var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
        var end = textarea.value.substr(textarea.selectionEnd);
        var newCursorPos = textarea.selectionStart;
        var scrollPos = textarea.scrollTop;
        textarea.value = begin + opentag + selection + closetag + end;
        if (textarea.setSelectionRange){
          if (selection.length == 0)
            textarea.setSelectionRange(newCursorPos + opentag.length, newCursorPos + opentag.length);
          else
            textarea.setSelectionRange(newCursorPos, newCursorPos + opentag.length + selection.length + closetag.length);
          textarea.focus();
        }
        textarea.scrollTop = scrollPos;
      }
      else {
        textarea.value += opentag + closetag;
        textarea.focus(textarea.value.length - 1);
      }
    }
    $(function() {
      $("#bbgras").click(function(){bbtag("[b]", "[/b]", document.getElementById($('textarea').attr('id')));});
      $("#bbitalic").click(function(){bbtag("[i]", "[/i]", document.getElementById($('textarea').attr('id')));});
      $("#bbimg").click(function(){bbtag("[img]", "[/img]", document.getElementById($('textarea').attr('id')));});
      $("#bburl").click(function(){bbtag("[url]", "[/url]", document.getElementById($('textarea').attr('id')));});
      $("#bbquote").click(function(){bbtag("[quote]", "[/quote]", document.getElementById($('textarea').attr('id')));});
      $("#bbvideo").click(function(){bbtag("[youtube]", "[/youtube]", document.getElementById($('textarea').attr('id')));});
      $("#bbmp3").click(function(){bbtag("[mp3]", "[/mp3]", document.getElementById($('textarea').attr('id')));});
      $('select[name=fontsizechange]').change(function(){
        var taille = $(this).find("option:selected").val();
        bbtag("[size="+taille+"]", "[/size]", document.getElementById($('textarea').attr('id')));
        $(this).find("option[value=4]").attr("selected", "selected");
      });
      $('select[name=colorchange]').change(function(){
        var couleur = $(this).find("option:selected").val().toLowerCase();
        bbtag("[color="+couleur+"]", "[/color]", document.getElementById($('textarea').attr('id')));
        $(this).find("option[value=Black]").attr("selected", "selected");
      });
      
      var dropbox = $('#dropbox'),
      message = $('.message', dropbox);
	
      dropbox.filedrop({
        paramname:'pic',

        maxfiles: 10,
          maxfilesize: 2,
        url: '<?=url_for("main/upload")?>',

        uploadFinished:function(i,file,response){
          if (response.status == "ok") {
            $.data(file).addClass('done');
            bbtag("[img]"+response.path+"[/img]", "", document.getElementById($('textarea').attr('id')));
          }
          else
            alert(response.status);
        },

          error: function(err, file) {
          switch(err) {
            case 'BrowserNotSupported':
              showMessage('Your browser does not support HTML5 file uploads!');
              break;
            case 'TooManyFiles':
              alert('Too many files! Please select 5 at most !');
              break;
            case 'FileTooLarge':
              alert(file.name+' is too large! Please upload files up to 2mb.');
              break;
            default:
              break;
          }
        },

        // Called before each upload is started
        beforeEach: function(file){
          if(!file.type.match(/^image\//)){
            alert('Only images are allowed!');
            return false;
          }
        },

        uploadStarted:function(i, file, len){
          createImage(file);
        },

        progressUpdated: function(i, file, progress) {
          $.data(file).find('.progress').width(progress);
        }

      });

      var template = '<div class="preview">'+
                '<span class="imageHolder">'+
                  '<img />'+
                  '<span class="uploaded"></span>'+
                '</span>'+
                '<div class="progressHolder">'+
                  '<div class="progress"></div>'+
                '</div>'+
              '</div>'; 


      function createImage(file){
        var preview = $(template), 
          image = $('img', preview);

        var reader = new FileReader();

        image.width = 100;
        image.height = 100;

        reader.onload = function(e){
          image.attr('src',e.target.result);
        };
        reader.readAsDataURL(file);
        message.hide();
        preview.appendTo(dropbox);
        $.data(file,preview);
      }

      function showMessage(msg){
        message.html(msg);
      }
    });
  </script>
