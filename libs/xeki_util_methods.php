<?php
/**
 * xeki FRAMEWORK : xeki util methods
 * xeki.io
 * Version 1.0.1
 */


/**
 * Nice echo in xeki just use d($array); and debug!
 * @param $info
 */
function d($info)
{
    if (PHP_SAPI === 'cli'){
        echo "\n";
        print_r($info);
        echo "\n";
    }
    else{
        echo '<pre>';
        print_r($info);
        echo '</pre>';
    }


    // for find prints
//    echo '<pre>';
//    print_r(debug_backtrace());
//    echo '</pre>';
}

/**
 * Echo for console
 * @param $info string or array for print in console
 */
function f($info)
{
    print_r($info);
    echo "\n";
}

/**
 * Echo error for console
 * @param $info
 */
function e($info)
{
    print_r($info);
    echo "\n";
    die();
}

/**
 * Secure redirect just use xeki_redirect allways works :)
 * @param $to url to redirect
 */
function xeki_redirect($to){
    global $AG_BASE;
    if (strpos($to, 'http://') !== false || strpos($to, 'https://') !== false) {
        header('Location: ' . $to);
        echo '<meta http-equiv="refresh" content="0;URL="' . $to . '"/>';
        echo '<script>window.location.replace("' . $to . '");</script>';
        die();
    }
    header('Location: ' . $AG_BASE . $to);
    echo '<meta http-equiv="refresh" content="0;URL="' . $AG_BASE . $to . '"/>';
    echo '<script>window.location.replace("' . $AG_BASE . $to . '");</script>';
    die();
}

/**
 * Verify if is mobile the system
 * @return int
 */
function xeki_isMobile()
{   
    
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

/**
 * Verify if is mobile the system
 * @return int
 */
function xeki_isMobile_ios()
{
    return preg_match("/(webos|iPod|iPhone|iPad)/i", $_SERVER["HTTP_USER_AGENT"]);
}

/**
 * Remove tags from html its work for show clean text for specific codes
 * Examples TODO create examples
 * @param $text text to clean
 * @param string $tags tags to clean
 * @param bool $invert
 * @return mixed text clean
 */
function xeki_strip_tags($text, $tags = '', $invert = FALSE){

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) AND count($tags) > 0) {
        if ($invert == FALSE) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
    } elseif ($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
}

/**
 * @param $str
 * @return string
 */
function cleanToPrint($str)
{
    $str = force_utf8($str);
    return html_entity_decode($str, ENT_COMPAT, 'UTF-8');
}

/**
 * @param $str
 * @return string
 */
function clear_str($str)
{
    return htmlentities($str);

}

