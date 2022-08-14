<?php

	if ( ! function_exists( 'wpartisan_set_no_found_rows' ) ) :
		function wpartisan_set_no_found_rows( \WP_Query $wp_query ) {
		$wp_query->set( 'no_found_rows', true );
		}
		endif;
		add_filter( 'pre_get_posts', 'wpartisan_set_no_found_rows', 10, 1 );
		if ( ! function_exists( 'sayfa_gecis_hizlandir' ) ) :
		function sayfa_gecis_hizlandir( $clauses, \WP_Query $wp_query ) {
		if ( $wp_query->is_singular() ) {
		return $clauses;
		}
		global $wpdb;
		$where = isset( $clauses[ 'where' ] ) ? $clauses[ 'where' ] : '';
		$join = isset( $clauses[ 'join' ] ) ? $clauses[ 'join' ] : '';
		$distinct = isset( $clauses[ 'distinct' ] ) ? $clauses[ 'distinct' ] : '';
		$wp_query->found_posts = $wpdb->get_var( "SELECT $distinct COUNT(*) FROM {$wpdb->posts} $join WHERE 1=1 $where" );
		$posts_per_page = ( ! empty( $wp_query->query_vars['posts_per_page'] ) ? absint( $wp_query->query_vars['posts_per_page'] ) : absint( get_option( 'posts_per_page' ) ) );
		$wp_query->max_num_pages = ceil( $wp_query->found_posts / $posts_per_page );
		return $clauses;
		}
		endif;
		add_filter( 'posts_clauses', 'sayfa_gecis_hizlandir', 10, 2 );


function turkcetarih_formati($format, $datetime = 'now'){
    date_default_timezone_set('Europe/Istanbul');
    $z = date("$format", strtotime($datetime));
    $gun_dizi = array(
        'Monday'    => 'Pazartesi',
        'Tuesday'   => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday'  => 'Perşembe',
        'Friday'    => 'Cuma',
        'Saturday'  => 'Cumartesi',
        'Sunday'    => 'Pazar',
        'January'   => 'Ocak',
        'February'  => 'Şubat',
        'March'     => 'Mart',
        'April'     => 'Nisan',
        'May'       => 'Mayıs',
        'June'      => 'Haziran',
        'July'      => 'Temmuz',
        'August'    => 'Ağustos',
        'September' => 'Eylül',
        'October'   => 'Ekim',
        'November'  => 'Kasım',
        'December'  => 'Aralık',
        'Mon'       => 'Pts',
        'Tue'       => 'Sal',
        'Wed'       => 'Çar',
        'Thu'       => 'Per',
        'Fri'       => 'Cum',
        'Sat'       => 'Cts',
        'Sun'       => 'Paz',
        'Jan'       => 'Oca',
        'Feb'       => 'Şub',
        'Mar'       => 'Mar',
        'Apr'       => 'Nis',
        'Jun'       => 'Haz',
        'Jul'       => 'Tem',
        'Aug'       => 'Ağu',
        'Sep'       => 'Eyl',
        'Oct'       => 'Eki',
        'Nov'       => 'Kas',
        'Dec'       => 'Ara',
    );
    foreach($gun_dizi as $en => $tr){
        $z = str_replace($en, $tr, $z);
    }
    if(strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
    return $z. ' '.date('H:i:s');
}

function yazi_ozet($harf_sayisi) {
    $temp_str = substr(strip_shortcodes(strip_tags(get_the_content())),0,$harf_sayisi);
   $temp_parts = explode(" ",$temp_str);
   $temp_parts[(count($temp_parts) - 1)] = '';
   if(strlen(strip_tags(get_the_content())) > 125) {
   return implode(" ",$temp_parts).'...';
   } else {
   return implode(" ",$temp_parts);
   }
   }

   function defer_parsing_of_js( $url ) {
    if ( is_user_logged_in() ) return $url; //don't break WP Admin
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return str_replace( ' src', ' defer src', $url );
}
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );

