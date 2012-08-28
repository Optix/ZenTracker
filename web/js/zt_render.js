var i18n = new Array();
i18n["uploadedNewAvatar"] = "vient de changer son avatar !";
i18n["postedNewTopic"] = "vient de poster un nouveau sujet !";
i18n["postedNewNews"] = "vient de poster une nouvelle news !";
i18n["postedNewUpload"] = "vient de poster un nouvel upload !";
i18n["postedNewFrmReply"] = "vient de répondre à ce sujet !";
i18n["hasDonated"] = "vient de faire un généreux don !";

/**
 * Render current userlist
 */
function renderUsers(users) {
  var i = 0;
  var template = '<li><a><img /> </a></li>';
  // Remove current list
  $('#users-connected .dropdown-menu').find("li").remove();
  // For each user conneceted
  $.each(users, function(j, usr) {
    // Create a copy of the template
    tpl = $(template).clone();
    // Populating with data
    tpl.find("img").attr("src", usr.avatar);
    tpl.children("a").append(' '+usr.username);
    tpl.children("a").attr("href", usr.url);
    // Insert in HTML
    tpl.appendTo('#users-connected .dropdown-menu');
    i = i + 1;
  });
  // Updating number
  $('#users-connected .badge span').text(i);
}


/**
 * Render shoutbox
 */
function renderShout(shts, lastid) {
  var template = ''
  +'<a class="entree btn">'
    +'<em class="date"></em>'
    +'<div class="thumbnail"><img class="avatar" /></div>'
    +'<div class="pseudo"></div>'
    +'<p></p>'
    +'<hr class="clrfx" />'
  +'</a>';
  // For each shout retrieved
  $.each(shts, function(i, sht) {
    // If shout is already in DOM
    if ($('#sht_'+sht.id).length) 
      return false;
    // Create a copy of the template
    tpl = $(template).clone();
    // Converting date
    var dateStr=sht.created_at; //returned from mysql timestamp/datetime field
    var a=dateStr.split(" ");
    var d=a[0].split("-");
    var t=a[1].split(":");
    var date = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
    tpl.children('.date').attr("data-timestamp", date.getTime()/1000);
    // Populating with data
    tpl.attr("id", "sht_"+sht.id).attr("data-shtid", sht.id);     
    // If message is handled by system
    if (sht.system == true) {
      // Unparse JSON stored in description
      var jsonDesc = JSON.parse(sht.description);
      tpl.children('.pseudo').append('<span class="label label-info">Important</span>');
      tpl.attr("href", sht.link);
      tpl.children('p').html('<strong>'+sht.Users.username+'</strong> '+i18n[jsonDesc[0]]+'<br /><img src="/images/icones/16x16/'+jsonDesc[1]+'" /> <strong>'+jsonDesc[2]+'</strong>')
        .css("padding", "5px").css("paddingLeft", "20px").css('line-height', '20px');
      tpl.find('.avatar').attr("src", "/uploads/avatars/50x50/"+sht.Users.avatar).parent().css('width', '50px').css('height', '50px');
    }
    else {
      tpl.children('.pseudo').append('<img src="/images/icones/16x16/user_comment.png" /> '
        +'<span style="font-weight:bold">'+sht.Users.username+'</span>');
      tpl.attr("href", scriptname+"shoutbox/show/id/"+sht.id);
      tpl.children('p').html(getSmilies(strip_tags(sht.description)));
      tpl.find('.avatar').attr("src", "/uploads/avatars/32x32/"+sht.Users.avatar);
    }

    // CSS
    tpl.css("min-height", "32px").hide();
    tpl.children('.avatar').css("height", "32px").css("width", "32px");
    // Displaying
    if (lastid == null)
      tpl.appendTo('#liste_shouts').show(); 
    else
      tpl.prependTo('#liste_shouts').delay(200*i).show('blind', {}, 200);
  });
  if (shts.length > 0 && lastid != null) {
    $('#sdn-pop').get(0).volume=localStorage.getItem('sndVolume');
    $('#sdn-pop').get(0).play();
  }
}

/**
 * Render entries for left panel
 */
