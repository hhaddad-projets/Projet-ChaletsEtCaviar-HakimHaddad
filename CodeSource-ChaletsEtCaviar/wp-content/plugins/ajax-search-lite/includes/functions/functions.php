<?php

if (!function_exists("w_isset_def")) {
    function w_isset_def(&$v, $d)
    {
        if (isset($v)) return $v;
        return $d;
    }
}

if (!function_exists("wpdreams_setval_or_getoption")) {
    function wpdreams_setval_or_getoption($options, $key, $def_key)
    {
        if (isset($options) && isset($options[$key]))
            return $options[$key];
        $def_options = get_option($def_key);
        return $def_options[$key];
    }
}

if (!function_exists("wpdreams_get_selected")) {
    function wpdreams_get_selected($option, $key) {
        return isset($option['selected-'.$key])?$option['selected-'.$key]:array();
    }
}

if (!function_exists("wpdreams_keyword_count_sort")) {
    function wpdreams_keyword_count_sort($first, $sec) {
        return $sec[1] - $first[1];
    }
}

if (!function_exists("wpdreams_get_stylesheet")) {
    function wpdreams_get_stylesheet($dir, $id, $style) {
        ob_start();
        include($dir."style.css.php");
        $out = ob_get_contents();
        ob_end_clean();
        if (isset($style['custom_css_special']) && isset($style['custom_css_selector'])
            && $style['custom_css_special'] != "") {
            $out.= " ".stripcslashes(str_replace('[instance]',
                    str_replace('THEID', $id, $style['custom_css_selector']),
                    $style['custom_css_special']));
        }
        return $out;
    }
}

if (!function_exists("wpdreams_update_stylesheet")) {
    function wpdreams_update_stylesheet($dir, $id, $style) {
        $out = wpdreams_get_stylesheet($dir, $id, $style);
        if (isset($style['css_compress']) && $style['css_compress'] == true)
            $out = wpdreams_css_compress($out);
        return @file_put_contents($dir."style".$id.".css", $out, FILE_TEXT);
    }
}

if (!function_exists("wpdreams_parse_params")) {
    function wpdreams_parse_params($params) {
        foreach ($params as $k=>$v) {
            $_tmp = explode('classname-', $k);
            if ($_tmp!=null && count($_tmp)>1) {
                ob_start();
                $c = new $v('0', '0', $params[$_tmp[1]]);
                $out = ob_get_clean();
                $params['selected-'.$_tmp[1]] = $c->getSelected();
            }
            $_tmp = null;
            $_tmp = explode('wpdfont-', $k);
            if ($_tmp!=null && count($_tmp)>1) {
                ob_start();
                $c = new $v('0', '0', $params[$_tmp[1]]);
                $out = ob_get_clean();
                $params['import-'.$_tmp[1]] = $c->getImport();
            }
        }
        return $params;
    }
}

if (!function_exists("wpdreams_admin_hex2rgb")) {
    function wpdreams_admin_hex2rgb($color)
    {
        if (strlen($color)>7) return $color;
        if (strlen($color)<3) return "rgba(0, 0, 0, 1)";
        if ($color[0] == '#')
            $color = substr($color, 1);
        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                $color[2].$color[3],
                $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return false;
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return "rgba(".$r.", ".$g.", ".$b.", 1)";
    }
}
if (!function_exists("wpdreams_four_to_string")) {
    function wpdreams_four_to_string($data) {
        // 1.Top 2.Bottom 3.Right 4.Left
        preg_match("/\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|/", $data, $matches);
        // 1.Top 3.Right 2.Bottom 4.Left
        return $matches[1]." ".$matches[3]." ".$matches[2]." ".$matches[4];
    }
}


