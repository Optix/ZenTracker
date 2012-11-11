/* =============================================================================
 * Real Time Management
 * ===========================================================================*/
var wsState = false;
if (window.WebSocket) {
  var url = "ws://"+window.location.host+":8001/";
  ws = new WebSocket(url);
  ws.onopen = function(e) {
    wsState = true;
    renderNotice("You're now connected with WebSocket.");
  }
  ws.onerror = function(e) {
    wsState = false;
    $('.sht .btn_actions').append('<img src="/images/icones/16x16/bullet_orange.png />');
  }
  ws.onmessage = function(e) {
    var wsJson = JSON.parse(e.data);
    console.log(wsJson);
    if (wsJson['action'] == "shoutbox")
      renderShout(wsJson['data'], 1);
    if (wsJson['action'] == "usersConnected")
      renderUsers(wsJson['data']);
  }
}


function actualiserNotif(title) {
  $.get("main/notifications/act/cnt", function(data) {
    if (parseInt(data) > 0) {
      $('#cntnotif').text(data);
      $('#cntnotif').css("color", "red");
      $('title').text(title+" ("+data+")");
      $('#gauche > ul > li > span').eq(2).css("color", "red");
      $('#gauche > ul > li > span').eq(2).css("text-shadow", "0px 0px 5px red");
    }
    else {
      $('#cntnotif').text('0');
      $('#cntnotif').css("color", "");
      $('title').text(title);
    }
  });
}

var emoticons = {
    ":blush:":"blush.gif",":weep:":"weep.gif",":unsure:":"unsure.gif",":closedeyes:":"closedeyes.gif",":yes:":"yes.gif",":no:":"no.gif",":?:":"question.gif",":!:":"excl.gif",":idea:":"idea.gif",":arrow:":"arrow.gif",":hmm:":"hmm.gif",":huh:":"huh.gif",":w00t:":"w00t.gif",":geek:":"geek.gif",":look:":"look.gif",":rolleyes:":"rolleyes.gif",":kiss:":"kiss.gif",":shifty:":"shifty.gif",":blink:":"blink.gif",":smartass:":"smartass.gif",":sick:":"sick.gif",":crazy:":"crazy.gif",":wacko:":"wacko.gif",":alien:":"alien.gif",":wizard:":"wizard.gif",":wave:":"wave.gif",":wavecry:":"wavecry.gif",":baby:":"baby.gif",":ras:":"ras.gif",":sly:":"sly.gif",":devil:":"devil.gif",":evil:":"evil.gif",":evilmad:":"evilmad.gif",":yucky:":"yucky.gif",":nugget:":"nugget.gif",":sneaky:":"sneaky.gif",":smart:":"smart.gif",":shutup:":"shutup.gif",":shutup2:":"shutup2.gif",":yikes:":"yikes.gif",":flowers:":"flowers.gif",":wub:":"wub.gif",":osama:":"osama.gif",":saddam:":"saddam.gif",":santa:":"santa.gif",":indian:":"indian.gif",":guns:":"guns.gif",":crockett:":"crockett.gif",":zorro:":"zorro.gif",":snap:":"snap.gif",":beer:":"beer.gif",":drunk:":"drunk.gif",":sleeping:":"sleeping.gif",":mama:":"mama.gif",":pepsi:":"pepsi.gif",":medieval:":"medieval.gif",":rambo:":"rambo.gif",":ninja:":"ninja.gif",":hannibal:":"hannibal.gif",":party:":"party.gif",":snorkle:":"snorkle.gif",":evo:":"evo.gif",":king:":"king.gif",":chef:":"chef.gif",":mario:":"mario.gif",":pope:":"pope.gif",":fez:":"fez.gif",":cap:":"cap.gif",":cowboy:":"cowboy.gif",":pirate:":"pirate.gif",":rock:":"rock.gif",":cigar:":"cigar.gif",":icecream:":"icecream.gif",":oldtimer:":"oldtimer.gif",":wolverine:":"wolverine.gif",":strongbench:":"strongbench.gif",":weakbench:":"weakbench.gif",":bike:":"bike.gif",":music:":"music.gif",":book:":"book.gif",":fish:":"fish.gif",":whistle:":"whistle.gif",":stupid:":"stupid.gif",":dots:":"dots.gif",":axe:":"axe.gif",":hooray:":"hooray.gif",":yay:":"yay.gif",":cake:":"cake.gif",":hbd:":"hbd.gif",":hi:":"hi.gif",":offtopic:":"offtopic.gif",":band:":"band.gif",":hump:":"hump.gif",":punk:":"punk.gif",":bounce:":"bounce.gif",":group:":"group.gif",":console:":"console.gif",":smurf:":"smurf.gif",":soldiers:":"soldiers.gif",":spidey:":"spidey.gif",":smurf:":"smurf.gif",":rant:":"rant.gif",":pimp:":"pimp.gif",":nuke:":"nuke.gif",":judge:":"judge.gif",":jacko:":"jacko.gif",":ike:":"ike.gif",":greedy:":"greedy.gif",":dumbells:":"dumbells.gif",":clover:":"clover.gif",":shit:":"shit.gif",
  ':)':'smile1.png',':-)':'smile1.png',':o)':'smile1.png',
    ';)':'wink.gif',';-)':'wink.gif',
    ':D':'grin.gif',':-D':'grin.gif',
    ':p':'tongue.gif',':-p':'tongue.gif',':P':'tongue.gif',
    ':(':'sad.gif',':-(':'sad.gif', ":sad:":"sad.gif",
    ":'(":'cry.gif',
    ":|":'noexpression.gif',":-|":'noexpression.gif',
    ":-/":'confused.gif', ":S":'confused.gif',
    ":o ":'ohmy.gif', ":-o":'ohmy.gif', ":-O":'ohmy.gif', 
    "8)":'cool2.gif', "8-)":'cool2.gif', 
    "O:-":'angel.gif', "(a)":'angel.gif', 
    "-_-":'sleep.gif', 
    "<3":'love.gif',":love:":'love.gif',
  ":grrr:":"angry.gif",
  ":smile:":"smile2.gif",
  ":lol:":"laugh.gif",
  ":cool:":"cool2.gif",
  ":fun:":"fun.gif",
  ":thumbsup:":"thumbsup.gif",
  ":thumbsdown:":"thumbsdown.gif"
};
function getSmilies(msg) {
  var url = "/images/smilies/", patterns = [],
     metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;
  for (var i in emoticons) {
    if (emoticons.hasOwnProperty(i)){ // escape metacharacters
      patterns.push('('+i.replace(metachars, "\\$&")+')');
    }
  }
  return msg.replace(new RegExp(patterns.join('|'),'g'), function (match) {
    return typeof emoticons[match] != 'undefined' ?
           '<img src="'+url+emoticons[match]+'"/>' :
           match;
  });
}
function listSmilies() {
  var container = $('<div></div>');
  $.each(emoticons, function(i, v) {
    container.append('<img src="/images/smilies/'+v+'" alt="'+i+'" class="smiley" /> ');
  });
  renderError(container.html());
}
$(function() {
  $('.smiley').live('click', function(e) {
    $('textarea[name=sht_txt]').val($('textarea[name=sht_txt]').val()+' '+$(this).attr('alt')).focus();
    $(".alert-error").alert('close');
  });
});


