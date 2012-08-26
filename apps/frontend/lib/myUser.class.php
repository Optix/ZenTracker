<?php

class myUser extends sfBasicSecurityUser
{
}

/* */
function getRelativeTime($date) {
  // Déduction de la date donnée à la date actuelle
  $time = time() - strtotime($date); 

  // Calcule si le temps est passé ou à venir
  if ($time > 0) {
    $when = "il y a";
  } else if ($time < 0) {
    $when = "dans environ";
  } else {
    return "il y a moins d'une seconde";
  }
  $time = abs($time); 

  // Tableau des unités et de leurs valeurs en secondes
  $times = array( 31104000 =>  'an{s}',       // 12 * 30 * 24 * 60 * 60 secondes
                  2592000  =>  'mois',        // 30 * 24 * 60 * 60 secondes
                  86400    =>  'jour{s}',     // 24 * 60 * 60 secondes
                  3600     =>  'heure{s}',    // 60 * 60 secondes
                  60       =>  'minute{s}',   // 60 secondes
                  1        =>  'seconde{s}'); // 1 seconde         

  foreach ($times as $seconds => $unit) {
    // Calcule le delta entre le temps et l'unité donnée
    $delta = round($time / $seconds); 

    // Si le delta est supérieur à 1
    if ($delta >= 1) {
      // L'unité est au singulier ou au pluriel ?
      if ($delta == 1) {
        $unit = str_replace('{s}', '', $unit);
      } else {
        $unit = str_replace('{s}', 's', $unit);
      }
      // Retourne la chaine adéquate
      return $when." ".$delta." ".$unit;
    }
  }
}

function correctFontSize($s) {
  switch ($s[1]) {
    case 1:
      $taille = 0.7;
    break;
   
    case 2:
      $taille = 0.8;
    break;
    
    case 3:
      $taille = 0.9;
    break;
    
    case 4:
      $taille = 1.1;
    break;
  
    case 5:
      $taille = 1.5;
    break;
  
    case 6:
      $taille = 2;
    break;
  
    case 7:
      $taille = 3;
    break;
  
    default:
      $taille = 1;
  }
  return '<span style="font-size: '.$taille.'em">'.$s[2].'</span>';
}

/* Décodage BBCode */
function bbcode_decode($txt, $nobr=null) {
  $txt = strip_tags(htmlspecialchars_decode($txt));
  /* Gras */
  $txt = str_ireplace('[b]', '<strong>', $txt);
  $txt = str_ireplace('[/b]', '</strong>', $txt);
  
  /* Italique */
  $txt = str_ireplace('[i]', '<em>', $txt);
  $txt = str_ireplace('[/i]', '</em>', $txt);
  
  /* Souligne */
  $txt = str_ireplace('[u]', '<span style="text-decoration:underline">', $txt);
  $txt = str_ireplace('[/u]', '</span>', $txt);
  
  /* Citation */
  $txt = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#isU", '<blockquote title="$1">$2</blockquote>', $txt);
  $txt = str_ireplace('[quote]', '<blockquote>', $txt);
  $txt = str_ireplace('[/quote]', '</blockquote>', $txt);
  
  /* Couleur */
  $txt = preg_replace("#\[color=(.+)\](.*)\[/color\]#isU", '<span style="color: $1">$2</span>', $txt);
  
  /* Taille */
  $txt = preg_replace_callback("#\[size=([1-7])\](.*)\[\/size\]#isU",'correctFontSize', $txt);
  //$txt = preg_replace("#\[size=(.+)\](.*)\[/size\]#isU", '<span style="font-size: $1">$2</span>', $txt);
  
  /* Position (on n'en a pas besoin) */
  $txt = str_ireplace('[/align]', null, $txt);
  $txt = preg_replace("#\[align=(.+)\]#isU", null, $txt);
  $txt = str_ireplace('[center]', null, $txt);
  $txt = str_ireplace('[/center]', null, $txt);
  
  /* Image */
  $txt = preg_replace_callback("#\[img\]http://(.*)\[/img\]#isU", "getImageTag", $txt);
  $txt = preg_replace("#\[img\](.*)\[/img\]#isU", '<img src="http://'.sfConfig::get('app_host_uploads').'/$1" />', $txt);
  
  /* Youtube */
  $txt = preg_replace("#\[youtube\](.*?)\[/youtube\]#si", "<object width=480 height=385><param name=movie value='http://www.youtube.com/v/\\1&hl=fr&fs=1&color1=0x006699&color2=0x54abd6'></param><param name=allowFullScreen value=true></param><param name=allowscriptaccess value=always></param><embed src='http://www.youtube.com/v/\\1&hl=fr&fs=1&color1=0x006699&color2=0x54abd6' type='application/x-shockwave-flash' allowscriptaccess=always allowfullscreen=true width=480 height=385></embed></object>", $txt);
  
  /* DailyMotion */
  $txt = str_ireplace("[dailymotion]", '<iframe frameborder="0" width="480" height="270" src="http://www.dailymotion.com/embed/video/', $txt);
  $txt = str_ireplace("[/dailymotion]", '"></iframe>', $txt);
  
  
  /* Mp3 */
  $txt = preg_replace("#\[mp3\](.*?)\[/mp3\]#si", "<object type='application/x-shockwave-flash' data='http://static.zentracker.net/dewplayer.swf?mp3=\\1&autostart=1' width=200 height=20><param name=movie value='http://static.zentracker.net/dewplayer.swf?mp3=\\1&autostart=1' /></object>", $txt);
  
  /* Urls */
  $txt = preg_replace("#\[url=(.*)\](.*)\[/url\]#isU", '<a href="$1" target="_blank">$2</a>', $txt);
  $txt = preg_replace("#\[url\](.*)\[/url\]#isU", '<a href="$1" target="_blank">$1</a>', $txt);
  
  /* Retour à la ligne ? */
  if (!empty($nobr)) { 
    $txt = nl2br($txt);
    $txt = smileys_decode($txt);
  }
  
  return $txt;
}