if (!function_exists("wpdreams_box_shadow_css")) {
    function wpdreams_box_shadow_css($css) {
        $css = str_replace("\n", "", $css);
        preg_match("/box-shadow:(.*?)px (.*?)px (.*?)px (.*?)px (.*?);/", $css, $matches);
        $ci = $matches[5];
        $hlength = $matches[1];
        $vlength = $matches[2];
        $blurradius = $matches[3];
        $spread = $matches[4];
        $moz_blur = ($blurradius>2)?$blurradius - 2:0;
        if ($hlength==0 && $vlength==0 && $blurradius==0 && $spread==0) {
            echo "box-shadow: none;";
        } else {
            echo "box-shadow:".$hlength."px ".$vlength."px ".$moz_blur."px ".$spread."px ".$ci.";";
            echo "-webkit-box-shadow:".$hlength."px ".$vlength."px ".$blurradius."px ".$spread."px ".$ci.";";
            echo "-ms-box-shadow:".$hlength."px ".$vlength."px ".$blurradius."px ".$spread."px ".$ci.";";
        }
    }
}

if (!function_exists("wpdreams_gradient_css")) {
    function wpdreams_gradient_css($data, $print=true)
    {

        $data = str_replace("\n", "", $data);
        preg_match("/(.*?)-(.*?)-(.*?)-(.*)/", $data, $matches);

        if (!isset($matches[1]) || !isset($matches[2]) || !isset($matches[3])) {
            // Probably only 1 color..
            if ($print) echo "background: ".$data.";";
            return "background: ".$data.";";
        }

        $type = $matches[1];
        $deg = $matches[2];
        $color1 = wpdreams_admin_hex2rgb($matches[3]);
        $color2 = wpdreams_admin_hex2rgb($matches[4]);

        // Check for full transparency
        preg_match("/rgba\(.*?,.*?,.*?,[\s]*(.*?)\)/", $color1, $opacity1);
        preg_match("/rgba\(.*?,.*?,.*?,[\s]*(.*?)\)/", $color2, $opacity2);
        if (isset($opacity1[1]) && $opacity1[1] == "0" && isset($opacity2[1]) && $opacity2[1] == "0") {
            if ($print) echo "background: transparent;";
            return "background: transparent;";
        }

        ob_start();
        //compatibility
        /*if (strlen($color1)>7) {
          preg_match("/\((.*?)\)/", $color1, $matches);
          $colors = explode(',', $matches[1]);
          echo "background: rgb($colors[0], $colors[1], $colors[2]);";
        } else {
          echo "background: ".$color1.";";
        }   */
        //linear

        if ($type!='0' || $type!=0) {
            ?>
            background-image: linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -webkit-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -moz-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -o-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -ms-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            <?php
        } else {
            //radial
            ?>
            background-image: -moz-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -webkit-gradient(radial, center center, 0px, center center, 100%, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -webkit-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -o-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -ms-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: radial-gradient(ellipse at center,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            <?php
        }
        $out = ob_get_clean();
        if ($print) echo $out;
        return $out;
    }
}

if (!function_exists("wpdreams_gradient_css_rgba")) {
    function wpdreams_gradient_css_rgba($data, $print=true)
    {

        $data = str_replace("\n", "", $data);
        preg_match("/(.*?)-(.*?)-(.*?)-(.*)/", $data, $matches);

        if (!isset($matches[1]) || !isset($matches[2]) || !isset($matches[3])) {
            // Probably only 1 color..
            echo "background: ".$data.";";
            return;
        }

        $type = $matches[1];
        $deg = $matches[2];
        $color1 = wpdreams_admin_hex2rgb($matches[3]);
        $color2 = wpdreams_admin_hex2rgb($matches[4]);

        ob_start();
        //compatibility


        if ($type!='0' || $type!=0) {
            ?>linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>)<?php
        } else {
            //radial
            ?>radial-gradient(ellipse at center,  <?php echo $color1; ?>, <?php echo $color2; ?>)<?php
        }
        $out = ob_get_clean();
        if ($print) echo $out;
        return $out;
    }
}


if (!function_exists("wpdreams_border_width")) {
    function wpdreams_border_width($css)
    {
        $css = str_replace("\n", "", $css);

        preg_match("/border:(.*?)px (.*?) (.*?);/", $css, $matches);

        return $matches[1];

    }
}

if (!function_exists("wpdreams_width_from_px")) {
    function wpdreams_width_from_px($css)
    {
        $css = str_replace("\n", "", $css);

        preg_match("/(.*?)px/", $css, $matches);

        return $matches[1];

    }
}

if (!function_exists("wpdreams_x2")) {
    function wpdreams_x2($url)
    {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        return str_replace('.'.$ext, 'x2.'.$ext, $url);
    }
}

if (!function_exists("wpdreams_in_array_r")) {
    function wpdreams_in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wpdreams_in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists("wpdreams_css_compress")) {
    function wpdreams_css_compress ($code) {
        $code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
        $code = str_replace(array("\r\n", "\r", "\n", "\t", '    '), '', $code);
        $code = str_replace('{ ', '{', $code);
        $code = str_replace(' }', '}', $code);
        $code = str_replace('; ', ';', $code);
        return $code;
    }
}

if (!function_exists("wpdreams_get_all_taxonomies")) {
    function wpdreams_get_all_taxonomies() {
        $args = array(
            'public'   => true,
            '_builtin' => false

        );
        $output = 'names'; // or objects
        $operator = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies( $args, $output, $operator );
        return $taxonomies;
    }
}

if (!function_exists("wpdreams_get_all_terms")) {
    function wpdreams_get_all_terms() {
        $taxonomies = wpdreams_get_all_taxonomies();
        $terms = array();
        $_terms = array();
        foreach ($taxonomies as $taxonomy) {
            $_temp = get_terms($taxonomy, 'orderby=name');
            foreach ($_temp as $k=>$v)
                $terms[] = $v;
        }
        foreach ($terms as $k=>$v) {
            $_terms[$v->term_id] = $v;
        }
        return $_terms;
    }
}

if (!function_exists("wpdreams_get_all_term_ids")) {
    function wpdreams_get_all_term_ids() {
        $taxonomies = wpdreams_get_all_taxonomies();
        $terms = array();
        foreach ($taxonomies as $taxonomy) {
            $_temp = get_terms($taxonomy, 'orderby=name');
            foreach ($_temp as $k=>$v)
                $terms[] = $v->term_id;
        }
        return $terms;
    }
}

if (!function_exists("wpdreams_four_to_string")) {
    function wpdreams_four_to_string($data) {
        // 1.Top 2.Bottom 3.Right 4.Left
        preg_match("/\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|/", $data, $matches);
        // 1.Top 3.Right 2.Bottom 4.Left
        return $matches[1]." ".$matches[3]." ".$matches[2]." ".$matches[4];
    }
}

if (!function_exists("wpdreams_four_to_array")) {
    function wpdreams_four_to_array($data) {
        // 1.Top 2.Bottom 3.Right 4.Left
        preg_match("/\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|/", $data, $matches);
        // 1.Top 3.Right 2.Bottom 4.Left
        return array(
            "top" => $matches[1],
            "right" => $matches[3],
            "bottom" => $matches[2],
            "left" => $matches[4]
        );
    }
}

if (!function_exists("asl_generate_html_results")) {
    /**
     * Converts the results array to HTML code
     *
     * Since ASL 4.5 the results are returned as plain HTML codes instead of JSON
     * to allow templating. This function includes the needed template files
     * to generate the correct HTML code. Supports grouping.
     *
     * @since 4.5
     * @param $results
     * @param $s_options
     * @return string
     */
    function asl_generate_html_results($results, $s_options ) {
        $html = "";
        $theme_path = get_stylesheet_directory() . "/asl/";

        if (empty($results) || !empty($results['nores'])) {
            if (!empty($results['keywords'])) {
                $s_keywords = $results['keywords'];
                // Get the keyword suggestions template
                ob_start();
                if ( file_exists( $theme_path . "keyword-suggestions.php" ) )
                    include( $theme_path . "keyword-suggestions.php" );
                else
                    include(ASL_INCLUDES_PATH . "views/keyword-suggestions.php");
                $html .= ob_get_clean();
            } else {
                // No results at all.
                ob_start();
                if ( file_exists( $theme_path . "no-results.php" ) )
                    include( $theme_path . "no-results.php" );
                else
                    include(ASL_INCLUDES_PATH . "views/no-results.php");
                $html .= ob_get_clean();
            }
        } else {
            // Get the item HTML
            foreach($results as $k=>$r) {
                $asl_res_css_class = ' asl_r_' . $r->content_type . ' asl_r_' . $r->content_type . '_' .$r->id;
                if ( isset($r->post_type) ) {
                    $asl_res_css_class .= ' asl_r_' . $r->post_type;
                }
                ob_start();
                if ( file_exists( $theme_path . "result.php" ) )
                    include( $theme_path . "result.php" );
                else
                    include(ASL_INCLUDES_PATH . "views/result.php");
                $html .= ob_get_clean();
            }

        }
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $html);
    }
}