function renderLeft(page_index, jq) {
  // If there is nothing to display, just send a notice
  if (array.left.length == 0)
	  renderNotice("I'm sorry, but there is nothing to display here :(");
  //$('#centre > *').remove();

  $('.entree, .ui-effects-wrapper, .row-fluid > .alert').remove();
  
  var max_elem = Math.min((page_index+1) * itemPerPage, array.left.length);
  var j = 0;
  for(var i=page_index*itemPerPage;i<max_elem;i++) {
    j++;
    if (array.module == "membres")
  	  renderMemberList(j, array.left[i]);
    else if (array.module == "forums")
  	  renderForums(j, array.left[i]);
    else if (array.module == "topics")
      renderTopics(j, array.left[i]);
    else if (array.module == "partage")
      renderUploads(j, array.left[i]);
    else {
      var l = array.left[i];
      // Basic template
  		var tpl = $('<a class="entree btn">'+
  		  '<em class="date"></em><h3></h3><div class="thumbnail">'+
  		  '<img class="avatar" /></div><p></p><hr class="clrfx" /></a>');
  		// If it is a category
  		if (l.description == "" && l.url == "") {
  			var tpl = $('<div class="entree btn btn-inverse"><h3></h3></div>');
  		}
  		tpl.children("h3").text(l.title);
  		// For description, no tags
  		tpl.children("p").text(strip_tags(l.description));
      tpl.attr('href', l.url);
  		if (l.author != null)
  		  tpl.find(".avatar").attr("src", l.Users.avatar);
  		else
  		  tpl.find(".avatar").attr("src", "/images/avatar_default.gif");
  		// Insert in page
      tpl.hide().appendTo('#centre').delay(200*j).show('blind', {}, 200);
    }
	}
  $('#centre a').each(function() {
    if ($(this).attr('href') == location.protocol+'//'+location.host+location.pathname)
      $(this).addClass('btn-info');
  });
}
function renderForums(i, l) {
	// Basic template
	var tpl = $('<a class="entree btn">'+
	  '<div class="thumbnail"><img class="avatar" />'+
	  '</div><h3></h3><p></p></a>');
	// If it is a category
	if (!isset(l.description, l.url))
		var tpl = $('<div class="entree btn btn-inverse"><h3></h3></div>');
  // Or a forum
	else {
    tpl.css("height", "44px").css('overflow', 'hidden');
    tpl.attr('href', l.url);
    tpl.children("p").text(l.description);
    tpl.find(".avatar").attr("src", "/images/icones/32x32/comments.png");
  }
	tpl.children("h3").text(l.title);
	// Insert in page
	tpl.hide().appendTo('#centre').delay(200*i).show('blind', {}, 200);
}
function renderTopics(i, l) {
  // Basic template
  var tpl = $('<a class="entree btn topic">'+
    '<div class="thumbnail"><img class="avatar" />'+
    '</div><h3></h3><p style="max-height: 19px"></p></a>');
  tpl.css("height", "44px");
  // Doing some logic...
  if (l.is_locked == true)
    tpl.find(".avatar").attr("src", "/images/icones/32x32/lock.png");
  else
    tpl.find(".avatar").attr("src", "/images/icones/32x32/comments.png");
  // If the topic is important
  if (l.is_important == true)
    tpl.addClass('btn-warning');
  // If the topic is unreaded
  if (l.FrmTopicsUsr.length > 0 && l.FrmMessages.length > 0) {
    if (l.FrmTopicsUsr[0].lastmsgid == l.FrmMessages[0].id)
      tpl.css("opacity", "0.5"); 
  }
  // Populating data
  tpl.attr('href', l.url);
  tpl.children("h3").text(l.title);
  // Description
  if (l.FrmMessages[0].author)
    var author = '<img src="'+l.FrmMessages[0].Users.avatar+'" style="width:16px" /> '+l.FrmMessages[0].Users.username;
  else
    var author = '<img src="/images/avatar_default.gif" style="width:16px" /> Anonymous';
  
  tpl.children("p").html('By '+author+' <span class="date" data-timestamp="'+l.updated_at+'"></span> : ')
  .append(strip_tags(l.FrmMessages[0].content)).css('text-align', "left");
  // Insert in page
  tpl.hide().appendTo('#centre').delay(200*i).show('blind', {}, 200);
}
function renderUploads(i, l) {
  // Basic template
  var tpl = $('<a class="entree btn upload">'+
    '<h3></h3><div class="thumbnail"><img class="avatar" style="width: 50px; height:50px" />'+
    '</div><p></p><p class="stats"></p></a>');
  // Inserting cover
  tpl.children('p:eq(0)').html(strip_tags(l.description, '<img>'));
  tpl.find(".avatar").attr("src", tpl.children('p:eq(0)').find('img').first().attr('src'));
  // If we don't have the minimum level required
  if (l.minlevel > level)
    tpl.children("h3").append('<img src="/images/icones/16x16/delete.png" /> ');
  // If freeleech
  if (parseInt(l.minlevel) == 0)
    tpl.children("h3").append('<img src="/images/icones/16x16/ribbon.png" title="Freeleech" /> ');
  // Populating data
  tpl.attr('href', l.url).attr('data-mode', "normal");
  tpl.children("h3").append(l.title);
  tpl.children('p:eq(0)').text(strip_tags(l.description));
  if (l.hash.length == 40)
    tpl.children("p:eq(1)").append('<span class="badge"><img src="/images/icones/16x16/utorrent.png" /></span> ');
  if (l.Users)
    tpl.children("p:eq(1)").append('<span class="badge"><img src="/uploads/avatars/16x16/'+l.Users.avatar+'" /> '+l.Users.username+'</span> ');
  else
    tpl.children("p:eq(1)").append('<span class="badge"><img src="/images/avatar_default.gif" /> Anonymous</span> ');
  if (l.Categories.picture)
    tpl.children("p:eq(1)").append('<span class="badge"><img src="/images/icones/16x16/'+l.Categories.picture+'" /> '+l.Categories.name+'</span> ');
  else
    tpl.children("p:eq(1)").append('<span class="badge">'+l.Categories.name+'</span> ');
  tpl.children("p:eq(1)").append('<span class="badge"><img src="/images/icones/16x16/compress.png" /> '+getFileSize(l.size)+'</span> ');
  tpl.children("p:eq(1)").append('<span class="badge"><img src="/images/icones/16x16/comments.png" /> '+l.cnt_coms+'</span> ');
  tpl.children("p:eq(1)").append('<span class="badge badge-success"><img src="/images/icones/16x16/status_online.png" /> '+l.cnt_seeders+'</span> ');
  tpl.children("p:eq(1)").append('<span class="badge" style="background-color: #b94a48;"><img src="/images/icones/16x16/status_online.png" /> '+l.cnt_leechers+'</span> ');
  tpl.children("p:eq(1)").append('<span class="badge badge-info"><img src="/images/icones/16x16/flag_finish.png" /> '+l.cnt_completed+'</span> ');
  // Insert in page
  tpl.hide().appendTo('#centre').delay(200*i).show('blind', {}, 200);
}
function renderMemberList(i, u) {
  // Template
	var tpl = $('<a class="entree btn"><div class="thumbnail">'
	  +'<img class="avatar" /></div><span class="badge pull-right"></span><h3></h3></a>');
	// CSS
  tpl.css("min-height", "32px").attr('href', u.url);
  tpl.find('.avatar, .thumbnail').css("height", "28px").css("width", "28px");
	tpl.children('.thumbnail').css('position', 'relative').css('top', '-3px');
	// Data
	tpl.children("h3").text(u.username);
	tpl.children(".badge").text(u.score).css('marginTop', '5px');
	tpl.find(".avatar").attr("src", u.avatar);
	// Insert
	tpl.hide().appendTo('#centre').delay(200*i).show('blind', {}, 200);
}

