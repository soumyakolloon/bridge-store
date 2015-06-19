<?php

/**
 * @project Bridge shoppingcart
 * Main controller
 */
include_once 'controller/database-controller.php';

class AppController
{

    public $protocal_array    = '';
    public $host              = '';
    public $protocal          = '';
    public $request_uri_array = '';
    public $request_uri       = '';
    public $database          = '';

    /**
     * Constructor
     */
    function __construct()
    {
        $this->database = new DataBaseController();
    }

    /**
     * Redirect to the page
     * @param string $page
     */
    function redirect($page = null)
    {
//        $this->protocal_array    = explode('/', $_SERVER['SERVER_PROTOCOL']);
//        $this->host              = $_SERVER['HTTP_HOST'] . '/';
//        $this->protocal          = strtolower($this->protocal_array[0]) . '://';
//        $this->request_uri_array = explode('/', $_SERVER['REQUEST_URI']);
//        $this->request_uri       = ($this->host == 'localhost/') ? $this->request_uri_array[1] . '/' : '';
        
        if ($page != null)
        {            
//            $redirect_url = $this->protocal . $this->host . $this->request_uri . $page;
            $redirect_url = get_base_url() . $page;
            printf("<script>location.href='$redirect_url'</script>");
        }
        else
        {
//            return $this->protocal . $this->host . $this->request_uri;
            return get_base_url();
        }
    }

    /**
     * Check whether the user is logged in
     * @param int $role
     * @param bool $redirect
     * @return boolean
     */
    public function is_logged_in($role = 0, $redirect = true)
    {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '')
        {
            // Check role if it is passed: (For admin user)
            if ($role != 0)
            {
                if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == $role))
                {
                    return true;
                }
                else
                {
                    if ($redirect)
                    {
                        $this->redirect('index.php?page=login');
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            if ($redirect)
            {
                $this->redirect('index.php?page=login');
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Is the current user is admin
     * @return bool
     */
    public function is_admin()
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Send email
     * @param array $email_ids
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return boolean
     */
    public function send_email($email_ids, $subject = '', $message = '', $attachments = null)
    {
		
		
       
        if (empty($email_ids))
        {
            return false;
        }
        else
        {   
			
			
            $from_name   = (isset($email_ids['from_name'])) ? $email_ids['from_name'] : '';
            $from_email  = (isset($email_ids['from_email'])) ? $email_ids['from_email'] : '';
            $reply_name  = (isset($email_ids['reply_name'])) ? $email_ids['reply_name'] : '';
            $reply_email = (isset($email_ids['reply_email'])) ? $email_ids['reply_email'] : '';            
            $to_emails   = (isset($email_ids['to_email'])) ? $email_ids['to_email'] : array();                       
			
			
			
            //Create a new PHPMailer instance
            $mail            = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
           
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 1;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            //Set the hostname of the mail server
            $mail->Host      = 'smtp.gmail.com';

            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = 465;

            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'ssl';

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = "soumyaabhilash85@gmail.com";

            //Password to use for SMTP authentication
            $mail->Password = "sujathamohanan";
            //Set who the message is to be sent from
            $mail->setFrom($from_email, $from_name);
            //Set an alternative reply-to address
            $mail->addReplyTo($reply_email, $reply_name);
            //Set who the message is to be sent to            
            foreach($to_emails as $to_email)
            {
                $mail->addAddress($to_email['email'], $to_email['name']);
            }
            //Set the subject line
            $mail->Subject  = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($message);
            //Replace the plain text body with one created manually
            //$mail->AltBody  = $message;
            //Add attachments
            
             
           
            if (!empty($attachments))
            {
                foreach ($attachments as $at)
                {
                    $mail->addAttachment($at);
                }
            }
            //send the message, check for errors
            if (!$mail->send())
            {
             echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo; exit;  
                return false;
            }
            else
            {
              
                return true;
            }
        }
    }

}