/* =============================================================================
 * Shoutbox
 * ===========================================================================*/
function actualiserShout() {
  if ($.data(document.body, "loadingShout"))
    return true;
  
  $.data(document.body, "loadingShout", true);
  // Getting last shout ID
  var lastid = $('#liste_shouts').children().first().attr("data-shtid");
  // Calling server
  $.getJSON(scriptname+"shoutbox/index/id/"+lastid, function(data) {
    renderShout(data, lastid);
    $.data(document.body, "loadingShout", false);
  });
}



/* =============================================================================
 * Main function
 * ===========================================================================*/
$(function(){   
  $('body').height($(window).height()-40);
  $('#droite').height($(window).height()-79);
  if($.browser.mozilla) {
    $('#droite > .well').height($('#droite').height()-79).css('min-height', '0%');
  }
  else
    $('#droite > .well').height($('#droite').height()-79);
  $('textarea[name=sht_txt]').width($('#centre').width()-150);
  actualiserShout();
  $('#myModal').hide();

  
  // Handling links a navbar
  $('.navbar li ul li a, .entree, #droite .detectCover').live('click', function(e) {
    // If already highlighted, do nothing
    if ($(this).hasClass('btn-info'))
      return false;
    // If link has not href
    if (!$(this).attr('href'))
      return false;
    // Disable links
    e.preventDefault();
    var href = $(this);
    // If it is a link from left side
    if (href.hasClass("entree")) {
      // We remove highlight for every entries
      $('#centre .entree').removeClass('btn-info');
      // and set it to current link
      href.addClass("btn-info");
    }
    leftPage = 1;
    // Calling server
    $.getJSON(href.attr('href'), function(data, textStatus, jqXHR) {
      if (jqXHR.status == 202) {
        window.location.reload(true);
        return false;
      }

      // Changing URL
      if (typeof history.pushState != 'undefined') 
        history.pushState(data.module, 'Page', href.attr('href'));
      
      // For debugging
      console.log(data);
      // Insert blocks on left side
      if (data.left) {
        array = data;
        $('#centre > *').remove();
        $('#centre').append('<div id="leftPaginate" class="pagination pagination-centered"><ul></ul></div>');
        $('#centre #leftPaginate ul').pagination(array.left.length, {
            items_per_page: itemPerPage,
            callback:renderLeft
        });
      }
      if (data.right) {
        array.right = data.right;
      }
      // Wanna hide to remplace content
      $('#droite').fadeTo(100, 0.1, function() {
        // Remove everything
        $('#droite > .well > *').remove();
        // Populating
        renderRight();
        // Showing
        $('#droite').fadeTo(200, 1);
      });
    }).error(function(e) { renderError(e);});
  });

  // Left Pagination
  $('#centre .pages button').live('click', function() {
    // If already selected, do nothing
    if ($(this).hasClass('btn-info'))
      return false;
    // Else, removing highlighting
    $('#centre .pages button').removeClass('btn-info');
    // and set it to new page
    $(this).addClass('btn-info');
    // Hiding elements of current page
    $('.entree').hide('normal');
    // And display these of the next one
    $('.entree[data-page='+$(this).text()+']').show();
    // Updating global var
    leftPage = parseInt($(this).text());
  });


  // Sound volume
  $('#sndVolume').val(localStorage.getItem("sndVolume"));
  // On change
  $('#sndVolume').live("change", function(e) {
    // Report to user
    renderNotice("Volume has been changed to "+Math.round($(this).val()*100)+'%');
    // Store into browser
    localStorage.setItem("sndVolume", $(this).val());
  });
  
  $('textarea[name=sht_txt]').on("keypress", function(e) {
    if (e.which == '13') {
      e.preventDefault();
      $('.sht input, .sht textarea').attr('disabled', 'disabled');
      // If WebSocket
      if (wsState) {
        ws.send(JSON.stringify({"shoutbox-add": $('.sht textarea').val()}));
        $('.sht textarea').val("");
      }
      else {
        $.post($('.sht').attr('action'), {sht_txt: $('.sht textarea').val()}, function(data) {
          if (data == "ok") {
            $('.sht textarea').val("");
            // On récupère de suite les nouvelles shouts
            actualiserShout();
          }
          else
            renderError("Unable to save your shout.");
        });
      }
      $('.sht input, .sht textarea').removeAttr('disabled');
    }
  });

  // Il faut double cliquer pour activer la suppression
  $('.js_supprimer').live('click', function(e){
    e.preventDefault();
    if ($('html').attr("lang") == "fr")
      var ln = "Etes-vous sûr ?";
    else
      var ln = "Are you sure ?";
    if (confirm(ln)) {
      $.get($(this).attr('href'));
      $(this).parent().parent().hide('blind', { }, 700);
      $(this).parent().parent().remove();
    }
  });

  // Handling form processing
  $('form').live('submit', function(e) {
    // If form contains file uploading, let browser send it directly.
    if ($(this).find('input[type=file]').length > 0)
      return true;
    // else...
    // Don't submit form
    e.preventDefault();
    // Getting URL
    var url = $(this).attr('action');
    if (url == "" || url == "#")
      url = window.location.href;
    // Getting form & data from it
    var form = $(this);
    var post = $(this).serialize();

    renderNotice("Sending form...");
    form.find('input[type=submit]').attr("disabled", "disabled");
    // Calling server with POST
    $.post(url, post, function(data, textStatus, jqXHR) {
      if (jqXHR.status == 202) {
        window.location.reload(true);
        return false;
      }
      console.log(data);
      if (typeof data == "string")
        var data = JSON.parse(data);
      // Insert blocks on left side
      if (data.left) {
        array = data;
        $('#centre > *').remove();
        $('#centre').append('<div id="leftPaginate" class="pagination pagination-centered"><ul></ul></div>');
        $('#centre #leftPaginate ul').pagination(array.left.length, {
            items_per_page: itemPerPage,
            callback:renderLeft
        });
      }
      else {
        array.right = data.right;
      }

      $('#droite').fadeTo(100, 0.1, function() {
        // Remove everything
        $('#droite > .well > *').remove();
        // Populating
        renderRight();
        // Showing
        $('#droite').fadeTo(200, 1);
      });
      
      if (data.left || data.right) {
        form.find('input').val('');
        form.find('textarea').text('');
        renderNotice("Form successfully saved !");
      }
      else {
        renderError("Form seems invalid by server. Please correct highlighted fields.");
        $.each(data[1], function(idx, val) {
          form.find('.help-inline').remove();
          form.find('input[id$="'+val+'"]').parent().append('<span class="help-inline">'+data[2][idx]+'</span>');
          form.find('input[id$="'+val+'"]').parents('.control-group').addClass("error");
        });
      }
    }).error(function(e) { renderError(e.status+' '+e.statusText);});
  });

  $('#uploads_filters_size').live('change', function() {
    var size = $(this).val()*1024*1024;
    $(this).attr('title', getFileSize(size)).attr('data-original-title', getFileSize(size)).tooltip('show');
  });
});




