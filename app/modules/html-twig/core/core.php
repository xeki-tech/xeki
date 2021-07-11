<?php

namespace xeki_html_twig;
use Twig;
class xeki_html_twig
{

    public $done_render = false;
    public $render = '';
    public $AG_BASE = '';

    public $AG_BASE_COMPLETE = '';
    public $ARRAY_PARAMS = '';
    public $LAST_PARAM = '';


    public $AG_PARAMS = array();
    public $AG_L_PARAM = '';

    public $AG_SEO_DATA = array();
    public $SOCIAL_META_TAGS = array();
    public $ITEMSCOPE = "";

    public $AG_META_DATA = "";
    public $AG_RENDER_EXTRA_DATA = array();

    public $SOCIAL_META_TAGS_HTML = "";

    protected static $base_path = array();
    private $DEFAULT_TITLE;
    private $DEFAULT_END_TITLE;
    private $DEFAULT_DESCRIPTION;
    private $DEFAULT_END_DESCRIPTION;

    /**
     * http_request constructor.
     */
    public function __construct($config)
    {

        $this->analyze_url();

        if(!empty($config['static_files_url'])){
            $this->load_static_files($config['static_files_url']);
        }
        $this->DEFAULT_TITLE=$config['default_title'];
        $this->DEFAULT_END_TITLE=$config['default_end_title'];
        $this->DEFAULT_DESCRIPTION=$config['default_description'];
        $this->DEFAULT_END_DESCRIPTION=$config['default_end_description'];

        $this->base_path = $config['pages_folder'];
        $loader = new Twig\Loader\FilesystemLoader($this->base_path);#folder html

        $cache_folder = $config['cache_folder'];
        // Check if is in gcp / GAE
        if(isset($_SERVER['GAE_INSTANCE'])){
            $cache_folder = '/tmp/twig/';
            $config['cache']=false;
        }
        $this->render = new Twig\Environment($loader, array(
            'cache' => $cache_folder , #folder cache
            'debug' => $config['cache'],
            'charset' => 'utf-8',
        ));
        

    }