// change a string with acoutes and tildes to no acutes and no tildes
function to_tildes($str){
    $new_string=force_utf8($str); # force text to utf8
    if($new_string)$str=$new_string;
    $str = utf8_decode($str);
    $str = str_replace(
        array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),
        array(chr(0xE1),chr(0xE9),chr(0xED),chr(0xF3),chr(0xFA),chr(0xF1),chr(0xC1),chr(0xC9),chr(0xCD),chr(0xD3),chr(0xDA),chr(0xD1)),
        $str);
    return $str;
}
function to_no_tildes($str){

    $new_string=force_utf8($str); # force text to utf8
    if($new_string)$str=$new_string;
    $str = utf8_decode($str);
    $str = str_replace(
        array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),
        array(chr(0xE1),chr(0xE9),chr(0xED),chr(0xF3),chr(0xFA),chr(0xF1),chr(0xC1),chr(0xC9),chr(0xCD),chr(0xD3),chr(0xDA),chr(0xD1)),
        $str);
    # array("a","e","i","o","u","n","A","E","I","O","U","N") tildes to no tildes
    $str = str_replace(array(chr(0xE1),chr(0xE9),chr(0xED),chr(0xF3),chr(0xFA),chr(0xF1),chr(0xC1),chr(0xC9),chr(0xCD),chr(0xD3),chr(0xDA),chr(0xD1)),
                       array("a","e","i","o","u","n","A","E","I","O","U","N"),
                       $str);

    $str = str_replace(array(chr(0xA1),chr(0xAB),chr(0xBB),chr(0xBF)),
                       array("","","",""),
                       $str);
    return $str;

}
function to_acutes_tildes($str){

    $chr_map = array(
        "&Agrave;"=>"\xC3\x80",
        "&Aacute;"=>"\xC3\x81",
        "&Acirc;"=>"\xC3\x82",
        "&Atilde;"=>"\xC3\x83",
        "&Auml;"=>"\xC3\x84",
        "&Aring;"=>"\xC3\x85",
        "&AElig;"=>"\xC3\x86",
        "&Ccedil;"=>"\xC3\x87",
        "&Egrave;"=>"\xC3\x88",
        "&Eacute;"=>"\xC3\x89",
        "&Ecirc;"=>"\xC3\x8A",
        "&Euml;"=>"\xC3\x8B",
        "&Igrave;"=>"\xC3\x8C",
        "&Iacute;"=>"\xC3\x8D",
        "&Icirc;"=>"\xC3\x8E",
        "&Iuml;"=>"\xC3\x8F",
        "&ETH;"=>"\xC3\x90",
        "&Ntilde;"=>"\xC3\x91",
        "&Ograve;"=>"\xC3\x92",
        "&Oacute;"=>"\xC3\x93",
        "&Ocirc;"=>"\xC3\x94",
        "&Otilde;"=>"\xC3\x95",
        "&Ouml;"=>"\xC3\x96",
        "&times;"=>"\xC3\x97",
        "&Oslash;"=>"\xC3\x98",
        "&Ugrave;"=>"\xC3\x99",
        "&Uacute;"=>"\xC3\x9A",
        "&Ucirc;"=>"\xC3\x9B",
        "&Uuml;"=>"\xC3\x9C",
        "&Yacute;"=>"\xC3\x9D",
        "&THORN;"=>"\xC3\x9E",
        "&szlig;"=>"\xC3\x9F",
        "&agrave;"=>"\xC3\xA0",
        "&aacute;"=>"\xC3\xA1",
        "&acirc;"=>"\xC3\xA2",
        "&atilde;"=>"\xC3\xA3",
        "&auml;"=>"\xC3\xA4",
        "&aring;"=>"\xC3\xA5",
        "&aelig;"=>"\xC3\xA6",
        "&ccedil;"=>"\xC3\xA7",
        "&egrave;"=>"\xC3\xA8",
        "&eacute;"=>"\xC3\xA9",
        "&ecirc;"=>"\xC3\xAA",
        "&euml;"=>"\xC3\xAB",
        "&igrave;"=>"\xC3\xAC",
        "&iacute;"=>"\xC3\xAD",
        "&icirc;"=>"\xC3\xAE",
        "&iuml;"=>"\xC3\xAF",
        "&eth;"=>"\xC3\xB0",
        "&ntilde;"=>"\xC3\xB1",
        "&ograve;"=>"\xC3\xB2",
        "&oacute;"=>"\xC3\xB3",
        "&ocirc;"=>"\xC3\xB4",
        "&otilde;"=>"\xC3\xB5",
        "&ouml;"=>"\xC3\xB6",
        "&divide;"=>"\xC3\xB7",
        "&oslash;"=>"\xC3\xB8",
        "&ugrave;"=>"\xC3\xB9",
        "&uacute;"=>"\xC3\xBA",
        "&ucirc;"=>"\xC3\xBB",
        "&uuml;"=>"\xC3\xBC",
        "&yacute;"=>"\xC3\xBD",
        "&thorn;"=>"\xC3\xBE",
        "&yuml;"=>"\xC3\xBF",
        '&nbsp;'=>"\xC2\xA0",
        '&iexcl;'=>"\xC2\xA1",
        '&cent;'=>"\xC2\xA2",
        '&pound;'=>"\xC2\xA3",
        '&curren;'=>"\xC2\xA4",
        '&yen;'=>"\xC2\xA5",
        '&brvbar;'=>"\xC2\xA6",
        '&sect;'=>"\xC2\xA7",
        '&uml;'=>"\xC2\xA8",
        '&copy;'=>"\xC2\xA9",
        '&ordf;'=>"\xC2\xAA",
        '&laquo;'=>"\xC2\xAB",
        '&not;'=>"\xC2\xAC",
        '&shy;'=>"\xC2\xAD",
        '&reg;'=>"\xC2\xAE",
        '&macr;'=>"\xC2\xAF",
        '&deg;'=>"\xC2\xB0",
        '&plusmn;'=>"\xC2\xB1",
        '&sup2;'=>"\xC2\xB2",
        '&sup3;'=>"\xC2\xB3",
        '&acute;'=>"\xC2\xB4",
        '&micro;'=>"\xC2\xB5",
        '&para;'=>"\xC2\xB6",
        '&middot;'=>"\xC2\xB7",
        '&cedil;'=>"\xC2\xB8",
        '&sup1;'=>"\xC2\xB9",
        '&ordm;'=>"\xC2\xBA",
        '&raquo;'=>"\xC2\xBB",
        '&frac14;'=>"\xC2\xBC",
        '&frac12;'=>"\xC2\xBD",
        '&frac34;'=>"\xC2\xBE",
        '&iquest;'=>"\xC2\xBF",
        "\xC2\x89"=>"",
        "\xC2\x9A"=>"",
        "\xC5\xBE"=>"",
        "\xC5\xB8"=>"",
        chr(0xA1)=>"",
        chr(0xAB)=>"",
        chr(0xBB)=>"",
        chr(0xBF)=>"",
    );

//    $chr = array_keys($chr_map); // but: for efficiency you should
//    $rpl = array_values($chr_map); // pre-calculate these two arrays
//    $str = str_replace($chr, $rpl, $str);


    $str = html_entity_decode($str);
    $str = utf8_decode($str);

    return $str;
}

