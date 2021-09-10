<?php

function turkcetarih_formati($format, $datetime = 'now'){
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