$('a.like,a.dislike').live("click", function(e) {
  e.preventDefault();
  var lnk = $(this);
  $.get($(this).attr("href"), function(d) {
    if (d == "ok") {
      lnk.parent().children('.like, .dislike').hide('slow');
      renderNotice('Vote saved !');
    }
  }).error(function(e) { renderError(e.status+' '+e.statusText);});
});
$('.quotemsg').live('click', function(e) {
  // Disable link
  e.preventDefault();
  // Viaual feedback : msg is already quoted
  $(this).removeClass('btn-success');
  $(this).children('i').removeClass('icon-white');
  // Finding values
  var val = $(this).parents("table").find('.msg').text();
  var author = $(this).parents("table").find('.username').text();
  // Template to inject in redactor
  val = '<p><blockquote><p>'+val+'</p><small>'+author+'</small></blockquote><br></p>';
  // Injecting
  $('.redacmsg').setCode(val);
  // Switching tab
  $('#droite .nav a[href=#new]').tab('show');
  // Focus redactor
  $('.redacmsg').focus();
});
// Removing a message
$('a.comDelete').live('click', function(e) {
  // JS is handling this link
  e.preventDefault();
  // This is our current link
  var lnk = $(this);
  // Calling server
  $.get(lnk.attr('href'), function(d) {
    // If everything is OK, the message has been deleted
    if (d == "ok") {
      // Hiding with slow motion
      lnk.parents("table").hide('blind', {}, 1000, function() { $(this).remove(); });
      // Display a notice
      renderNotice('Message bas been deleted successfully !');
    }
  }).error(function(e) { renderError(e.status+' '+e.statusText);});
});
// Editing a comment
$('a.comEdit').live('click', function(e) {
  // Disable link
  e.preventDefault();
  // Getting some values
  var lnk = $(this);
  var msg = lnk.parents('table').find('.msg');
  // If edit mode is already active
  if (lnk.hasClass("btn-success")) {
    // Post content to server
    $.post(lnk.attr('href'), { msg: msg.html() }, function(d) {
      // If server has edited the post
      if (d == "ok") {
        // We cannot edit the cell
        msg.attr('contentEditable', 'false');
        // Putting new date
        var date = Math.round(new Date().getTime() / 1000);
        lnk.parents(".datetime").children('.date').attr('data-timestamp', date);
        // Hiding edit link
        lnk.hide("slow");
        // Inform user
        renderNotice("Message has been edited !");
      }
    });
  }
  // Not active, then active it
  else {
    // Now the current link will be the submit one
    lnk.html('<i class="icon-white icon-pencil"></i> OK');
    // Changing color
    lnk.removeClass('btn-warning').addClass('btn-success');
    // Make cell contentEditable (HTML5), 'cause that's cool :-) 
    msg.attr('contentEditable', 'true').focus();
  }
});