/* Virer le BBCode (on laisse les URL) */
function bbcode_strip($txt, $url=null) {
  $txt = trim(strip_tags(htmlspecialchars_decode($txt)));
  $txt = preg_replace("#\[img\](.*)\[/img\]#iU", null, $txt);
  $txt = preg_replace("#\[(.*)\]#iU", null, $txt);
  return $txt;
}

/* Génère les tailles de fichier */
function makesize($bytes) {
  if (abs($bytes) < 1000 * 1024)
    return number_format($bytes / 1024, 2) . "&nbsp;Ko";
  if (abs($bytes) < 1000 * 1048576)
    return number_format($bytes / 1048576, 2) . "&nbsp;Mo";
  if (abs($bytes) < 1000 * 1073741824)
    return number_format($bytes / 1073741824, 2) . "&nbsp;Go";
  return number_format($bytes / 1099511627776, 2) . "&nbsp;To";
}

/* Retourne l'icone en échange de l'extension */
function getExtensionIcon($ext) {
  $ext = strtolower($ext);
  // Si le fichier d'icone existe
  if (file_exists("images/icones/16x16/file_extension_$ext.png"))
    return "file_extension_$ext.png";
  else {
    if (in_array($ext, array("avi", "mkv")))
      return 'film.png';
    elseif (in_array($ext, array("mp3", "flac")))
      return 'music.png';
    else
      return 'document_empty.png';
  }
}