if (!function_exists('asl_icl_t')) {
    /* Ajax Search Lite wrapper for WPML and Polylang print */
    function asl_icl_t($name, $value, $strip_special = false) {
        $regex = '/[^\pL\s]+/u';
        if (function_exists('icl_register_string') && function_exists('icl_t')) {
            icl_register_string('ajax-search-lite', $name, $value);
            if ( $strip_special )
                return preg_replace($regex, ' ', stripslashes( icl_t('ajax-search-lite', $name, $value) ));
            return stripslashes( icl_t('ajax-search-lite', $name, $value) );
        }
        if (function_exists('pll_register_string') && function_exists('pll__')) {
            pll_register_string($name, $value, 'ajax-search-lite');
            if ( $strip_special )
                return preg_replace( $regex, ' ', stripslashes( pll__($value)) );
            return stripslashes( pll__($value) );
        }
        if (function_exists('qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            if ( $strip_special )
                return preg_replace( $regex, ' ', stripslashes( qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $value ) ) );
            return stripslashes( qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $value ) );
        }
        if ( $strip_special )
            return preg_replace( $regex, ' ', stripslashes( $value ) );
        return stripslashes( $value );
    }
}

if (!function_exists("wd_closetags")) {
	/**
	 * Close unclosed HTML tags
	 *
	 * @param $html
	 * @return string
	 */
	function wd_closetags( $html ) {
		$unpaired = array('hr', 'br', 'img');

		// put all opened tags into an array
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result[1];
		// remove unpaired tags
		if (is_array($openedtags) && count($openedtags)>0) {
			foreach ($openedtags as $k=>$tag) {
				if (in_array($tag, $unpaired))
					unset($openedtags[$k]);
			}
		} else {
			// Replace a possible un-closed tag from the end, 30 characters backwards check
			$html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
			return $html;
		}
		// put all closed tags into an array
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result[1];
		$len_opened = count ( $openedtags );
		// all tags are closed
		if( count ( $closedtags ) == $len_opened ) {
			// Replace a possible un-closed tag from the end, 30 characters backwards check
			$html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
			return $html;
		}
		$openedtags = array_reverse ( $openedtags );
		// close tags
		for( $i = 0; $i < $len_opened; $i++ ) {
			if ( !in_array ( $openedtags[$i], $closedtags ) ) {
				$html .= "</" . $openedtags[$i] . ">";
			} else {
				unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
			}
		}
		// Replace a possible un-closed tag from the end, 30 characters backwards check
		$html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
		return $html;
	}
}

