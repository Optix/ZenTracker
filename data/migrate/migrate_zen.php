<?php
/**
 * ZenTracker - Migration Script
 * (from ZenTracker beta)
 *
 * @package    zt2
 * @subpackage migrate
 * @author     Optix
 */


/**
 * Variables for configuration. Databases have to be on the same SQL server
 */
$host   = "localhost";
$usr    = "sf";
$pwd    = "";
$source = "zt";
$dest   = "zt2";



/**
 * Function used to generate slug URLs
 */
function toAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = @iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

/**
 * Connecting to DBs
 */
echo "Connecting...\n";
$dbSource      = new PDO('mysql:host=localhost;dbname='.$source, $usr, $pwd);
$dbDestination = new PDO('mysql:host=localhost;dbname='.$dest, $usr, $pwd);
$dbSource->query("SET NAMES 'UTF-8");
$dbDestination->query("SET NAMES 'UTF-8");


/**
 * Migrate users
 */
$r = $dbDestination->query('DELETE FROM frm_topicsusr;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM msg_messages;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM pm_participants;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM uploads;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM users;') or die(print_r($dbDestination->errorInfo()));
echo "Migrating users...\n";
$r = $dbDestination->query('INSERT INTO '.$dest.'.users (id, username, password, email, random, pid, avatar, lastvisit, role, created_at, updated_at, slug)
	SELECT mbr_id, username, password, email, random, pid, avatar, lastvisit, droits, creation, creation, LOWER(username) FROM '.$source.'.membre
	') or die(print_r($dbDestination->errorInfo()));

/**
 * Migrate categories
 */
$r = $dbDestination->query('DELETE FROM categories;') or die(print_r($dbDestination->errorInfo()));
echo "Migrating categories...\n";
$r = $dbDestination->query('INSERT INTO '.$dest.'.categories (id, name, picture, root_id, lft, rgt, level, slug)
	SELECT id, titre, image, root_id, lft, rgt, level, REPLACE(LOWER(titre),\' \',\'-\') FROM '.$source.'.cat
	') or die(print_r($dbDestination->errorInfo()));


$r = $dbDestination->query('DELETE FROM donations;') or die(print_r($dbDestination->errorInfo()));
echo "Migrating donations...\n";
$r = $dbDestination->query('INSERT INTO '.$dest.'.donations (id, donor, amount, created_at, updated_at)
  SELECT iddons, mid, valeur, creation, creation FROM '.$source.'.dons
  ') or die(print_r($dbDestination->errorInfo()));

$r = $dbDestination->query('DELETE FROM shoutbox;') or die(print_r($dbDestination->errorInfo()));
echo "Migrating shoutbox...\n";
$r = $dbDestination->query('INSERT INTO '.$dest.'.shoutbox (id, author, description, created_at, updated_at)
  SELECT sht_id, sht_mid, sht_txt, sht_date, sht_date FROM '.$source.'.shoutbox
  WHERE sht_system = 0') or die(print_r($dbDestination->errorInfo()));


/**
 * Migrate uploads
 */
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
    $txt = str_replace($s, '<img src="/images/smilies/'.$h.'" />', $txt);
  }
  
  return $txt;
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
  $txt = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#isU", '<blockquote><p>$2</p><small>$1</small></blockquote>', $txt);
  $txt = str_ireplace('[quote]', '<blockquote><p>', $txt);
  $txt = str_ireplace('[/quote]', '</p></blockquote>', $txt);
  
  /* Couleur */
  $txt = preg_replace("#\[color=(.+)\](.*)\[/color\]#isU", '<span style="color: $1">$2</span>', $txt);
  
  /* Taille */
  $txt = preg_replace("#\[size=([1-7])\](.*)\[\/size\]#isU",'$2', $txt);
  //$txt = preg_replace("#\[size=(.+)\](.*)\[/size\]#isU", '<span style="font-size: $1">$2</span>', $txt);
  
  /* Position (on n'en a pas besoin) */
  $txt = str_ireplace('[/align]', null, $txt);
  $txt = preg_replace("#\[align=(.+)\]#isU", null, $txt);
  $txt = str_ireplace('[center]', null, $txt);
  $txt = str_ireplace('[/center]', null, $txt);
  
  /* Image */
  $txt = preg_replace("#\[img\](.*)\[/img\]#isU", '<img src="$1" />', $txt);
  
  /* Youtube */
  $txt = preg_replace("#\[youtube\](.*?)\[/youtube\]#si", "<object width=480 height=385><param name=movie value='http://www.youtube.com/v/\\1&hl=fr&fs=1&color1=0x006699&color2=0x54abd6'></param><param name=allowFullScreen value=true></param><param name=allowscriptaccess value=always></param><embed src='http://www.youtube.com/v/\\1&hl=fr&fs=1&color1=0x006699&color2=0x54abd6' type='application/x-shockwave-flash' allowscriptaccess=always allowfullscreen=true width=480 height=385></embed></object>", $txt);
  
  /* DailyMotion */
  $txt = str_ireplace("[dailymotion]", '<iframe frameborder="0" width="480" height="270" src="http://www.dailymotion.com/embed/video/', $txt);
  $txt = str_ireplace("[/dailymotion]", '"></iframe>', $txt);

  /* Urls */
  $txt = preg_replace("#\[url=(.*)\](.*)\[/url\]#isU", '<a href="$1" target="_blank">$2</a>', $txt);
  $txt = preg_replace("#\[url\](.*)\[/url\]#isU", '<a href="$1" target="_blank">$1</a>', $txt);
  
  $txt = nl2br($txt);
  $txt = smileys_decode($txt);
  
  return $txt;
}
echo "Migrating torrents...\n";
$r = $dbSource->query('SELECT * FROM torrent');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO uploads (id, hash, title, cat, description, author, url, size, minlevel, created_at, updated_at, slug)
	VALUES (null, :h, :t, :c, :d, :a, :u, :s, 1, NOW(), NOW(), :sl)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "h" => $d['hash'],
    "t" => $d['titre'],
    "c" => $d['cat'],
    "d" => bbcode_decode($d['description']),
    "a" => $d['auteur'],
    "u" => $d['hash'].'.btf',
    "s" => $d['taille'],
    "sl"=> toAscii($d['titre'])
  ));
}
$dbDestination->commit();

