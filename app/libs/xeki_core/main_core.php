<?php
namespace xeki;
require_once(dirname(__FILE__) . "/support_libs/error.php");

/**
 * Class core
 * @package xeki
 */
class core
{

    // util and global variables 
    /**
     * @var string
     */
    public static $DIR_PATH = "";
    /**
     * @var string
     */
    public static $URL_BASE = "";

    /*
    *
    */
    /**
     * @var string
     */
    public static $URL_BASE_COMPLETE = "";

    /**
     * @var string
     */
    public static $SYSTEM_PATH_BASE = "";

    /*
    *
    */
    /**
     * @var string
     */
    public static $URL_PARAMS = "";

    /*
     *
     */
    /**
     * @var string
     */
    public static $URL_REQUEST_COMPLETE = "";

    /*
     *
     */
    /**
     * @var string
     */
    public static $URL_REQUEST = "";

    /*
    *
    */
    /**
     * @var string
     */
    public static $URL_PARAMS_LAST = "";

    /*
    *
    */
    /**
     * @var string
     */
    public static $_DEFAULT_PAGE_ERROR = "";
    /**
     * @var string
     */
    public static $_DOMAIN = "";


    /**
     *
     */
    public static function init()
    {
        self::$DIR_PATH = dirname(__FILE__) . "/../../";

        self::analyze_url();
    }


    /**
     * @param $string
     */
    public static function fatal_error($string)
    {
        // add send email
        echo $string;
        die();
    }

    /**
     * @return mixed
     */
    public static function get_payload()
    {
        $request_body = file_get_contents('php://input');
        $_PAY_LOAD = json_decode($request_body, true);
        return $_PAY_LOAD;
    }

    /**
     * @return array
     */
    public static function get_request_data()
    {
        $data = [];
        // check get
        if (is_array($_GET)) {
            $data = $_GET;
        }

        // check post
        if (is_array($_POST)) {
            $data = array_merge($_POST, $data);
        }

        // check payload
        $request_body = file_get_contents('php://input');
        $_PAY_LOAD = json_decode($request_body, true);

        if (is_array($_PAY_LOAD)) {
            $data = array_merge($data, $_PAY_LOAD);
        }

        return $data;
    }

    /**
     * @return array
     */
    public static function get_post_data()
    {
        $data = [];

        // check post
        if (is_array($_POST)) {
            $data = array_merge($_POST, $data);
        }

        // check payload
        $request_body = file_get_contents('php://input');
        $_PAY_LOAD = json_decode($request_body, true);

        if (is_array($_PAY_LOAD)) {
            $data = array_merge($data, $_PAY_LOAD);
        }

        return $data;
    }

    /**
     *
     */
    public static function analyze_url()
    {


        $host = $_SERVER['HTTP_HOST'] ?? "cli";
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? "cli";
        $url = $_SERVER['REQUEST_URI'] ?? "cli";
        $server_name = $_SERVER['SERVER_NAME'] ?? "cli";


        // remove get params
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }
        // load script name
        $scriptName = $_SERVER['SCRIPT_NAME'];

        $AG_PARAMS = explode("/", $url);

        $URL_BASE = str_replace('index.php', '', $scriptName);
        $URL_BASE = str_replace('//', '/', $URL_BASE);


        $AG_PARAMS = array_slice($AG_PARAMS, count(explode("/", $scriptName)) - 1);


        $clean_url = "";
        $cont = 0;
        foreach ($AG_PARAMS as $item) {
            if ($cont == 0)
                $clean_url .= $item;
            else
                $clean_url .= "/" . $item;

            $cont++;
        }


        $cParams = count($AG_PARAMS);
        $checker = 'key_default_for_check_spam_imposible_last_param';
        $AG_L_PARAM = $checker;
        if ($cParams > 0) {
            if ($AG_PARAMS[$cParams - 1] !== '') {
                $AG_L_PARAM = $AG_PARAMS[$cParams - 1];
            } else if ($cParams > 2) {
                $AG_L_PARAM = $AG_PARAMS[$cParams - 2];
            }
        }
        $AG_L_PARAM = $AG_L_PARAM == $checker ? '' : $AG_L_PARAM;