if ( !function_exists('wpd_font') ) {
    /**
     * Helper method to be used before printing the font styles. Converts font families to apostrophed versions.
     *
     * @param $font
     * @return mixed
     */
    function wpd_font($font) {
        preg_match("/family:(.*?)$/", $font, $fonts);
        if (isset($fonts[1])) {
            $f = explode(',', stripslashes(str_replace(array('"', "'"), '', $fonts[1])) );
            foreach ($f as &$_f) {
                if (trim($_f) != 'inherit')
                    $_f = '"' . trim($_f) . '"';
                else
                    $_f = trim($_f);
            }
            $f = implode(',', $f);
            return preg_replace("/family:(.*?)$/", 'family:' . $f, $font);
        } else {
            return $font;
        }
    }
}

if (!function_exists("mysql_escape_mimic")) {
  function mysql_escape_mimic($inp) { 
      if(is_array($inp)) 
          return array_map(__METHOD__, $inp); 
  
      if(!empty($inp) && is_string($inp)) { 
          return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
      } 
  
      return $inp; 
  }
} 

if (!function_exists("wd_in_array_r")) {
  function wd_in_array_r($needle, $haystack, $strict = true) {
      foreach ($haystack as $item) {
          if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wd_in_array_r($needle, $item, $strict))) {
              return true;
          }
      }
  
      return false;
  }
}