/**
 * Render content for right panel
 */
function renderRight() {
  renderTabs(array).appendTo('#droite > .well');
  $("#pm_topics_recipients").tokenInput(scriptname+"membres/search", {
    propertyToSearch: "username",
    resultsLimit: 10,
    resultsFormatter: function(item){ 
      return "<li>" + "<img src='" + item.avatar + "' height='24px' width='24px' /> "
       + item.username + "</li>" },
  });
  $('#droite #coms').each(function() {
    renderComments(0, null);
  });
  $('#droite .tinymce').parents('.controls').css('marginLeft', '0px').parent().find('label:contains(Content)').remove();
  $('#droite .tinymce').redactor({ 
    autoformat: false,
    autoresize: false,
    removeStyles: true,
    imageUpload: scriptname+'main/upload',
    lang: $('html').attr('lang')
  });
  // Generating thumbnail
  $('#droite .detectCover').each(function(idx, v) {
    var src = $(this).find('img').first().attr('src');
    var img = $('<img class="avatar" style="width: 50px; height:50px" />').attr('src', src);
    if (src)
      $(this).html(img).tooltip();
    else
      $(this).html('');
  });
  // Format size
  $('#droite .size').each(function(idx, v) {
    $(this).html(getFileSize(parseInt($(this).text())));
  });
}