function to_tildes_acutes($str)
{
    $chr_map = array(
        // html codes
        '¿' => "&iquest;",
        'á' => "&aacute;",
        'é' => "&eacute;",
        'í' => "&iacute;",
        'ó' => "&oacute;",
        'ú' => "&uacute;",
        'Á' => "&Aacute;",
        'É' => "&Eacute;",
        'Í' => "&Iacute;",
        'Ó' => "&Oacute;",
        'Ú' => "&Uacute;",
        'ñ' => "&ntilde;",
        'Ñ' => "&Ntilde;",

        "\xC3\x80"=>"&Agrave;",
        "\xC3\x81"=>"&Aacute;",
        "\xC3\x82"=>"&Acirc;",
        "\xC3\x83"=>"&Atilde;",
        "\xC3\x84"=>"&Auml;",
        "\xC3\x85"=>"&Aring;",
        "\xC3\x86"=>"&AElig;",
        "\xC3\x87"=>"&Ccedil;",
        "\xC3\x88"=>"&Egrave;",
        "\xC3\x89"=>"&Eacute;",
        "\xC3\x8A"=>"&Ecirc;",
        "\xC3\x8B"=>"&Euml;",
        "\xC3\x8C"=>"&Igrave;",
        "\xC3\x8D"=>"&Iacute;",
        "\xC3\x8E"=>"&Icirc;",
        "\xC3\x8F"=>"&Iuml;",
        "\xC3\x90"=>"&ETH;",
        "\xC3\x91"=>"&Ntilde;",
        "\xC3\x92"=>"&Ograve;",
        "\xC3\x93"=>"&Oacute;",
        "\xC3\x94"=>"&Ocirc;",
        "\xC3\x95"=>"&Otilde;",
        "\xC3\x96"=>"&Ouml;",
        "\xC3\x97"=>"&times;",
        "\xC3\x98"=>"&Oslash;",
        "\xC3\x99"=>"&Ugrave;",
        "\xC3\x9A"=>"&Uacute;",
        "\xC3\x9B"=>"&Ucirc;",
        "\xC3\x9C"=>"&Uuml;",
        "\xC3\x9D"=>"&Yacute;",
        "\xC3\x9E"=>"&THORN;",
        "\xC3\x9F"=>"&szlig;",
        "\xC3\xA0"=>"&agrave;",
        "\xC3\xA1"=>"&aacute;",
        "\xC3\xA2"=>"&acirc;",
        "\xC3\xA3"=>"&atilde;",
        "\xC3\xA4"=>"&auml;",
        "\xC3\xA5"=>"&aring;",
        "\xC3\xA6"=>"&aelig;",
        "\xC3\xA7"=>"&ccedil;",
        "\xC3\xA8"=>"&egrave;",
        "\xC3\xA9"=>"&eacute;",
        "\xC3\xAA"=>"&ecirc;",
        "\xC3\xAB"=>"&euml;",
        "\xC3\xAC"=>"&igrave;",
        "\xC3\xAD"=>"&iacute;",
        "\xC3\xAE"=>"&icirc;",
        "\xC3\xAF"=>"&iuml;",
        "\xC3\xB0"=>"&eth;",
        "\xC3\xB1"=>"&ntilde;",
        "\xC3\xB2"=>"&ograve;",
        "\xC3\xB3"=>"&oacute;",
        "\xC3\xB4"=>"&ocirc;",
        "\xC3\xB5"=>"&otilde;",
        "\xC3\xB6"=>"&ouml;",
        "\xC3\xB7"=>"&divide;",
        "\xC3\xB8"=>"&oslash;",
        "\xC3\xB9"=>"&ugrave;",
        "\xC3\xBA"=>"&uacute;",
        "\xC3\xBB"=>"&ucirc;",
        "\xC3\xBC"=>"&uuml;",
        "\xC3\xBD"=>"&yacute;",
        "\xC3\xBE"=>"&thorn;",
        "\xC3\xBF"=>"&yuml;",
        "\xC2\xA0"=>'&nbsp;',
        "\xC2\xA1"=>'&iexcl;',
        "\xC2\xA2"=>'&cent;',
        "\xC2\xA3"=>'&pound;',
        "\xC2\xA4"=>'&curren;',
        "\xC2\xA5"=>'&yen;',
        "\xC2\xA6"=>'&brvbar;',
        "\xC2\xA7"=>'&sect;',
        "\xC2\xA8"=>'&uml;',
        "\xC2\xA9"=>'&copy;',
        "\xC2\xAA"=>'&ordf;',
        "\xC2\xAB"=>'&laquo;',
        "\xC2\xAC"=>'&not;',
        "\xC2\xAD"=>'&shy;',
        "\xC2\xAE"=>'&reg;',
        "\xC2\xAF"=>'&macr;',
        "\xC2\xB0"=>'&deg;',
        "\xC2\xB1"=>'&plusmn;',
        "\xC2\xB2"=>'&sup2;',
        "\xC2\xB3"=>'&sup3;',
        "\xC2\xB4"=>'&acute;',
        "\xC2\xB5"=>'&micro;',
        "\xC2\xB6"=>'&para;',
        "\xC2\xB7"=>'&middot;',
        "\xC2\xB8"=>'&cedil;',
        "\xC2\xB9"=>'&sup1;',
        "\xC2\xBA"=>'&ordm;',
        "\xC2\xBB"=>'&raquo;',
        "\xC2\xBC"=>'&frac14;',
        "\xC2\xBD"=>'&frac12;',
        "\xC2\xBE"=>'&frac34;',
        "\xC2\xBF"=>'&iquest;',
//
        "\xC2\x89" => '',
        "\xC2\x9A" => '',


        "\xC5\xBE"=>'',
        "\xC5\xB8"=>'',


        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A
        "\xC2\x84" => '"', // U+0084⇒U+201E


        "\xC2\x8B" => "'", // U+008B⇒U+2039
        "\xC2\x91" => "'", // U+0091⇒U+2018
        "\xC2\x92" => "'", // U+0092⇒U+2019
        "\xC2\x93" => '"', // U+0093⇒U+201C
        "\xC2\x94" => '"', // U+0094⇒U+201D
        "\xC2\x9B" => "'", // U+009B⇒U+203A

        // Regular Unicode     // U+0022 quotation mark (")
        // U+0027 apostrophe     (')
        "\xC2\x80" => '"', // U+00BB

        "\xE2\x80\x98" => "'", // U+2018
        "\xE2\x80\x99" => "'", // U+2019
        "\xE2\x80\x9A" => "'", // U+201A
        "\xE2\x80\x9B" => "'", // U+201B
        "\xE2\x80\x9C" => '"', // U+201C
        "\xE2\x80\x9D" => '"', // U+201D
        "\xE2\x80\x9E" => '"', // U+201E
        "\xE2\x80\x9F" => '"', // U+201F
        "\xE2\x80\xB9" => "'", // U+2039
        "\xE2\x80\xBA" => "'", // U+203A
        "\xE2\x9D\x9B" => "'", // U+203A
        "\xE2\x9D\x9C" => "'", // U+203A
        "\xE2\x9D\x9D" => '"', // U+203A
        "\xE2\x9D\x9E" => '"', // U+203A
    );
//    $chr = array_keys($chr_map); // but: for efficiency you should
//    $rpl = array_values($chr_map); // pre-calculate these two arrays
//    $str = str_replace($chr, $rpl, html_entity_decode($str, ENT_QUOTES, "UTF-8"));
    $str = html_entity_encode($str);
    return $str;
}