if (!function_exists("wd_substr_at_word")) {
  function wd_substr_at_word($text, $length) {
      if (strlen($text) <= $length) return $text;
      $blogCharset = get_bloginfo('charset');
      $charset = $blogCharset !== '' ? $blogCharset : 'UTF-8';
      $s = mb_substr($text, 0, $length, $charset);
      return mb_substr($s, 0, strrpos($s, ' '), $charset);
  }
}

if (!function_exists("wpdreams_ismobile")) {
  function wpdreams_ismobile() {
    $is_mobile = '0';    
    if(preg_match('/(android|iphone|ipad|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
        $is_mobile=1;  
    if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
        $is_mobile=1;  
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');
    
    if(in_array($mobile_ua,$mobile_agents))
        $is_mobile=1;
    
    if (isset($_SERVER['ALL_HTTP'])) {
        if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) 
            $is_mobile=1;
    }    
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) 
        $is_mobile=0;
    return $is_mobile;
  }
}
if (!function_exists("current_page_url")) {  
  function current_page_url() {
  	$pageURL = 'http';
  	if( isset($_SERVER["HTTPS"]) ) {
  		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
  	}
  	$pageURL .= "://";
  	if ($_SERVER["SERVER_PORT"] != "80") {
  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
  	} else {
  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  	}
  	return $pageURL;
  }  
} 
if (!function_exists("wpdreams_hex2rgb")) {  
  function wpdreams_hex2rgb($color)
  {
      if (strlen($color)>7) return $color;
      if (strlen($color)<3) return "0, 0, 0";
      if ($color[0] == '#')
          $color = substr($color, 1);
      if (strlen($color) == 6)
          list($r, $g, $b) = array($color[0].$color[1],
                                   $color[2].$color[3],
                                   $color[4].$color[5]);
      elseif (strlen($color) == 3)
          list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
      else
          return false;
      $r = hexdec($r); $g = hexdec($g); $b = hexdec($b); 
      return $r.", ".$g.", ".$b;
  }  
}

if (!function_exists("wpdreams_rgb2hex")) {
    function wpdreams_rgb2hex($color)
    {
        if (strlen($color)>7) {
          preg_match("/.*?\((\d+), (\d+), (\d+).*?/", $color, $c);
          if (is_array($c) && count($c)>3) {
             $color = "#".sprintf("%02X", $c[1]);
             $color .= sprintf("%02X", $c[2]);
             $color .= sprintf("%02X", $c[3]);
          }
        }
        return $color;
    }
} 

if (!function_exists("get_content_w")) {  
  function get_content_w($id)
  {
      $my_postid = $id;
      $content_post = get_post($my_postid);
      $content = $content_post->post_content;
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
  }  
}

if (!function_exists("wpdreams_utf8safeencode")) {  
  function wpdreams_utf8safeencode($s, $delimiter)
  {
    $convmap= array(0x0100, 0xFFFF, 0, 0xFFFF);
    return $delimiter."_".base64_encode(mb_encode_numericentity($s, $convmap, 'UTF-8'));
  }  
}