    private function load_static_files($url_param){
        $url_static_files='';

        if(strpos($url_param, "http")!==false){
            $url_static_files=$url_param;
        }
        else{
            $url_static_files=\xeki\core::$URL_BASE_COMPLETE.$url_param;
        }

        $this->SetVar("static_files",$url_static_files);
        $this->SetVar("static_files_url",$url_static_files);
    }
    public function set_path($path,$cache="")
    {
        if($cache=="")$cache=\xeki\core::$SYSTEM_PATH_BASE."/cache/";
//        d("new render");
        $_DEBUG_MODE = DEBUG_MODE;
        $this->base_path = $path;
        $loader = new Twig\Loader\FilesystemLoader($path);#folder html
        $this->render = new Twig\Environment($loader, array(
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

        $this->AG_BASE = $AG_BASE;
        $this->AG_BASE_COMPLETE = $AG_BASE_COMPLETE;

        $this->AG_PARAMS = $AG_PARAMS;
        $this->AG_L_PARAM = $AG_L_PARAM;

        $this->AG_BASE_COMPLETE = $AG_BASE_COMPLETE;
        $this->ARRAY_PARAMS = $AG_PARAMS;
        $this->LAST_PARAM = $AG_L_PARAM;
    }

    public function set_render_data($data = "", $key = "")
    {
        $this->AG_SEO_DATA[$data] = $key;
    }

    public function set_meta_data($meta_data = "")
    {
        $this->AG_META_DATA;
    }


    public function SetVar($key_array, $value = "")
    {
        if (is_array($key_array)) {
            $this->AG_RENDER_EXTRA_DATA = array_merge($this->AG_RENDER_EXTRA_DATA, $key_array);
        } else {
            $this->AG_RENDER_EXTRA_DATA[$key_array]=$value;
        }
    }

    public function GetVar($key_array)
    {
        return $this->AG_RENDER_EXTRA_DATA[$key_array];
    }

    public function GetVars()
    {
        return $this->AG_RENDER_EXTRA_DATA;
    }

    public function set_itemscope($itemscope="")
    {
        $this->ITEMSCOPE= $itemscope;
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
        $this->SOCIAL_META_TAGS = array();
        foreach ($this->SOCIAL_META_TAGS as $item){

        }
        $this->SOCIAL_META_TAGS_HTML= "";

        if(isset($data['google'])){
            $inner_data = $data['google'];
            if($inner_data['name'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta itemprop='name' content='{$inner_data['name']}'>";
            }
            if($inner_data['description'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta itemprop='description' content='{$inner_data['description']}'>";
            }
            if($inner_data['image'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta itemprop='image' content='{$inner_data['image']}'>";
            }
        }

        if(isset($data['twitter'])){
            $inner_data = $data['twitter'];
            if($inner_data['card'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:card' content='summary_large_image'>";
            }
            if($inner_data['site'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:site' content=''@publisher_handle'>";
            }
            if($inner_data['title'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:title' content='Page Title'>";
            }
            if($inner_data['description'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:description' content='Page description less than 200 characters'>";
            }
            if($inner_data['creator'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:creator' content=''@author_handle'>";
            }
            if($inner_data['image'] ){
//                $this->>SOCIAL_META_TAGS_HTML.= "<meta name='twitter:image:src' content='http://www.example.com/image.jpg'>";
            }
        }
        if(isset($data['facebook'])){
            $inner_data = $data['facebook'];
            if($inner_data['title'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:title' content='{$inner_data['title']}'>";
            }
            if($inner_data['type'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:type' content='{$inner_data['type']}'>";
            }
            if($inner_data['url'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:url' content='{$inner_data['url']}'>";
            }
            if($inner_data['image'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:image' content='{$inner_data['image']}'>";
            }
            if($inner_data['description'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:description' content='{$inner_data['description']}'>";
            }
            if($inner_data['site_name'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:site_name' content='{$inner_data['site_name']}'>";
            }
            if($inner_data['fb:admins'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='fb:admins' content='{$inner_data['fb:admins']}'>";
            }
            if($inner_data['article:published_time'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='article:published_time' content='{$inner_data['article:published_time']}' />";
            }
            if($inner_data['article:modified_time'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='article:modified_time' content='{$inner_data['article:modified_time']}' />";
            }
            if($inner_data['article:section'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='article:section' content='{$inner_data['article:section']}' />   ";
            }
            if($inner_data['article:tag'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='article:tag' content='{$inner_data['article:tag']}' />   ";
            }
            if($inner_data['price:amount'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:price:amount' content='{$inner_data['price:amount']}' />";
            }
            if($inner_data['price:currency'] ){
                $this->SOCIAL_META_TAGS_HTML.= "<meta property='og:price:currency' content='{$inner_data['price:currency']}' />";
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
        $_DEFAULT_TITLE = $this->DEFAULT_TITLE;
        $_DEFAULT_END_TITLE = $this->DEFAULT_END_TITLE;
        $_DEFAULT_DESCRIPTION = $this->DEFAULT_DESCRIPTION;
        $_DEFAULT_END_DESCRIPTION = $this->DEFAULT_END_DESCRIPTION;

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
        $this->AG_SEO_DATA['page_title'] = cleanToPrint($title);
        $this->AG_SEO_DATA['page_description'] = cleanToPrint($description);
        $this->AG_SEO_DATA['page_keyWords'] = cleanToPrint($keywords);
    }

    public function render_json($array_json){
        $this->done_render=true;
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
    public function render($file = '', $dataArray = array())
    {
        // fix
        if(!is_array($dataArray))$dataArray=array();
        $this->done_render=true;
        $AG_BASE = $this->AG_BASE;
        $AG_BASE_COMPLETE = $this->AG_BASE_COMPLETE;
        $AG_META_DATA = $this->AG_META_DATA;
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
        $dataArray = array_merge($this->AG_SEO_DATA, $dataArray);

        $dataArray = array_merge($variables_system, $dataArray);

        $dataArray = array_merge($this->AG_RENDER_EXTRA_DATA, $dataArray);


        // d($dataArray);
//        d($dataArray);
        if($this->ITEMSCOPE!==""){
            $dataArray['page_item_scope']=$this->ITEMSCOPE;
        }
        if($this->SOCIAL_META_TAGS_HTML!==""){
            $dataArray['page_social_meta_tags']=$this->SOCIAL_META_TAGS_HTML;
        }

        // Other tags


        // d($dataArray);
        // valid if file exist
        $file_base_path = $this->base_path;
        if(!file_exists ( "{$file_base_path}{$file}")){
            // ERROR the html file dont exist
            echo "ERROR the file dont exist {$file_base_path}{$file}";
            die();
        }
//        d($dataArray);
        // check is exist cache folder

        // $dataArray = utf8size($dataArray);
        $print_html = $this->render->render($file, $dataArray);


        if (!$_DEBUG_MODE) {
            $print_html = $this->compress_html($print_html);
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

    public function get_url_base_complete(){
        return $this->AG_BASE_COMPLETE;
    }

}