/* Décodage smileys */
function smileys_decode($txt) {
  $sm = array(
  ":blush:" => "blush.gif",":weep:" => "weep.gif",":unsure:" => "unsure.gif",":closedeyes:" => "closedeyes.gif",":yes:" => "yes.gif",":no:" => "no.gif",":?:" => "question.gif",":!:" => "excl.gif",":idea:" => "idea.gif",":arrow:" => "arrow.gif",":hmm:" => "hmm.gif",":huh:" => "huh.gif",":w00t:" => "w00t.gif",":geek:" => "geek.gif",":look:" => "look.gif",":rolleyes:" => "rolleyes.gif",":kiss:" => "kiss.gif",":shifty:" => "shifty.gif",":blink:" => "blink.gif",":smartass:" => "smartass.gif",":sick:" => "sick.gif",":crazy:" => "crazy.gif",":wacko:" => "wacko.gif",":alien:" => "alien.gif",":wizard:" => "wizard.gif",":wave:" => "wave.gif",":wavecry:" => "wavecry.gif",":baby:" => "baby.gif",":ras:" => "ras.gif",":sly:" => "sly.gif",":devil:" => "devil.gif",":evil:" => "evil.gif",":evilmad:" => "evilmad.gif",":yucky:" => "yucky.gif",":nugget:" => "nugget.gif",":sneaky:" => "sneaky.gif",":smart:" => "smart.gif",":shutup:" => "shutup.gif",":shutup2:" => "shutup2.gif",":yikes:" => "yikes.gif",":flowers:" => "flowers.gif",":wub:" => "wub.gif",":osama:" => "osama.gif",":saddam:" => "saddam.gif",":santa:" => "santa.gif",":indian:" => "indian.gif",":guns:" => "guns.gif",":crockett:" => "crockett.gif",":zorro:" => "zorro.gif",":snap:" => "snap.gif",":beer:" => "beer.gif",":drunk:" => "drunk.gif",":sleeping:" => "sleeping.gif",":mama:" => "mama.gif",":pepsi:" => "pepsi.gif",":medieval:" => "medieval.gif",":rambo:" => "rambo.gif",":ninja:" => "ninja.gif",":hannibal:" => "hannibal.gif",":party:" => "party.gif",":snorkle:" => "snorkle.gif",":evo:" => "evo.gif",":king:" => "king.gif",":chef:" => "chef.gif",":mario:" => "mario.gif",":pope:" => "pope.gif",":fez:" => "fez.gif",":cap:" => "cap.gif",":cowboy:" => "cowboy.gif",":pirate:" => "pirate.gif",":rock:" => "rock.gif",":cigar:" => "cigar.gif",":icecream:" => "icecream.gif",":oldtimer:" => "oldtimer.gif",":wolverine:" => "wolverine.gif",":strongbench:" => "strongbench.gif",":weakbench:" => "weakbench.gif",":bike:" => "bike.gif",":music:" => "music.gif",":book:" => "book.gif",":fish:" => "fish.gif",":whistle:" => "whistle.gif",":stupid:" => "stupid.gif",":dots:" => "dots.gif",":axe:" => "axe.gif",":hooray:" => "hooray.gif",":yay:" => "yay.gif",":cake:" => "cake.gif",":hbd:" => "hbd.gif",":hi:" => "hi.gif",":offtopic:" => "offtopic.gif",":band:" => "band.gif",":hump:" => "hump.gif",":punk:" => "punk.gif",":bounce:" => "bounce.gif",":group:" => "group.gif",":console:" => "console.gif",":smurf:" => "smurf.gif",":soldiers:" => "soldiers.gif",":spidey:" => "spidey.gif",":smurf:" => "smurf.gif",":rant:" => "rant.gif",":pimp:" => "pimp.gif",":nuke:" => "nuke.gif",":judge:" => "judge.gif",":jacko:" => "jacko.gif",":ike:" => "ike.gif",":greedy:" => "greedy.gif",":dumbells:" => "dumbells.gif",":clover:" => "clover.gif",":shit:" => "shit.gif",
  ':)' => 'smile1.png',':-)' => 'smile1.png',':o)' => 'smile1.png',
    ';)' => 'wink.gif',';-)' => 'wink.gif',
    ':D' => 'grin.gif',':-D' => 'grin.gif',
    ':p' => 'tongue.gif',':-p' => 'tongue.gif',':P' => 'tongue.gif',
    ':(' => 'sad.gif',':-(' => 'sad.gif', ":sad:"=>"sad.gif",
    ":'(" => 'cry.gif',
    ":|" => 'noexpression.gif',":-|" => 'noexpression.gif',
    ":-/" => 'confused.gif', ":S" => 'confused.gif',
    ":o " => 'ohmy.gif', ":-o" => 'ohmy.gif', ":-O" => 'ohmy.gif', 
    "8)" => 'cool2.gif', "8-)" => 'cool2.gif', 
    "O:-" => 'angel.gif', "(a)" => 'angel.gif', 
    "-_-" => 'sleep.gif', 
    "<3" => 'love.gif',":love:" => 'love.gif',
  ":grrr:" => "angry.gif",
  ":smile:" => "smile2.gif",
  ":lol:" => "laugh.gif",
  ":cool:" => "cool2.gif",
  ":fun:" => "fun.gif",
  ":thumbsup:" => "thumbsup.gif",
  ":thumbsdown:" => "thumbsdown.gif",

  );
  
  foreach ($sm as $s=>$h) {
    $a = getimagesize("images/smilies/".$h);
    $txt = str_replace($s, '<img src="http://'.sfConfig::get('app_host_cdn').'/images/smilies/'.$h.'" alt="" '.$a[3].' />', $txt);
  }
  
  return $txt;
}

/* Notifications */
function notifie($uid, $msg, $img) {
  $file = "uploads/notifications/$uid.txt";
  $t = time();
  if (file_exists($file)) {
    $a = unserialize(file_get_contents($file));
    $a[$t] = array('msg' => $msg, 'img' => $img, 'readed'=>false);
    file_put_contents($file, serialize($a));
  }
  else {
    file_put_contents($file, serialize(array($t => array("msg"=>$msg, "img"=>$img, 'readed'=>false))));
  }
}