echo "Migrating torrent comments...\n";
$r = $dbSource->query('SELECT * FROM torrents_coms');
$q = $dbDestination->prepare('INSERT INTO msg_messages (id, module, upid, author, content, created_at, updated_at)
  VALUES (null, "partage", :k, :a, :c, :cr, :up)');
while ($d = $r->fetch()) {
  $v = $dbDestination->query('SELECT id FROM uploads WHERE hash = "'.$d['hash'].'"')->fetch();
  $q->execute(array(
    "k" => $v['id'],
    "a" => $d['auteur'],
    "c" => bbcode_decode($d['contenu']),
    "cr" => $d['creation'],
    "up" => $d['creation'],
  )) or die(print_r($q->errorInfo()));
}
echo "Migrating torrent peers...\n";
$r = $dbDestination->query('DELETE FROM torrents_peers;') or die(print_r($dbDestination->errorInfo()));
$r = $dbSource->query('SELECT * FROM torrents_connectes');
$q = $dbDestination->prepare('INSERT INTO torrents_peers (hash, pid, peer_id, uid, ip, port, download, upload, remain, useragent, created_at, updated_at)
  VALUES (:h, :pid, :peid, :u, :ip, :port, :dl, :upl, :r, :ua, :cr, :up)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "h" => $d['hash'],
    "pid" => $d['pid'],
    "peid" => $d['peer_id'],
    "u" => $d['mid'],
    "ip" => inet_ntop($d['ip']),
    "port" => $d['port'],
    "dl" => $d['download'],
    "upl" => $d['upload'],
    "r" => (int) $d['reste'],
    "ua" => $d['useragent'],
    "cr" => $d['datemaj'],
    "up" => $d['datemaj'],));
  //)) or die(print_r($q->errorInfo()));
}
$r = $dbDestination->query('DELETE FROM torrents_peers_offset;') or die(print_r($dbDestination->errorInfo()));
echo "Migrating offsets...\n";
$r = $dbDestination->query('INSERT INTO '.$dest.'.torrents_peers_offset (hash, pid, download, upload)
  SELECT hash, pid, download, upload FROM '.$source.'.torrents_connectes_offset
  ');



echo "Migrating forums...\n";
$r = $dbDestination->query('DELETE FROM msg_messages;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM frm_topics;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM frm_forums;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('DELETE FROM frm_cats;') or die(print_r($dbDestination->errorInfo()));

$r = $dbSource->query('SELECT * FROM frm_cat');
$q = $dbDestination->prepare('INSERT INTO frm_cats (id, name, slug)
  VALUES (:id, :n, :sl)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "id" => $d['idfrm_cat'],
    "n" => $d['frm_catnom'],
    "sl"=> toAscii($d['frm_catnom'])
  ));
}