if( !function_exists( "wp_temizlik" ) ) {  
function wp_temizlik() {
// wp header temizlik
remove_action( 'wp_head', 'feed_links_extra', 3 );  // Category Feeds silme
remove_action( 'wp_head', 'feed_links', 2 );        // Post ve Comment Feeds silme
remove_action( 'wp_head', 'rsd_link' );    // EditURI link silme
remove_action( 'wp_head', 'wlwmanifest_link' );     // Windows Live Writer silme
remove_action( 'wp_head', 'index_rel_link' );       // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );   // previous link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );    // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Önceki sonraki yazı silme
remove_action( 'wp_head', 'wp_generator' );// WP version silme
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10); // application/json+oembed silme
remove_action('welcome_panel', 'wp_welcome_panel'); //hoşgeldin admin panel silme
remove_action('wp_head', 'print_emoji_detection_script', 7); //js emoji silme
remove_action('wp_print_styles', 'print_emoji_styles'); //style emoji silme
remove_action('wp_head', 'wp_resource_hints', 2); //<link rel='dns-prefetch' href='//s.w.org' /> silme
remove_action('wp_head', 'rest_output_link_wp_head', 10); //https://api.w.org/ silme
remove_filter('comment_text','wpautop',30); // p tagı silme
remove_filter( 'the_content', 'wpautop' );   // p tagı silme
wp_deregister_script( 'wp-embed' ); // wp-embed kodları silme
wp_deregister_script( 'imagesloaded' );	
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
if (!is_admin()) {
  //  wp_deregister_script('jquery');
    wp_register_script('jquery', false);
}
remove_theme_support( 'title-tag' ); //Title Özgürlük
remove_action( 'wp_head', 'rel_canonical' ); //rel kaldırma
}
}
add_action( 'init', 'wp_temizlik' );

function relcanonical() {
$parcala = explode('?',$_SERVER['REQUEST_URI']);
echo '<link rel="canonical" href="'.site_url().''.$parcala[0].'">
';
echo '<link rel="sitemap" type="application/xml" title="sitemap" href="'.site_url('/').'sitemap.xml" />
';
}
add_action( 'wp_head', 'relcanonical',100);
function wp_js_version_kaldırma( $src ) {
    if ( strpos( $src, 'ver=' ) )
    $src = remove_query_arg( 'ver', $src );
    return $src;
    }
    add_filter( 'style_loader_src', 'wp_js_version_kaldırma', 9999 );
    add_filter( 'script_loader_src', 'wp_js_version_kaldırma', 9999 );
  
    function tarih_fonk( $from, $to = '' ) {
      if ( empty($to) )
        $to = time();
      $diff = (int) abs($to - $from);
      if ($diff <= 3600) {
        $mins = round($diff / 60);
        if ($mins <= 1) {
          $mins = 1;
        }
    
        if ($mins == 1) {
          $since = sprintf(__('%s min ago', 'vidlife'), $mins);
        } else {
          $since = sprintf(__('%s mins ago', 'vidlife'), $mins);
        }
      } else if (($diff <= 86400) && ($diff > 3600)) {
        $hours = round($diff / 3600);
        if ($hours <= 1) {
          $hours = 1;
        }
        
        if ($hours == 1) {
          $since = sprintf(__('%s hour ago', 'vidlife'), $hours);
        } else {
          $since = sprintf(__('%s hours ago', 'vidlife'), $hours);
        }
      } else if ($diff >= 86400 && $diff <= 31536000) {
        $days = round($diff / 86400);
        if ($days <= 1) {
          $days = 1;
        }
    
        if ($days == 1) {
          $since = sprintf(__('%s day ago', 'vidlife'), $days);
        } else {
          $since = sprintf(__('%s days ago', 'vidlife'), $days);
        }
      } else {
        $since = get_the_date();
      }
      return $since;
    }
    
    /* Wp İzlenme Metabox ------------------------------------*/
    function izlenme() {
        $count_key = 'izlenme';
        $count = get_post_meta(get_the_ID(), $count_key, true);
        if($count==''){
            $count = 0;
            delete_post_meta(get_the_ID(), $count_key);
            add_post_meta(get_the_ID(), $count_key, '1');
        }else{
            $count++;
            update_post_meta(get_the_ID(), $count_key, $count);
        }
    }
    function youtube_id($in, $to_num = false, $pad_up = false, $pass_key = null)
    {
      $out   =   '';
      $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $base  = strlen($index);
    
      if ($pass_key !== null) {
        // Although this function's purpose is to just make the
        // ID short - and not so much secure,
        // with this patch by Simon Franz (https://blog.snaky.org/)
        // you can optionally supply a password to make it harder
        // to calculate the corresponding numeric ID
    
        for ($n = 0; $n < strlen($index); $n++) {
          $i[] = substr($index, $n, 1);
        }
    
        $pass_hash = hash('sha256',$pass_key);
        $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
    
        for ($n = 0; $n < strlen($index); $n++) {
          $p[] =  substr($pass_hash, $n, 1);
        }
    
        array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
      }
    
      if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $len = strlen($in) - 1;
    
        for ($t = $len; $t >= 0; $t--) {
          $bcp = bcpow($base, $len - $t);
          @$out = (int)$out + (int)strpos($index, substr($in, $t, 1)) * (int)$bcp;
        }
    
        if (is_numeric($pad_up)) {
          $pad_up--;
    
          if ($pad_up > 0) {
            $out -= pow($base, $pad_up);
          }
        }
      } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
          $pad_up--;
    
          if ($pad_up > 0) {
            $in += pow($base, $pad_up);
          }
        }
    
        for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
          $bcp = bcpow($base, $t);
          $a   = floor($in / $bcp) % $base;
          $out = $out . substr($index, $a, 1);
          $in  = $in - ($a * $bcp);
        }
      }
    
      return $out;
    }  

  function ilkBuyuk($str) {
    $str = str_replace('i', 'İ',$str);
    $str = str_replace('I', 'ı',$str);
    return ltrim(mb_convert_case($str, MB_CASE_TITLE, 'UTF-8'));
    }