/* =============================================================================
 * Datetime management
 * ===========================================================================*/
// Convert a timestamp to relative time
function getRelativeTime(time, offsetTime) {
  var dateFunc = new Date();
  var timeSince = dateFunc.getTime() - time*1000 - offsetTime;
  var inSeconds = timeSince / 1000;
  var inMinutes = timeSince / 1000 / 60;
  var inHours = timeSince / 1000 / 60 / 60;
  var inDays = timeSince / 1000 / 60 / 60 / 24;
  var inMonths = timeSince / 1000 / 60 / 60 / 24 / 30;
  var inYears = timeSince / 1000 / 60 / 60 / 24 / 365;
  var lang = $('html').attr("lang");
  if(Math.round(inSeconds) == 1){
    if (lang == "fr")
      return "Il y a tout juste une seconde";
    else
      return "Just a second ago"
  }
  else if(inMinutes < 1.01){
    if (lang == "fr")
      return "Il y a "+Math.round(inSeconds) + " secondes";
    else
      return Math.round(inSeconds) + " seconds ago";
  }
  else if(Math.round(inMinutes) == 1){
    if (lang == "fr")
      return "Il y a une minute";
    else
      return "One minute ago";
  }
  else if(inHours < 1.01){
    if (lang == "fr")
      return "Il y a "+Math.round(inMinutes) + " minutes";
    else
      return Math.round(inMinutes) + " minutes ago";
  }

  // in hours
  else if(Math.round(inHours) == 1)
    if (lang == "fr")
      return "Il y a une heure";
    else
      return "One hour ago";
  else if(inDays < 1.01){
    if (lang == "fr")
      return "Il y a "+Math.round(inHours) + " heures";
    else
      return Math.round(inHours) + " hours ago";
  }
  else if(Math.round(inDays) == 1)
    if (lang == "fr")
      return "Il y a une journée";
    else
      return "One day ago";
  else if(inMonths < 1.01){
    if (lang == "fr")
      return "Il y a "+Math.round(inDays) + " jours";
    else
      return Math.round(inDays) + " days ago";
  }
  else if(Math.round(inMonths) == 1){
    if (lang == "fr") 
      return "Il y a un mois";
    else
      return "Last month";
  }
  else if(inYears < 1.01){
    if (lang == "fr")
      return "Il y a "+Math.round(inMonths) + " mois";
    else
      return Math.round(inMonths) + " months ago";
  }
  else if(Math.round(inYears) == 1){
    if (lang == "fr")
      return "Il y a un an";
    else
      return "Last year";
  }
  else {
    if (lang == "fr")
      return "Il y a "+Math.round(inYears) + " ans";
    else
      return Math.round(inYears) + " years ago";
  }
}
setInterval(function() {
  $('.date').each(function(idx) {
    if ($(this).attr("data-timestamp"))
      var newDate = getRelativeTime(parseInt($(this).attr("data-timestamp")), offsetTime);
      if ($(this).html() != newDate) {
        $(this).text(newDate);
      }
  });
}, 900);
 