if (!function_exists("wpdreams_utf8safedecode")) {  
  function wpdreams_utf8safedecode($s, $delimiter)
  {
    if (strpos($s, $delimiter)!=0) return $s;
    $convmap= array(0x0100, 0xFFFF, 0, 0xFFFF);
    $_s = explode($delimiter."_", $s);
    return base64_decode(mb_decode_numericentity($s[1], $convmap, 'UTF-8'));
  }  
}

if (!function_exists("postval_or_getoption")) {  
  function postval_or_getoption($option)
  {
    if (isset($_POST) && isset($_POST[$option]))
      return $_POST[$option];
    return get_option($option);
  }  
}

if (!function_exists("setval_or_getoption")) {  
  function setval_or_getoption($options, $key)
  {
    if (isset($options) && isset($options[$key]))
      return $options[$key];
    $def_options = get_option('asl_defaults');
    if (isset($def_options[$key]))
      return $def_options[$key];
    else
      return "";
  }  
}

if (!function_exists("wpdreams_get_image_from_content")) {
    /**
     * Gets an image from the HTML content
     *
     * @param $content
     * @param int $number
     * @return bool|string
     */
    function wpdreams_get_image_from_content($content, $number = 0) {
        if ($content == "" || !class_exists('domDocument'))
            return false;

        $dom = new domDocument;
        if ( function_exists('mb_convert_encoding') )
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        else
            @$dom->loadHTML($content);
        $dom->preserveWhiteSpace = false;
        @$images = $dom->getElementsByTagName('img');
        if ($images->length > 0) {
            if ($images->length > $number) {
                $im = $images->item($number)->getAttribute('src');
            } else {
                $im = $images->item(0)->getAttribute('src');
            }
            return $im;
        } else {
            return false;
        }
    }
}

if (!function_exists("wpdreams_on_backend_page")) {  
  function wpdreams_on_backend_page($pages)
  {
    if (isset($_GET) && isset($_GET['page'])) {
        return in_array($_GET['page'] ,$pages);
    }
    return false;
  }  
}

if (!function_exists("wd_in_array_r")) {
  function wd_in_array_r($needle, $haystack, $strict = true) {
      foreach ($haystack as $item) {
          if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wd_in_array_r($needle, $item, $strict))) {
              return true;
          }
      }
  
      return false;
  }
}

if (!function_exists("wpdreams_on_backend_page")) {
    /**
     * @param $pages
     * @return bool
     */
    function wpdreams_on_backend_page($pages)
    {
        if (isset($_GET) && isset($_GET['page'])) {
            return in_array($_GET['page'] ,$pages);
        }
        return false;
    }
}

if (!function_exists("wpdreams_on_backend_post_editor")) {
    /**
     * @return bool
     */
    function wpdreams_on_backend_post_editor() {
        $current_url = current_page_url();
        return (strpos($current_url, 'post-new.php')!==false ||
            strpos($current_url, 'post.php')!==false);
    }
}