/**
 * Render tabs
 */
function renderTabs() {
  var data = array;
  var container = $('<div></div>');
  // Building tabs bar
  var tabsTpl = $('<ul class="nav nav-tabs"></ul>');
  var tabCnt = $('<div class="tab-content"></div>');
  // For each tab
  $.each(data.right, function(idx, tabs) {
    // Building tab
    var tab = $('<li><a data-toggle="tab"><img /></a></li>');
    var tab2 = $('<div class="tab-pane"></div>');
    // Insert data
    tab.children("a").attr('href', "#"+idx).css('text-align', "center");
    tab2.attr('id', idx);
    // A nice icon
    tab.find("img").attr('src', "/images/icones/16x16/"+tabs.picture);
    // The tab's title, only visible for desktop computers (large screen)
    tab.children("a").append(' <span class="visible-desktop">'+tabs.title+'</span>');
    // If server is sending raw html
    if (tabs.format == "html")
      tab2.html(tabs.data);
    else if (tabs.format == "json") {
      // If comments are sent
      if (idx == "coms") {
        $('#droite > .well').append('<div id="rightPaginate" class="pagination pagination-centered"><ul></ul></div>');
        $('#rightPaginate ul').pagination(tabs.data.length, {
          items_per_page: itemPerPage,
          callback:renderComments
        });
      }
      // If files are included in JSON
      else if (idx == "files")
        renderFiles(tabs).appendTo(tab2)
      else if (idx == "peers")
        renderPeers(tabs).appendTo(tab2);
    }
    // Append to tabs bar
    tab.appendTo(tabsTpl);
    tab2.appendTo(tabCnt);
  });
  // Set first tab active
  tabsTpl.find('li').eq(0).addClass("active");
  tabCnt.find('div').eq(0).addClass("active");
  // Appending to container
  tabsTpl.appendTo(container);
  tabCnt.appendTo(container);
  return container;
}


/**
 * Render comments
 * 
 * @return jQuery <div>
 */
