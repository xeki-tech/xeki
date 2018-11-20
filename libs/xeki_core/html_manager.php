<?php
/**
 * Created by PhpStorm.
 * User: Luis Eduardo
 * Date: 2/25/2016
 * Time: 6:21 AM
 */

namespace xeki;


class html_manager
{

    public static $done_render = false;
    public static $render = '';
    public static $AG_BASE = '';

    public static $AG_BASE_COMPLETE = '';
    public static $ARRAY_PARAMS = '';
    public static $LAST_PARAM = '';


    public static $AG_PARAMS = array();
    public static $AG_L_PARAM = '';

    public static $AG_SEO_DATA = array();
    public static $SOCIAL_META_TAGS = array();
    public static $ITEMSCOPE = "";

    public static $AG_META_DATA = "";
    public static $AG_RENDER_EXTRA_DATA = array();

    public static $SOCIAL_META_TAGS_HTML = "";

    protected static $base_path = array();

    /**
     * http_request constructor.
     */
    public function __construct($path,$cache)
    {
        $_DEBUG_MODE = DEBUG_MODE;

        self::analyze_url();
        self::$base_path = $path;
        $loader = new \Twig_Loader_Filesystem($path);#folder html
        self::$render = new \Twig_Environment($loader, array(
            'cache' => "$cache",#folder cache
            'debug' => $_DEBUG_MODE,
            'charset' => 'utf-8',
        ));

    }

    public function set_path($path,$cache="")
    {
        if($cache=="")$cache=\xeki\core::$SYSTEM_PATH_BASE."/cache/";
//        d("new render");
        $_DEBUG_MODE = DEBUG_MODE;
        self::$base_path = $path;
        $loader = new \Twig_Loader_Filesystem($path);#folder html
        self::$render = new \Twig_Environment($loader, array(
            'cache' => "$cache",#folder cache
            'debug' => $_DEBUG_MODE,
            'charset' => 'utf-8',
        ));

    }

    
    /**
     * Analyze and load AG_BASE and AG_BASE_COMPLETE
     */
    // this will be deprecated
    public function analyze_url()
    {
        $AG_BASE = \xeki\core::$URL_BASE;
        $AG_BASE_COMPLETE = \xeki\core::$URL_BASE_COMPLETE;
        $AG_PARAMS = \xeki\core::$URL_PARAMS;
        $AG_L_PARAM = \xeki\core::$URL_PARAMS_LAST;

        self::$AG_BASE = $AG_BASE;
        self::$AG_BASE_COMPLETE = $AG_BASE_COMPLETE;
        
        self::$AG_PARAMS = $AG_PARAMS;
        self::$AG_L_PARAM = $AG_L_PARAM;

        self::$AG_BASE_COMPLETE = $AG_BASE_COMPLETE;
        self::$ARRAY_PARAMS = $AG_PARAMS;
        self::$LAST_PARAM = $AG_L_PARAM;
    }

    public function set_render_data($data = "", $key = "")
    {
        self::$AG_SEO_DATA[$data] = $key;
    }

    public function set_meta_data($meta_data = "")
    {
        self::$AG_META_DATA;
    }


    public static function add_extra_data($key_array, $value = "")
    {
        if (is_array($key_array)) {
            self::$AG_RENDER_EXTRA_DATA = array_merge(self::$AG_RENDER_EXTRA_DATA, $key_array);
        } else {
            self::$AG_RENDER_EXTRA_DATA[$key_array]=$value;
        }
    }

    public function set_itemscope($itemscope="")
    {
        self::$ITEMSCOPE= $itemscope;
    }