function is_utf8( $str )
{
    return preg_match( "/^(
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
      )*$/x",
      $str
    );
}

function fix_to_slug($url){
    # Prep string with some basic normalization
    $url = to_no_tildes($url);
    $url = strtolower($url);
    $url = strip_tags($url);
    $url = stripslashes($url);
    $url = html_entity_decode($url);

    # Remove quotes (can't, etc.)
    $url = str_replace('\'', '', $url);

    # Replace non-alpha numeric with hyphens
    $match = '/[^a-z0-9]+/';
    $replace = '-';
    $url = preg_replace($match, $replace, $url);

    $url = trim($url, '-');
    return $url;
}

function force_utf8( $str, $inputEnc='WINDOWS-1252' )
{
    if ( is_utf8( $str ) ) // Nothing to do.
        return $str;

    if ( strtoupper( $inputEnc ) === 'ISO-8859-1' )
        return utf8_encode( $str );

    if ( function_exists( 'mb_convert_encoding' ) )
        return mb_convert_encoding( $str, 'UTF-8', $inputEnc );

    if ( function_exists( 'iconv' ) )
        return iconv( $inputEnc, 'UTF-8', $str );

    return false;
    // You could also just return the original string.
    trigger_error(
        'Cannot convert string to UTF-8 in file '
            . __FILE__ . ', line ' . __LINE__ . '!',
        E_USER_ERROR
    );
}