function trim(txt) {
  return txt.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

/* =============================================================================
 * Files management
 * ===========================================================================*/
$('#uploads_url').live('change', function() {
  var url = getjHebergApiUrl($(this).val());
  var container = $('<ul></ul>');
  $.getJSON(url, function(json) {
    container.append('<li class="btn">'+getImageExtension(getFileExtension(json.fileName))
      +' <strong>'+json.fileName+'</strong> ('+getFileSize(json.fileSize)+')</a>');
  });
  $(this).parent().append(container);
});
function getFileSize (fs) {
  if (fs >= 1073741824) {return round_number(fs / 1073741824, 2) + ' GB';}
  if (fs >= 1048576)    {return round_number(fs / 1048576, 2) + ' MB';}
  if (fs >= 1024)       {return round_number(fs / 1024, 0) + ' KB';}
  return fs + ' B';
}; 
function round_number(num, dec) {
  return Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
}
function getFileExtension(filename) { 
 var dot = filename.lastIndexOf("."); 
 if( dot == -1 ) return ""; 
 var extension = filename.substr(dot+1,filename.length); 
 return extension; 
}
function getImageExtension(ext) {
  var video = ['avi', 'mkv'];
  var audio = ['mp3', 'flac'];
  var book = ['epub'];
  if (video.indexOf(ext) > -1)
    return '<img src="/images/icones/16x16/film.png" />';
  else if (audio.indexOf(ext) > -1)
    return '<img src="/images/icones/16x16/music.png" />';
  else if (book.indexOf(ext) > -1)
    return '<img src="/images/icones/16x16/book_open.png" />';
  else
    return '<img src="/images/icones/16x16/file_extension_'+ext+'.png" />';
}





function strip_tags (input, allowed) {
  allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
    return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
  });
}

function isset() {
  var a=arguments; var l=a.length; var i=0;
 
  if (l==0) {
    throw new Error('Empty isset');
  }
 
  while (i!=l) {
    if (typeof(a[i])=='undefined' || a[i]===null) {
      return false;
    } else {
      i++;
    }
  }
  return true;
}