function curl($url) {
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
function CleanLink($str, $options = array())
{
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function curl($url) {
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}

function ucwords_tr($gelen){

    $sonuc='';
    $kelimeler=explode(" ", $gelen);
  
    foreach ($kelimeler as $kelime_duz){
  
      $kelime_uzunluk=strlen($kelime_duz);
      $ilk_karakter=mb_substr($kelime_duz,0,1,'UTF-8');
  
      if($ilk_karakter=='Ç' or $ilk_karakter=='ç'){
        $ilk_karakter='Ç';
      }elseif ($ilk_karakter=='Ğ' or $ilk_karakter=='ğ') {
        $ilk_karakter='Ğ';
      }elseif($ilk_karakter=='I' or $ilk_karakter=='ı'){
        $ilk_karakter='I';
      }elseif ($ilk_karakter=='İ' or $ilk_karakter=='i'){
        $ilk_karakter='İ';
      }elseif ($ilk_karakter=='Ö' or $ilk_karakter=='ö'){
        $ilk_karakter='Ö';
      }elseif ($ilk_karakter=='Ş' or $ilk_karakter=='ş'){
        $ilk_karakter='Ş';
      }elseif ($ilk_karakter=='Ü' or $ilk_karakter=='ü'){
        $ilk_karakter='Ü';
      }else{
        $ilk_karakter=strtoupper($ilk_karakter);
      }
  
      $digerleri=mb_substr($kelime_duz,1,$kelime_uzunluk,'UTF-8');
      $sonuc.=$ilk_karakter.kucuk_yap($digerleri).' ';
  
    }
  
    $son=trim(str_replace('  ', ' ', $sonuc));
    return $son;
  
  }
  
  function kucuk_yap($gelen){
  
    $gelen=str_replace('Ç', 'ç', $gelen);
    $gelen=str_replace('Ğ', 'ğ', $gelen);
    $gelen=str_replace('I', 'ı', $gelen);
    $gelen=str_replace('İ', 'i', $gelen);
    $gelen=str_replace('Ö', 'ö', $gelen);
    $gelen=str_replace('Ş', 'ş', $gelen);
    $gelen=str_replace('Ü', 'ü', $gelen);
    $gelen=strtolower($gelen);
  
    return $gelen;
  }


/* thumbnail title change to post title */
add_filter( 'post_thumbnail_html', 'meks_post_thumbnail_alt_change', 10, 5 );

/* Function which will replace alt atribute to post title */
function meks_post_thumbnail_alt_change( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	$post_title = get_the_title();
	$html = preg_replace( '/(alt=")(.*?)(")/i', '$1'.esc_attr( $post_title ).'$3', $html );

	return $html;

}

	function http_to_https(){
		if ( (isset($_SERVER['HTTP_X_FORWARDED_PORT'] ) && ( '443' == $_SERVER['HTTP_X_FORWARDED_PORT'] ))
	|| (isset($_SERVER['HTTP_CF_VISITOR']) && $_SERVER['HTTP_CF_VISITOR'] == '{"scheme":"https"}')) {
	$_SERVER['HTTPS'] = 'on';
	}
	if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
	$_SERVER['HTTPS'] == 1) ||  
	isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
	$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
	{
	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);
	exit();
	}
	}
	add_action( 'init', 'http_to_https' );

   function klasik_widget() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'klasik_widget' );