echo "Importing forums...\n";
$r = $dbSource->query('SELECT * FROM frm_forum');
$q = $dbDestination->prepare('INSERT INTO frm_forums (id, cat, name, description, minroleread, minlevelread, minrolewrite, minlevelwrite, created_at, updated_at, slug)
  VALUES (:id, :c, :n, :d, "mbr", "1", "mbr", "1", NOW(), NOW(), :sl)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "id" => $d['idfrm_forum'],
    "c" => $d['catid'],
    "n" => $d['frm_titre'],
    "d" => $d['frm_desc'],
    "sl"=> toAscii($d['frm_titre'])
  ));
}

echo "Importing forum topics...\n";
$r = $dbSource->query('SELECT * FROM frm_sujet');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO frm_topics (id, forum, title, is_locked, is_important, created_at, updated_at, slug)
  VALUES (:id, :f, :t, :l, :i, NOW(), NOW(), :sl)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "id" => $d['idfrm_sujet'],
    "f" => $d['forum'],
    "t" => $d['titre'],
    "l" => $d['verrouille'],
    "i" => $d['postit'],
    "sl"=> toAscii($d['titre'])
  ));
}
$dbDestination->commit();

echo "Importing forum messages...\n";
$r = $dbSource->query('SELECT * FROM frm_msg');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO msg_messages (id, module, tid, author, content, created_at, updated_at, deleted_at)
  VALUES (null, "forums", :k, :a, :c, :cr, :up, :de)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "k" => $d['sujet'],
    "a" => $d['auteur'],
    "c" => bbcode_decode($d['message']),
    "cr" => $d['creation'],
    "up" => ($d['edition'])?$d['edition']:$d['creation'],
    "de" => $d['suppression']
  ));
}
$dbDestination->commit();

echo "Updating topic timings (last message date)...\n";
$q = $dbDestination->query('UPDATE frm_topics f SET 
  created_at = (SELECT MIN(created_at) FROM msg_messages WHERE module = "forums" AND tid = f.id),
  updated_at = (SELECT MAX(created_at) FROM msg_messages WHERE module = "forums" AND tid = f.id)');



/**
 * Migrate private messages
 */
echo "Migrating private messages...\n";
$r = $dbDestination->query('DELETE FROM pm_topics;') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('INSERT INTO '.$dest.'.pm_topics (id, title, slug)
  SELECT mp_id, mp_sujet, mp_id FROM '.$source.'.mp_sujet') or die(print_r($dbDestination->errorInfo()));
$r = $dbDestination->query('INSERT INTO '.$dest.'.pm_participants (mpid, mpmid, readed, deleted)
  SELECT mp_id, mp_mid, readed, deleted FROM '.$source.'.mp_participants') or die(print_r($dbDestination->errorInfo()));
$r = $dbSource->query('SELECT * FROM mp_msg');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO msg_messages (id, module, pmid, author, content, created_at, updated_at)
  VALUES (null, "pm", :k, :a, :c, :cr, :up)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "k" => $d['mp_id'],
    "a" => $d['mp_auteur'],
    "c" => bbcode_decode($d['mp_txt']),
    "cr" => $d['mp_date'],
    "up" => $d['mp_date'],
  ));
}
$dbDestination->commit();
echo "Updating PM timings (last message date)...\n";
$q = $dbDestination->query('UPDATE pm_topics f SET 
  created_at = (SELECT MIN(created_at) FROM msg_messages WHERE module = "pm" AND pmid = f.id),
  updated_at = (SELECT MAX(created_at) FROM msg_messages WHERE module = "pm" AND pmid = f.id)');

echo "Migrating news...\n";
$r = $dbSource->query('SELECT * FROM news');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO news (id, title, description, author, created_at, updated_at, slug)
  VALUES (null, :t, :d, :a, :c, :u, :s)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "t" => $d['titre'],
    "a" => $d['auteur'],
    "d" => bbcode_decode($d['contenu']),
    "c" => $d['creation'],
    "u" => $d['creation'],
    "s" => toAscii($d['titre']),
  ));
}
$dbDestination->commit();


echo "Migrating IP...\n";
$r = $dbSource->query('SELECT * FROM ip');
$dbDestination->beginTransaction();
$q = $dbDestination->prepare('INSERT INTO ips (ip, uid, created_at, updated_at)
  VALUES (:i, :u, :c, :u)');
while ($d = $r->fetch()) {
  $q->execute(array(
    "i" => inet_ntop($d['ip']),
    "u" => $d['uid'],
    "c" => $d['creation'],
    "u" => $d['creation'],
  ));
}
$dbDestination->commit();