        self::$_DOMAIN =
            ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? isset($_SERVER['HTTP_HOST'])) ?
                $_SERVER['HTTP_HOST'] :
                $server_name;

        self::$URL_BASE = $URL_BASE;

        if (isset($_SERVER['HTTP_CF_VISITOR'])) { #for cloudflare scheme
            $info_cf = json_decode($_SERVER['HTTP_CF_VISITOR'], true);
            $_SERVER['REQUEST_SCHEME'] = $info_cf['scheme'];

        }
        $URL_BASE_COMPLETE = $host . '' . $URL_BASE;
        // set scheme and remove cli if exist   
        $base_url = "";
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https")
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https")
        ) {
            $base_url = "https";
        } else {
            $base_url = "http";
        }
        $URL_BASE_COMPLETE = str_replace("cli://", "", $URL_BASE_COMPLETE);
        self::$URL_BASE_COMPLETE = "{$base_url}://{$URL_BASE_COMPLETE}";

        self::$URL_PARAMS = $AG_PARAMS;
        self::$URL_PARAMS_LAST = $AG_L_PARAM;

        self::$URL_REQUEST_COMPLETE = $URL_BASE_COMPLETE . $clean_url;
        self::$URL_REQUEST = $clean_url;
    }

    /**
     * @param $find
     * @param $replace
     * @param $subject
     * @return string
     */
    public static function replace_first($find, $replace, $subject): string
    {
        // stolen from the comments at PHP.net/str_replace
        // Splits $subject into an array of 2 items by $find,
        // and then joins the array with $replace
        return implode($replace, explode($find, $subject, 2));
    }

    /**
     * @param $to
     * @param int $type
     * @param false $force
     */
    public static function redirect($to, $type = 0, $force = false)
    {
        $URL_BASE = self::$URL_BASE;
        $URI = $_SERVER['REQUEST_URI'];
        $URI_CLEAN = self::replace_first("/", '', $URI);
        if (!$force && ($URI == $to || $URI_CLEAN == $to)) {
            return;
        }
        if (strpos($to, 'http://') !== false || strpos($to, 'https://') !== false) {
            $to_url = $to;
        } else {
            $to_url = $URL_BASE . $to;
        }

        if ($type != 302) {
            header("HTTP/1.1 302 Moved Permanently");
            header('Location: ' . $to_url, true, 302);
        } else {
            header('Location: ' . $to_url);
        }


        echo '<meta http-equiv="refresh" content="0;URL="' . $to_url . '"/>';
        echo '<script>window.location.replace("' . $to_url . '");</script>';
        die();
        # Prep string with some basic normalization
    }

    /**
     * @param $error
     * @return bool
     */
    public static function is_error($error)
    {
        if ($error instanceof \xeki\error) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $url
     * @return string
     */
    function fix_to_slug($url): string
    {
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

    // change a string with acoutes and tildes to no acutes and no tildes

    /**
     * @param $str
     * @return array|string|string[]
     */
    function to_no_tildes($str)
    {

        $new_string = force_utf8($str); # force text to utf8
        if ($new_string) $str = $new_string;
        $str = utf8_decode($str);
        $str = str_replace(
            array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;"),
            array(chr(0xE1), chr(0xE9), chr(0xED), chr(0xF3), chr(0xFA), chr(0xF1), chr(0xC1), chr(0xC9), chr(0xCD), chr(0xD3), chr(0xDA), chr(0xD1)),
            $str);
        # array("a","e","i","o","u","n","A","E","I","O","U","N") tildes to no tildes
        $str = str_replace(array(chr(0xE1), chr(0xE9), chr(0xED), chr(0xF3), chr(0xFA), chr(0xF1), chr(0xC1), chr(0xC9), chr(0xCD), chr(0xD3), chr(0xDA), chr(0xD1)),
            array("a", "e", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N"),
            $str);

        $str = str_replace(array(chr(0xA1), chr(0xAB), chr(0xBB), chr(0xBF)),
            array("", "", "", ""),
            $str);
        return $str;

    }


    // alias

    /**
     * @param $str
     * @return array|string|string[]
     */
    public static function textToAcutes($str)
    {
        return \xeki\core::text_to_acutes($str);
    }


    /**
     * @param $str
     * @return false|int
     */
    function is_utf8($str)
    {
        return preg_match("/^(
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

    /**
     * @param $str
     * @param string $inputEnc
     * @return array|false|mixed|string|string[]|null
     */
    function force_utf8($str, $inputEnc = 'WINDOWS-1252')
    {
        if (is_utf8($str)) // Nothing to do.
            return $str;

        if (strtoupper($inputEnc) === 'ISO-8859-1')
            return utf8_encode($str);

        if (function_exists('mb_convert_encoding'))
            return mb_convert_encoding($str, 'UTF-8', $inputEnc);

        if (function_exists('iconv'))
            return iconv($inputEnc, 'UTF-8', $str);

        return false;
    }

    /**
     * @return array|string|string[]
     */
    public static function set_static_files_route()
    {

        $route = $_SERVER['SCRIPT_NAME'];
        $route = str_replace('index.php', '', $route);
        $route = str_replace('libs/xeki_core/_main.php', '', $route);
        $route = "/" . $route . "static_files/";
        $route = str_replace('//', '/', $route);
        return $route;
    }

    /**
     * @param $str
     * @return array|string|string[]
     */
    public static function text_to_acutes($str)
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

            "\xC3\x80" => "&Agrave;",
            "\xC3\x81" => "&Aacute;",
            "\xC3\x82" => "&Acirc;",
            "\xC3\x83" => "&Atilde;",
            "\xC3\x84" => "&Auml;",
            "\xC3\x85" => "&Aring;",
            "\xC3\x86" => "&AElig;",
            "\xC3\x87" => "&Ccedil;",
            "\xC3\x88" => "&Egrave;",
            "\xC3\x89" => "&Eacute;",
            "\xC3\x8A" => "&Ecirc;",
            "\xC3\x8B" => "&Euml;",
            "\xC3\x8C" => "&Igrave;",
            "\xC3\x8D" => "&Iacute;",
            "\xC3\x8E" => "&Icirc;",
            "\xC3\x8F" => "&Iuml;",
            "\xC3\x90" => "&ETH;",
            "\xC3\x91" => "&Ntilde;",
            "\xC3\x92" => "&Ograve;",
            "\xC3\x93" => "&Oacute;",
            "\xC3\x94" => "&Ocirc;",
            "\xC3\x95" => "&Otilde;",
            "\xC3\x96" => "&Ouml;",
            "\xC3\x97" => "&times;",
            "\xC3\x98" => "&Oslash;",
            "\xC3\x99" => "&Ugrave;",
            "\xC3\x9A" => "&Uacute;",
            "\xC3\x9B" => "&Ucirc;",
            "\xC3\x9C" => "&Uuml;",
            "\xC3\x9D" => "&Yacute;",
            "\xC3\x9E" => "&THORN;",
            "\xC3\x9F" => "&szlig;",
            "\xC3\xA0" => "&agrave;",
            "\xC3\xA1" => "&aacute;",
            "\xC3\xA2" => "&acirc;",
            "\xC3\xA3" => "&atilde;",
            "\xC3\xA4" => "&auml;",
            "\xC3\xA5" => "&aring;",
            "\xC3\xA6" => "&aelig;",
            "\xC3\xA7" => "&ccedil;",
            "\xC3\xA8" => "&egrave;",
            "\xC3\xA9" => "&eacute;",
            "\xC3\xAA" => "&ecirc;",
            "\xC3\xAB" => "&euml;",
            "\xC3\xAC" => "&igrave;",
            "\xC3\xAD" => "&iacute;",
            "\xC3\xAE" => "&icirc;",
            "\xC3\xAF" => "&iuml;",
            "\xC3\xB0" => "&eth;",
            "\xC3\xB1" => "&ntilde;",
            "\xC3\xB2" => "&ograve;",
            "\xC3\xB3" => "&oacute;",
            "\xC3\xB4" => "&ocirc;",
            "\xC3\xB5" => "&otilde;",
            "\xC3\xB6" => "&ouml;",
            "\xC3\xB7" => "&divide;",
            "\xC3\xB8" => "&oslash;",
            "\xC3\xB9" => "&ugrave;",
            "\xC3\xBA" => "&uacute;",
            "\xC3\xBB" => "&ucirc;",
            "\xC3\xBC" => "&uuml;",
            "\xC3\xBD" => "&yacute;",
            "\xC3\xBE" => "&thorn;",
            "\xC3\xBF" => "&yuml;",
//
            "\xC2\x89" => '',
            "\xC2\x9A" => '',


            "\xC5\xBE" => '',
            "\xC5\xB8" => '',
            "\xC2\xA0" => '&nbsp;',
            "\xC2\xA1" => '&iexcl;',
            "\xC2\xA2" => '&cent;',
            "\xC2\xA3" => '&pound;',
            "\xC2\xA4" => '&curren;',
            "\xC2\xA5" => '&yen;',
            "\xC2\xA6" => '&brvbar;',
            "\xC2\xA7" => '&sect;',
            "\xC2\xA8" => '&uml;',
            "\xC2\xA9" => '&copy;',
            "\xC2\xAA" => '&ordf;',
            "\xC2\xAB" => '&laquo;',
            "\xC2\xAC" => '&not;',
            "\xC2\xAD" => '&shy;',
            "\xC2\xAE" => '&reg;',
            "\xC2\xAF" => '&macr;',
            "\xC2\xB0" => '&deg;',
            "\xC2\xB1" => '&plusmn;',
            "\xC2\xB2" => '&sup2;',
            "\xC2\xB3" => '&sup3;',
            "\xC2\xB4" => '&acute;',
            "\xC2\xB5" => '&micro;',
            "\xC2\xB6" => '&para;',
            "\xC2\xB7" => '&middot;',
            "\xC2\xB8" => '&cedil;',
            "\xC2\xB9" => '&sup1;',
            "\xC2\xBA" => '&ordm;',
            "\xC2\xBB" => '&raquo;',
            "\xC2\xBC" => '&frac14;',
            "\xC2\xBD" => '&frac12;',
            "\xC2\xBE" => '&frac34;',
            "\xC2\xBF" => '&iquest;',

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
        $chr = array_keys($chr_map); // but: for efficiency you should
        $rpl = array_values($chr_map); // pre-calculate these two arrays
        $str = str_replace($chr, $rpl, html_entity_decode($str, ENT_QUOTES, "UTF-8"));
        return $str;
    }

    /**
     * @param $array_json
     * @param bool $utf8
     */
    static function PrintJson($array_json, $utf8 = true)
    {
        header('Content-Type: application/json');
        if ($utf8) {
            $array_json = utf8_size($array_json);
        }
        if (is_array($array_json)) {
            $json = json_encode($array_json);
        } else {
            $json = $array_json;
        }
        echo($json);
    }

    /**
     * @param $html
     */
    static function PrintHtml($html)
    {
        header('Content-Type: text/html');
        echo $html;

    }

}