if (!function_exists("wpdreams_get_blog_list")) {
  function wpdreams_get_blog_list( $start = 0, $num = 10, $deprecated = '' ) {
  
  	global $wpdb;
    if (!isset($wpdb->blogs)) return array();
  	$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC", $wpdb->siteid), ARRAY_A );
  
  	foreach ( (array) $blogs as $details ) {
  		$blog_list[ $details['blog_id'] ] = $details;
  		$blog_list[ $details['blog_id'] ]['postcount'] = $wpdb->get_var( "SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix( $details['blog_id'] ). "posts WHERE post_status='publish' AND post_type='post'" );
  	}
  	unset( $blogs );
  	$blogs = $blog_list;
  
  	if ( false == is_array( $blogs ) )
  		return array();
  
  	if ( $num == 'all' )
  		return array_slice( $blogs, $start, count( $blogs ) );
  	else
  		return array_slice( $blogs, $start, $num );
  }
}

if (!function_exists('asp_woo_version_check')) {
    function asp_woo_version_check($version = '3.0') {
        if (class_exists('WooCommerce')) {
            global $woocommerce;
            if (isset($woocommerce, $woocommerce->version) &&
                version_compare($woocommerce->version, $version, ">=")
            ) {
                return true;
            }
        }

        return false;
    }
}

//----------------------------------------------------------------------------------------------------------------------
// 6. NON-AJAX RESULTS
//----------------------------------------------------------------------------------------------------------------------

if ( !class_exists("asl_dummyPost") )  {
    /**
     * Class asl_dummyPost
     *
     * A default class to instantiate to generate post like results.
     */
    class asl_dummyPost {

        public $ID = -10;
        public $post_title = "";
        public $post_author = "";
        public $post_name = "";
        public $post_type = "post";         // Everything unknown is going to be a post
        public $post_date = "";             // Format: 0000-00-00 00:00:00
        public $post_date_gmt = "";         // Format: 0000-00-00 00:00:00
        public $post_content = "";          // The full content of the post
        public $post_excerpt = "";          // User-defined post excerpt
        public $post_status = "publish";    // See get_post_status for values
        public $comment_status = "closed";  // Returns: { open, closed }
        public $ping_status = "closed";     // Returns: { open, closed }
        public $post_password = "";         // Returns empty string if no password
        public $post_parent = 0;            // Parent Post ID (default 0)
        public $post_modified = "";         // Format: 0000-00-00 00:00:00
        public $post_modified_gmt = "";     // Format: 0000-00-00 00:00:00
        public $comment_count = 0;          // Number of comments on post (numeric string)
        public $menu_order = 0;             // Order value as set through page-attribute when enabled (numeric string. Defaults to 0)
        public $guid = "";
        public $asl_guid;

        function __construct() {}
    }
}

if ( !function_exists("asl_results_to_wp_obj") ) {
    /**
     * Converts ajax results from Ajax Search Pro to post like objects to be displayable
     * on the regular search results page.
     *
     * @param $results
     * @param int $from
     * @param string $count
     * @return array
     */
    function asl_results_to_wp_obj($results, $from = 0, $count = "all") {
        if (empty($results))
            return array();

        if ($count == "all")
            $results_slice = array_slice($results, $from);
        else
            $results_slice = array_slice($results, $from, $count);

        if (empty($results_slice))
            return array();

        $wp_res_arr = array();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');

        $current_date = date($date_format . " " . $time_format, time());

        foreach ($results_slice as $r) {

            if (is_multisite())
                switch_to_blog($r->blogid);

            if ( !isset($r->content_type) ) continue;

            switch ($r->content_type) {
                case "pagepost":
                    $res = get_post($r->id);
                    break;
                case "blog":
                    $res = new asl_dummyPost();
                    $res->post_title = $r->title;
                    $res->asl_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $current_date;
                    break;
                case "bp_group":
                    $res = new asl_dummyPost();
                    $res->post_title = $r->title;
                    $res->asl_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $r->date;
                    break;
                case "bp_activity":
                    $res = new asl_dummyPost();
                    $res->post_title = $r->title;
                    $res->asl_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $r->date;
                    break;
                case "comment":
                    $res = get_post($r->post_id);
                    if (isset($res->post_title)) {
                        $res->post_title = $r->title;
                        $res->asl_guid = $r->link;
                        $res->post_content = $r->content;
                        $res->post_excerpt = $r->content;
                    }
                    break;
                case "term":
                    $res = new asl_dummyPost();
                    $res->post_title = $r->title;
                    $res->asl_guid = $r->link;
                    $res->guid = $r->link;
                    $res->post_date = $current_date;
                    break;
                case "user":
                    $res = new asl_dummyPost();
                    $res->post_title = $r->title;
                    $res->asl_guid = $r->link;
                    $res->guid = $r->link;
                    $res->post_date = $current_date;
                    break;
            }

            if (!empty($res)) {
                $res = apply_filters("asl_regular_search_result", $res);
                $wp_res_arr[] = $res;
            }

            if (is_multisite())
                restore_current_blog();
        }

        return $wp_res_arr;
    }
}