    /**
     * Set seo
     * $data= array(
     *  "google" => array(),
     *   "name"=>"",
     *   "description"=>"",
     *   "image"=>"",#absolute url
     *   ),
     *  "twitter" => array(),
     *   "card"=>"",
     *   "site"=>"",
     *   "title"=>"",#absolute url
     *   "description"=>"",
     *   "creator"=>"",
     *   "image"=>"",
     *   ),
     *  "facebook" => array(),
     *   "title"=>"",
     *   "type"=>"",
     *   "url" = > "",
     *   "image"=>"",#absolute url
     *   "description"=>"",
     *   "fb:admins"=>"",
     *   "article:published_time"=>"",
     *   "article:modified_time"=>"",
     *   "article:section"=>"",
     *   "article:tag"=>"",
     *   "price:amount"=>"",
     *   "price:currency"=>"",
     *   ),
     * );
     *
     *
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param bool|false $fixInfo
     */
    public function set_social_meta_tags($data=array())
    {
        self::$SOCIAL_META_TAGS = array();
        foreach (self::$SOCIAL_META_TAGS as $item){

        }
        self::$SOCIAL_META_TAGS_HTML= "";

        if(isset($data['google'])){
            $inner_data = $data['google'];
            if($inner_data['name'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta itemprop='name' content='{$inner_data['name']}'>";
            }
            if($inner_data['description'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta itemprop='description' content='{$inner_data['description']}'>";
            }
            if($inner_data['image'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta itemprop='image' content='{$inner_data['image']}'>";
            }
        }

        if(isset($data['twitter'])){
            $inner_data = $data['twitter'];
            if($inner_data['card'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:card' content='summary_large_image'>";
            }
            if($inner_data['site'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:site' content=''@publisher_handle'>";
            }
            if($inner_data['title'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:title' content='Page Title'>";
            }
            if($inner_data['description'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:description' content='Page description less than 200 characters'>";
            }
            if($inner_data['creator'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:creator' content=''@author_handle'>";
            }
            if($inner_data['image'] ){
//                self::SOCIAL_META_TAGS_HTML.= "<meta name='twitter:image:src' content='http://www.example.com/image.jpg'>";
            }
        }
        if(isset($data['facebook'])){
            $inner_data = $data['facebook'];
            if($inner_data['title'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:title' content='{$inner_data['title']}'>";
            }
            if($inner_data['type'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:type' content='{$inner_data['type']}'>";
            }
            if($inner_data['url'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:url' content='{$inner_data['url']}'>";
            }
            if($inner_data['image'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:image' content='{$inner_data['image']}'>";
            }
            if($inner_data['description'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:description' content='{$inner_data['description']}'>";
            }
            if($inner_data['site_name'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:site_name' content='{$inner_data['site_name']}'>";
            }
            if($inner_data['fb:admins'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='fb:admins' content='{$inner_data['fb:admins']}'>";
            }
            if($inner_data['article:published_time'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='article:published_time' content='{$inner_data['article:published_time']}' />";
            }
            if($inner_data['article:modified_time'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='article:modified_time' content='{$inner_data['article:modified_time']}' />";
            }
            if($inner_data['article:section'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='article:section' content='{$inner_data['article:section']}' />   ";
            }
            if($inner_data['article:tag'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='article:tag' content='{$inner_data['article:tag']}' />   ";
            }
            if($inner_data['price:amount'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:price:amount' content='{$inner_data['price:amount']}' />";
            }
            if($inner_data['price:currency'] ){
                self::$SOCIAL_META_TAGS_HTML.= "<meta property='og:price:currency' content='{$inner_data['price:currency']}' />";
            }
        }
    }
    /**
     * Set seo
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param bool|false $fixInfo
     */
    public function set_seo($title = "", $description = "", $keywords = "", $fixInfo = false)
    {
        #fix for only 3 params
        if (!is_string($keywords)) if ($keywords) $fixInfo = true;
        $title = strip_tags($title); // remove html
        $description = strip_tags($description);// remove html
        $keywords = strip_tags($keywords);
        global $_DEFAULT_TITLE;
        global $_DEFAULT_END_TITLE;
        global $_DEFAULT_DESCRIPTION;
        global $_DEFAULT_END_DESCRIPTION;
        global $_DEFAULT_KEYWORDS;
        global $_DEFAULT_END_KEYWORDS;

        if ($fixInfo) {
            $leng_title = strlen($title);
            $leng_title_end = strlen($_DEFAULT_END_TITLE);
            $leng_description = strlen($description);
            $leng_description_end = strlen($_DEFAULT_END_DESCRIPTION);
            $leng_keywords = strlen($keywords);
            #fix title 70 max
            if ($leng_title == 0) {
                $title = $_DEFAULT_TITLE;
            } elseif ($leng_title > 70) {
                $title = substr($title, 0, 70);
            } elseif (($leng_title + $leng_title_end) <= 70) {
                $title = $title . $_DEFAULT_END_TITLE;
            } else {
                $title = $title . $_DEFAULT_END_TITLE;
                $title = substr($title, 0, 70);
            }
            if ($leng_description == 0) {
                $description = $_DEFAULT_DESCRIPTION;
            } elseif ($leng_description > 160) {
                $description = substr($description, 0, 160);
            } elseif (($leng_description + $leng_description_end) <= 160) {
                $description = $description . $_DEFAULT_END_DESCRIPTION;
            } else {
                $description = $description . $_DEFAULT_END_DESCRIPTION;
                $description = substr($description, 0, 160);
            }
            #fix description 160 max
        }
        self::$AG_SEO_DATA['page_title'] = cleanToPrint($title);
        self::$AG_SEO_DATA['page_description'] = cleanToPrint($description);
        self::$AG_SEO_DATA['page_keyWords'] = cleanToPrint($keywords);
    }

    public static function render_json($array_json){
        \xeki\html_manager::$done_render=true;
        header('Content-Type: application/json');
        utf8_size($array_json);
        $json='';
        if(is_array($array_json)){
            $json=json_encode($array_json);
        }
        else{
            $json=$array_json;
        }

        // transform to acoutes to ut8




        echo ($json);
    }
    /**
     * @param string $file
     * @param array $dataArray
     */
    public static function render($file = '', $dataArray = array())
    {
        // fix
        if(!is_array($dataArray))$dataArray=array();
        \xeki\html_manager::$done_render=true;
        $AG_BASE = self::$AG_BASE;
        $AG_BASE_COMPLETE = self::$AG_BASE_COMPLETE;
        $AG_META_DATA = self::$AG_META_DATA;
        $_DEBUG_MODE = DEBUG_MODE;

        $variables_system = array(
            "xeki_IS_MOBILE" => xeki_isMobile(),
            'URL_BASE' => $AG_BASE,
            'URL_BASE_COMPLETE' => $AG_BASE_COMPLETE,
            'xeki_BASE' => $AG_BASE,
            'xeki_BASE_COMPLETE' => $AG_BASE_COMPLETE,
            'xeki_META_DATA' => $AG_META_DATA,
        );
//        $dataArray = array();
        $dataArray = array_merge(self::$AG_SEO_DATA, $dataArray);

        $dataArray = array_merge($variables_system, $dataArray);

        $dataArray = array_merge(self::$AG_RENDER_EXTRA_DATA, $dataArray);


        // d($dataArray);
//        d($dataArray);
        if(self::$ITEMSCOPE!==""){
            $dataArray['page_item_scope']=self::$ITEMSCOPE;
        }
        if(self::$SOCIAL_META_TAGS_HTML!==""){
            $dataArray['page_social_meta_tags']=self::$SOCIAL_META_TAGS_HTML;
        }

        // Other tags


        // d($dataArray);
        // valid if file exist
        $file_base_path = self::$base_path;
        if(!file_exists ( "{$file_base_path}{$file}")){
            // ERROR the html file dont exist
            echo "ERROR the file dont exist {$file_base_path}{$file}";
            die();
        }
//        d($dataArray);
        // check is exist cache folder


        $print_html = self::$render->render($file, $dataArray);


        if (!$_DEBUG_MODE) {
            $print_html = self::compress_html($print_html);
        }
        echo $print_html;
    }

    public function compress_html($output)
    {
//    return $output;
        return preg_replace(
            array('#^\s*//.+$#m', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s', '/ {2,}/'),
            array(' ', ' ', ' '),
            $output
        );
    }


    // static methods 

    public static function get_url_base_complete(){
        return self::$AG_BASE_COMPLETE;
    }

}