/**
 * @param $cadena
 */
function clear_str_no_acutes($cadena)
{
    clear_str($cadena);
}

/**
 * @param $cadena
 * @return string
 */
function limpiarCadena($cadena)
{
    return clear_str($cadena);
}

/**
 * @param $cadena
 * @return string
 */
function limpiarCadenaUTF8($cadena)
{
    return clear_str($cadena);
}

/**
 * @param $data
 * @return mixed|string
 */
function cleanHtml($data)
{
    $data = limpiarBasicsRev($data);
    $data = preg_replace('@<(p|font|b)[^>]*>@is', '', $data);
    $data = preg_replace('@</(p|font|b)>@is', '', $data);
    $data = str_replace("\\r\\n", '<br/>', $data);
    return $data;
}

/**
 * @param $cadena
 * @return string
 */
function limpiarBasicsRev($cadena)
{
    return cleanToPrint($cadena);
}

/**
 * @param $floatcurr
 * @param string $curr
 * @return string
 */
function formatcurrency($floatcurr, $curr = "COP")
{
    $floatcurr = (float)$floatcurr;
    $currencies['ARS'] = array(2, ',', '.');          //  Argentine Peso
    $currencies['AMD'] = array(2, '.', ',');          //  Armenian Dram
    $currencies['AWG'] = array(2, '.', ',');          //  Aruban Guilder
    $currencies['AUD'] = array(2, '.', ' ');          //  Australian Dollar
    $currencies['BSD'] = array(2, '.', ',');          //  Bahamian Dollar
    $currencies['BHD'] = array(3, '.', ',');          //  Bahraini Dinar
    $currencies['BDT'] = array(2, '.', ',');          //  Bangladesh, Taka
    $currencies['BZD'] = array(2, '.', ',');          //  Belize Dollar
    $currencies['BMD'] = array(2, '.', ',');          //  Bermudian Dollar
    $currencies['BOB'] = array(2, '.', ',');          //  Bolivia, Boliviano
    $currencies['BAM'] = array(2, '.', ',');          //  Bosnia and Herzegovina, Convertible Marks
    $currencies['BWP'] = array(2, '.', ',');          //  Botswana, Pula
    $currencies['BRL'] = array(2, ',', '.');          //  Brazilian Real
    $currencies['BND'] = array(2, '.', ',');          //  Brunei Dollar
    $currencies['CAD'] = array(2, '.', ',');          //  Canadian Dollar
    $currencies['KYD'] = array(2, '.', ',');          //  Cayman Islands Dollar
    $currencies['CLP'] = array(0, '', '.');           //  Chilean Peso
    $currencies['CNY'] = array(2, '.', ',');          //  China Yuan Renminbi
    $currencies['COP'] = array(0, '', '.');          //  Colombian Peso
    $currencies['CRC'] = array(2, ',', '.');          //  Costa Rican Colon
    $currencies['HRK'] = array(2, ',', '.');          //  Croatian Kuna
    $currencies['CUC'] = array(2, '.', ',');          //  Cuban Convertible Peso
    $currencies['CUP'] = array(2, '.', ',');          //  Cuban Peso
    $currencies['CYP'] = array(2, '.', ',');          //  Cyprus Pound
    $currencies['CZK'] = array(2, '.', ',');          //  Czech Koruna
    $currencies['DKK'] = array(2, ',', '.');          //  Danish Krone
    $currencies['DOP'] = array(2, '.', ',');          //  Dominican Peso
    $currencies['XCD'] = array(2, '.', ',');          //  East Caribbean Dollar
    $currencies['EGP'] = array(2, '.', ',');          //  Egyptian Pound
    $currencies['SVC'] = array(2, '.', ',');          //  El Salvador Colon
    $currencies['ATS'] = array(2, ',', '.');          //  Euro
    $currencies['BEF'] = array(2, ',', '.');          //  Euro
    $currencies['DEM'] = array(2, ',', '.');          //  Euro
    $currencies['EEK'] = array(2, ',', '.');          //  Euro
    $currencies['ESP'] = array(2, ',', '.');          //  Euro
    $currencies['EUR'] = array(2, ',', '.');          //  Euro
    $currencies['FIM'] = array(2, ',', '.');          //  Euro
    $currencies['FRF'] = array(2, ',', '.');          //  Euro
    $currencies['GRD'] = array(2, ',', '.');          //  Euro
    $currencies['IEP'] = array(2, ',', '.');          //  Euro
    $currencies['ITL'] = array(2, ',', '.');          //  Euro
    $currencies['LUF'] = array(2, ',', '.');          //  Euro
    $currencies['NLG'] = array(2, ',', '.');          //  Euro
    $currencies['PTE'] = array(2, ',', '.');          //  Euro
    $currencies['GHC'] = array(2, '.', ',');          //  Ghana, Cedi
    $currencies['GIP'] = array(2, '.', ',');          //  Gibraltar Pound
    $currencies['GTQ'] = array(2, '.', ',');          //  Guatemala, Quetzal
    $currencies['HNL'] = array(2, '.', ',');          //  Honduras, Lempira
    $currencies['HKD'] = array(2, '.', ',');          //  Hong Kong Dollar
    $currencies['HUF'] = array(0, '', '.');           //  Hungary, Forint
    $currencies['ISK'] = array(0, '', '.');           //  Iceland Krona
    $currencies['INR'] = array(2, '.', ',');          //  Indian Rupee
    $currencies['IDR'] = array(2, ',', '.');          //  Indonesia, Rupiah
    $currencies['IRR'] = array(2, '.', ',');          //  Iranian Rial
    $currencies['JMD'] = array(2, '.', ',');          //  Jamaican Dollar
    $currencies['JPY'] = array(0, '', ',');           //  Japan, Yen
    $currencies['JOD'] = array(3, '.', ',');          //  Jordanian Dinar
    $currencies['KES'] = array(2, '.', ',');          //  Kenyan Shilling
    $currencies['KWD'] = array(3, '.', ',');          //  Kuwaiti Dinar
    $currencies['LVL'] = array(2, '.', ',');          //  Latvian Lats
    $currencies['LBP'] = array(0, '', ' ');           //  Lebanese Pound
    $currencies['LTL'] = array(2, ',', ' ');          //  Lithuanian Litas
    $currencies['MKD'] = array(2, '.', ',');          //  Macedonia, Denar
    $currencies['MYR'] = array(2, '.', ',');          //  Malaysian Ringgit
    $currencies['MTL'] = array(2, '.', ',');          //  Maltese Lira
    $currencies['MUR'] = array(0, '', ',');           //  Mauritius Rupee
    $currencies['MXN'] = array(2, '.', ',');          //  Mexican Peso
    $currencies['MZM'] = array(2, ',', '.');          //  Mozambique Metical
    $currencies['NPR'] = array(2, '.', ',');          //  Nepalese Rupee
    $currencies['ANG'] = array(2, '.', ',');          //  Netherlands Antillian Guilder
    $currencies['ILS'] = array(2, '.', ',');          //  New Israeli Shekel
    $currencies['TRY'] = array(2, '.', ',');          //  New Turkish Lira
    $currencies['NZD'] = array(2, '.', ',');          //  New Zealand Dollar
    $currencies['NOK'] = array(2, ',', '.');          //  Norwegian Krone
    $currencies['PKR'] = array(2, '.', ',');          //  Pakistan Rupee
    $currencies['PEN'] = array(2, '.', ',');          //  Peru, Nuevo Sol
    $currencies['UYU'] = array(2, ',', '.');          //  Peso Uruguayo
    $currencies['PHP'] = array(2, '.', ',');          //  Philippine Peso
    $currencies['PLN'] = array(2, '.', ' ');          //  Poland, Zloty
    $currencies['GBP'] = array(2, '.', ',');          //  Pound Sterling
    $currencies['OMR'] = array(3, '.', ',');          //  Rial Omani
    $currencies['RON'] = array(2, ',', '.');          //  Romania, New Leu
    $currencies['ROL'] = array(2, ',', '.');          //  Romania, Old Leu
    $currencies['RUB'] = array(2, ',', '.');          //  Russian Ruble
    $currencies['SAR'] = array(2, '.', ',');          //  Saudi Riyal
    $currencies['SGD'] = array(2, '.', ',');          //  Singapore Dollar
    $currencies['SKK'] = array(2, ',', ' ');          //  Slovak Koruna
    $currencies['SIT'] = array(2, ',', '.');          //  Slovenia, Tolar
    $currencies['ZAR'] = array(2, '.', ' ');          //  South Africa, Rand
    $currencies['KRW'] = array(0, '', ',');           //  South Korea, Won
    $currencies['SZL'] = array(2, '.', ', ');         //  Swaziland, Lilangeni
    $currencies['SEK'] = array(2, ',', '.');          //  Swedish Krona
    $currencies['CHF'] = array(2, '.', '\'');         //  Swiss Franc
    $currencies['TZS'] = array(2, '.', ',');          //  Tanzanian Shilling
    $currencies['THB'] = array(2, '.', ',');          //  Thailand, Baht
    $currencies['TOP'] = array(2, '.', ',');          //  Tonga, Paanga
    $currencies['AED'] = array(2, '.', ',');          //  UAE Dirham
    $currencies['UAH'] = array(2, ',', ' ');          //  Ukraine, Hryvnia
    $currencies['USD'] = array(2, '.', ',');          //  US Dollar
    $currencies['VUV'] = array(0, '', ',');           //  Vanuatu, Vatu
    $currencies['VEF'] = array(2, ',', '.');          //  Venezuela Bolivares Fuertes
    $currencies['VEB'] = array(2, ',', '.');          //  Venezuela, Bolivar
    $currencies['VND'] = array(0, '', '.');           //  Viet Nam, Dong
    $currencies['ZWD'] = array(2, '.', ' ');          //  Zimbabwe Dollar
    if ($curr == "INR") {
        return formatinr($floatcurr);
    } else {
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}

/**
 * @param $input
 * @return string
 */
function formatinr($input)
{
//CUSTOM FUNCTION TO GENERATE ##,##,###.##
    $dec = "";
    $pos = strpos($input, ".");
    if ($pos === false) {
//no decimals
    } else {
//decimals
        $dec = substr(round(substr($input, $pos), 2), 1);
        $input = substr($input, 0, $pos);
    }
    $num = substr($input, -3); //get the last 3 digits
    $input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
    while (strlen($input) > 0) //loop the process - further get digits 2 by 2
    {
        $num = substr($input, -2) . "," . $num;
        $input = substr($input, 0, -2);
    }
    return $num . $dec;
}


## end class
/**
 * @param $str
 * @return string
 */
function toFullTextSearch($str)
{
    return strtolower(cleanHtml($str));
}

/**
 * @param int $len
 * @return string
 */
function incrementalHash($len = 5)
{
    $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $base = strlen($charset);
    $result = '';

    $now = explode(' ', microtime());
    $now = $now[1];

    while ($now >= $base) {
        $i = $now % $base;
        $result = $charset[$i] . $result;
        $now /= $base;
    }
    return md5(substr($result, -10));
}

class BaseIntEncoder
{
    const codeset = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";

    static function encode($n)
    {
        $base = strlen(self::codeset);
        $converted = '';
        while ($n > 0) {
            $converted = substr(self::codeset, bcmod($n, $base), 1) . $converted;
            $n = self::bcFloor(bcdiv($n, $base));
        }
        return $converted;
    }

    static function decode($code)
    {
        $base = strlen(self::codeset);
        $c = '0';
        for ($i = strlen($code); $i; $i--) {
            $c = bcadd($c, bcmul(strpos(self::codeset, substr($code, (-1 * ($i - strlen($code))), 1))
                , bcpow($base, $i - 1)));
        }
        return bcmul($c, 1, 0);
    }

    static private function bcFloor($x)
    {
        return bcmul($x, '1', 0);
    }

    static private function bcCeil($x)
    {
        $floor = bcFloor($x);
        return bcadd($floor, ceil(bcsub($x, $floor)));
    }

    static private function bcRound($x)
    {
        $floor = bcFloor($x);
        return bcadd($floor, round(bcsub($x, $floor)));
    }
}

/*
    if (isset($_FILES["fileToUpload"])) {
        $data = cvs_to_array($_FILES["fileToUpload"]["tmp_name"]);
    }
*/
function cvs_to_array($file_route="", $_DELIMETER = ',', $_ENCLOSER = '"', $_ESCAPE = "\\"){
        $file = new SplFileObject("$file_route");
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl($_DELIMETER, $_ENCLOSER, $_ESCAPE); // this is the default anyway though

        $info_cvs=array();foreach ($file as $row) {array_push($info_cvs,$row);} // convert to array the SPLFileOBJECT
        $items_title = $info_cvs[0];unset($info_cvs[0]);
        $result = array();
        foreach ($info_cvs as $row) {
            $item_temp = array();
            for($i=0;$i<count($items_title);$i++){
                if(strlen($items_title[$i])>0)
                    $item_temp["$items_title[$i]"]=$row[$i];
            }
            array_push($result,$item_temp);
        }
        return $result;
}
function decode_array($d){
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        $d = htmlspecialchars_decode(utf8_decode(htmlentities($d, ENT_COMPAT, 'utf-8', false)));

        return $d;
    }
    return $d;
}
function utf8size($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        $d = to_acutes_tildes($d);
        return utf8_encode($d);
    }
    return $d;
}
// alias 
function utf8_size($d){return utf8size($d);}
function utf8ize($d){return utf8size($d);}


