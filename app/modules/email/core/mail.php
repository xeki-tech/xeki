<?php

namespace xeki_mail;

use Mailgun\Mailgun; # con ganas de mandarlo a la !!!
use Aws\Ses\SesClient;
use GuzzleHttp\Client;

class mail
{
    private $from;

    // mail gun
    private $type = "local";
    private $config_array = array();


    private $domain_mail_gun = '';
    private $key_mailgun = '';
    private $default_from = 'demo@xekiframework.io';


    private $aws_key = '';
    private $aws_secret = '';
    private $aws_region = '';

    /**
     * mail constructor.
     * @param $from
     * @param $path
     * @param $domain_mail_gun
     * @param $key_mailgun
     */
    public function __construct($config)
    {
        $this->config_array = $config;
        $this->type = isset($config['type_sender']) ? $config['type_sender'] : false;
        $this->from = isset($config['from']) ? $config['from'] : "";
        $this->domain_mail_gun = isset($config['mailgun_domain']) ? $config['mailgun_domain'] : "";
        $this->key_mailgun = isset($config['mailgun_key']) ? $config['mailgun_key'] : "";
        $this->default_from = isset($config['default_from']) ? $config['default_from'] : "";


        $this->aws_key = isset($config['aws_key']) ? $config['aws_key'] : "";
        $this->aws_secret = isset($config['aws_secret']) ? $config['aws_secret'] : "";
        $this->aws_region = isset($config['aws_region']) ? $config['aws_region'] : "";

    }


    public function send_email($to, $subject, $html, $array_info = array())
    {

        if (strlen($html) == 0) {
            d("error empty html");
            die();
        }
        $info_email = array();
        $info_email['from'] = $this->default_from;
        if (isset($array_info['from']))
            $info_email['from'] = $array_info['from'];

        foreach ($array_info as $key => $info) {
            $html = str_replace("{{{$key}}}", $info, $html);
        }


        $info_email['to'] = $to;
        $info_email['subject'] = $subject;
        $info_email['html'] = $html;

        // d($info_email);
        // d($this->type);
        if ($this->type == "local") {

            return $this->send_by_local($to, $subject, $html, $info_email);
        }
        if ($this->type == "smtp") {
            return $this->send_by_smtp($info_email);
        }
        if ($this->type == "mailgun") {
            return $this->send_by_mail_gun($info_email);
        }
        if ($this->type == "aws") {
            return $this->send_by_aws_ses($info_email);
        }

    }

    private function send_by_mail_gun($info)
    {


        $return = "";
        try {
            $domain = $this->domain_mail_gun;
            $key = $this->key_mailgun;

            $client = new \GuzzleHttp\Client([
                'verify' => false,
            ]);
            $adapter = new \Http\Adapter\Guzzle6\Client($client);
            $mg = new Mailgun($key, $adapter);


            // $mg = new Mailgun($key);
            // $mg->setApiVersion('aecf68de');
            // $mg->setSslEnabled(false);
            // die();


            $return = $mg->sendMessage($domain, $info);
        } catch (Exception $e) {

            die();
        }

        return $return;


    }

    private function send_by_local($to, $subject, $html, $array_info)
    {

        // d($html);
        // d($subject);
        // d($to);
        // d($array_info);
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        if (isset($array_info['from'])) {
            $headers .= "From: {$array_info['from']}" . "\r\n";
        }
        $res = mail($to, $subject, $html, $headers);
        //d("i run ");
        //d($res);
    }

    private function send_by_aws_ses($array_info)
    {


        $config = array(
            'credentials' => array(
                'key' => $this->aws_key,
                'secret' => $this->aws_secret,
            ),
            'region' => $this->aws_region,
            'version' => 'latest',
            // 'scheme'  => 'http',
            'http' => [
                'verify' => false
            ]
        );
        $client = SesClient::factory($config);

        //Now that you have the client ready, you can build the message 
        $msg = array();
        $msg['Source'] = $array_info['from'];
        //ToAddresses must be an array
        $msg['Destination']['ToAddresses'] = array($array_info['to']);

        $msg['Message']['Subject']['Data'] = $array_info['subject'];
        $msg['Message']['Subject']['Charset'] = "UTF-8";

        // $msg['Message']['Body']['Text']['Data'] ="Text data of email";
        // $msg['Message']['Body']['Text']['Charset'] = "UTF-8";
        $msg['Message']['Body']['Html']['Data'] = $array_info['html'];
        $msg['Message']['Body']['Html']['Charset'] = "UTF-8";
        try {
            $result = $client->sendEmail($msg);

            //save the MessageId which can be used to track the request
            $msg_id = $result->get('MessageId');

            $result;
            //view sample output 

            return true;
        } catch (Exception $e) {
            //An error happened and the email did not get sent
            echo($e->getMessage());
            die();
            return false;
        }
        //view the original message passed to the SDK 
        // print_r($msg);
    }

    private function send_by_smtp($array_info)
    {

        require_once(dirname(__FILE__) . '/libs/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        //Create a new PHPMailer instance
        $mail = new \PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $mail->Host = $this->config_array['smtp_domain'];

        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = $this->config_array['smtp_port'];
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = $this->config_array['smtp_secure'];
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = $this->config_array['smtp_email'];
        //Password to use for SMTP authentication
        $mail->Password = $this->config_array['smtp_pass'];

//        $mail->SMTPOptions = array(
//            'ssl' => array(
//                'verify_peer' => false,
//                'verify_peer_name' => false,
//                'allow_self_signed' => false
//            )
//        );
        //Set who the message is to be sent from
        $mail->setFrom($this->config_array['smtp_email'], 'xeki sender');
        //Set an alternative reply-to address
//        $mail->addReplyTo('replyto@example.com', 'First Last');
        //Set who the message is to be sent to
        $mail->addAddress('liuspatt@gmail.com', 'John Doe');
        $mail->addAddress($array_info['to']);
        //Set the subject line
        $mail->Subject = $array_info['subject'];
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($array_info['html']);
        //Replace the plain text body with one created manually
//        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
//        $mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors

        if (!$mail->send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
//            echo "Message sent!";
        }

    }


}