function renderComments(page_index, jq) {
  var tabs = array.right.coms;
  console.log(array);
  console.log(tabs);
  // Setting container
  comTable = $('<div></div>');
  // If no comment are posted
  if (tabs.data.length == 0) {
    $('<p style="text-align:center"><i class="icon-comment"></i> No one has commented yet.</p>')
    .appendTo(comTable);
  }
  // For each comment
  var max_elem = Math.min((page_index+1) * itemPerPage, tabs.data.length);
  var j = 0;
  for(var i=page_index*itemPerPage;i<max_elem;i++) {
    j++;
    var com = tabs.data[i];
    // Designing the template
    var comTpl = $('<table class="table table-striped table-bordered">'+
      '<tr><td class="username span1"></td><td class="datetime"><span class="date"></span>'+
      '<div class="pull-right"></div></td></tr>'+
      '<tr><td class="avt"></td><td class="msg"></td></tr>'+
      '<tr><td colspan="2" style="text-align: center" class="votes"></td></tr></table>');
    // Filling username and avatar
    if (com.author) {
      comTpl.find('.username').text(com.Users.username);
      comTpl.find('.avt').html('<div class="thumbnail" style="float: none"><img src="'+com.Users.avatar+'" /></div>');
    }
    else {
      comTpl.find('.username').text("Anonymous");
      comTpl.find('.avt').html('<div class="thumbnail" style="float: none"><img src="/images/avatar_default.gif" /></div>');
    }
      
    // Date
    comTpl.find('.datetime .date').attr("data-timestamp", com.updated_at);
    // Body message
    comTpl.find('.msg').append(com.content);
    // Votes
    var hasVoted = false;
    if (com.MsgVotes.length > 0) {
      // Setting vars
      var agree = 0;
      var dislike = 0;
      // Building bars
      comTplVotes1 = $('<div style="width:49%;margin-bottom:0" class="progress progress-info"><div class="bar"></div></div>');
      comTplVotes2 = $('<div style="width:49%;margin-bottom:0" class="progress progress-danger pull-right"><div class="bar pull-right"></div></div>');
      // Counting votes
      $.each(com.MsgVotes, function(t, v) {
        if (v.vote == true)
          agree++;
        else
          dislike++; 
        // Have we already voted ?
        if (v.uid == uid)
          hasVoted = true;
      });
      // Getting the percentage
      agree = Math.round(agree/com.MsgVotes.length*100);
      dislike = Math.round(dislike/com.MsgVotes.length*100);
      // If the bar is long enought to display text
      if (agree > 10)
        comTplVotes1.children('.bar').text(agree+"%");
      if (dislike > 10)
        comTplVotes2.children('.bar').text(dislike+"%");
      // Setting width's bar
      comTplVotes1.children('.bar').css("width", agree+"%");
      comTplVotes2.children('.bar').css("width", dislike+"%");
      // Insert in table
      comTpl.find('.votes').prepend(comTplVotes1).prepend(comTplVotes2);
    }
    // If there are no vote yet, don't display last row.
    else
      comTpl.find('td[colspan=2]').parent().remove();

    comTpl.find('.datetime .pull-right').append('<a href="" class="quotemsg btn btn-mini btn-success"><i class="icon-white icon-comment"></i> Citer</a> ');
    // If we are the author of the comment
    if (com.author == uid || role == "adm" || role == "mod") {
      // Displaying action links
      comTpl.find('.datetime .pull-right').append('<a href="'+scriptname+'messages/edit/mid/'+com.id+'" class="comEdit btn btn-mini btn-warning"><i class="icon-white icon-pencil"></i> Éditer</a> ');
      comTpl.find('.datetime .pull-right').append('<a href="'+scriptname+'messages/delete/mid/'+com.id+'" class="comDelete btn btn-mini btn-danger"><i class="icon-white icon-trash"></i> Supprimer</a> ');
    }
    if (hasVoted == false && com.author != uid) {
      comTpl.find('.datetime .pull-right').append('<a href="'+scriptname+'messages/vote/mid/'+com.id+'/v/1" class="like btn btn-mini btn-info"><i class="icon-white icon-star"></i> +1</a> ');
      comTpl.find('.datetime .pull-right').append('<a href="'+scriptname+'messages/vote/mid/'+com.id+'/v/0" class="dislike btn btn-mini btn-danger"><i class="icon-white icon-star-empty"></i> -1</a> ');
    }
    // If message is not deleted, then insert into HTML
    if (com.deleted_at == null)
      comTpl.appendTo(comTable);
    // Message deleted
    else {
      // Remove action links, votes and quoting
      comTpl.find('tr:eq(1), .pull-right').remove();
      // Just display deleting date
      comTpl.find('.datetime').text('Message deleted '+com.deleted_at); 
      // Keep it discrete
      comTpl.css('opacity', '0.5');
      // Insert in HTML
      comTpl.appendTo(comTable);
    }
    // Resetting margin
    comTpl.css("marginBottom", "5px");
  }
  $('#coms').html(comTable);
  //return comTable;
}

/**
 * Render filelist
 * 
 * @return jQuery <table>
 */
function renderPeers(tabs) {
  peerTable = $('<div></div>');
  // For each file
  $.each(tabs.data, function(idx, peer) {
    // Designing a row
    var peerTpl = $('<div class="entree btn"><div class="thumbnail"><img class="avatar" /></div>'
      +'<div class="pull-right"></div><h6></h6><div class="progress"><div class="bar"></div></div></div>');
    peerTpl.css('height', '42px');
    // Avatar
    if (peer.Users) {
      var avatar = '/uploads/avatars/32x32/'+peer.Users.avatar;
      var username = peer.Users.username;
    }
    else {
      var avatar = '/images/avatar_default.png';
      var username = "Anonymous";
    }
      
    peerTpl.find('.avatar').attr('src', avatar).css('width', "32px")
      .parent().css('width', "32px").addClass('pull-left');
    // Nickname
    peerTpl.children('h6').text(username);
    // Percentage
    var perc = (peer.uploadsize-peer.remain)/peer.uploadsize*100;
    peerTpl.find('.bar').css("width", perc+'%').text(Math.round(perc)+'%');
    // Stats
    peerTpl.find('.pull-right').html('<i class="icon-download"></i> '+getFileSize(peer.download)
    +' &bull; '+getFileSize(peer.upload)+' <i class="icon-upload"></i>');
    // If user is seeder
    if (peer.remain == 0) {
      peerTpl.find('.progress').addClass('progress-success');
      peerTpl.prependTo(peerTable);
    }
    else {
      peerTpl.appendTo(peerTable);
    }
  });
  return peerTable;
}

/**
 * Render filelist
 * 
 * @return jQuery <table>
 */
function renderFiles(tabs) {
  comTable = $('<table class="table table-striped table-bordered"></table>');
  // For each file
  $.each(tabs.data, function(idx, file) {
    // Don't display some files
    if (file.path[0] == "Thumbs.db")
      return true;
    // Designing a row
    var comTpl = $('<tr><td class="name"></td><td class="size"></td></tr>');
    // Filename 
    comTpl.find('.name').html(getImageExtension(getFileExtension(file.path[0]))+' '+file.path[0]);
    // Filesize
    comTpl.find('.size').html(file.length).css("text-align", "right");
    // Inserting to the parent table
    comTpl.appendTo(comTable);
  });
  return comTable;
}

/**
 * Render errors or notices
 */
function renderError(e) {
  var tpl = $('<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button><i class="icon-exclamation-sign"></i></div>');
  tpl.hide().append(' '+e).prependTo('#centre').show('blind', {}, 200);
}
// Notice will be hidden after 5sec
function renderNotice(e) {
  var tpl = $('<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><i class="icon-ok-sign"></i></div>');
  tpl.hide().append(' '+e).prependTo('#centre').show('blind', {}, 200).delay(5000).hide('blind', {}, 1000, function() { $(this).remove(); });
}

/**
 * Render modal box (like upload)
 */
function renderModal(title, data) {
  var tpl = $('#myModal');
  tpl.find("h3").text(title);
  renderTabs(data).prependTo('#myModal .modal-body .row-fluid').addClass("span8");

  if (data.right.peers.length > 0) {
    peer_model = '<div class="btn" style="display: block;height:43px">'+
                  '<div class="thumbnail"><img /></div>'+
                  '<h5 style="text-align:left"></h5>'+
                  '<div class="progress"><div class="bar"></div></div>'+
                  '<hr style="clear:left;width:0px;margin:0;visibility:hidden;" />'+
                '</div>';
    $.each(data.right.peers, function(i, peer) {
      peer_tpl = $(peer_model).clone();
      peer_tpl.children("h5").text(peer.Membre.username);
      var progress = Math.round((data.taille-peer.reste)/data.taille*100);
      peer_tpl.find(".bar").css("width", progress+"%");
      if (progress == 100)
        peer_tpl.children(".progress").addClass("progress-success");
      else
        peer_tpl.children(".progress").addClass("progress-striped");
      peer_tpl.find("img").attr("src", peer.Membre.avatar);
      peer_tpl.find("img").css("width", "32px");
      peer_tpl.appendTo(tpl.find(".span4"));
    });
  }
  else {
    peer_tpl = $('<p style="text-align: center;font-size: 200%;opacity: 0.5;">Aucun membre connecté à ce torrent</p>');
    peer_tpl.appendTo(tpl.find(".span4"));
  }

  $('#myModal').modal('show');
  $('#myModal .modal-body').css("max-height", "700px");
  $('#myModal').css("marginLeft", "-"+($('#myModal').width()/2)+"px");
  $('#myModal').css("marginTop", "-"+($('#myModal').height()/2)+"px");
  
}