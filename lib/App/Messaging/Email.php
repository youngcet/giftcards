<?php
    
    namespace App\Messaging;
    
    trait Email
    {
        public static function sendMail ($msg)
        {
            if (! is_array ($msg))
            {
                return new App\Custom\Error (-1, 'Not an array reference');
            }
            
            # check that values are provided
            if (empty ($msg['recipient']) || empty ($msg['subject']) || empty ($msg['message']) || empty ($msg['from']) 
                || empty ($msg['replytoName']) || empty ($msg['replyto']))
            {
                return new App\Custom\Error (-1, 'one or more values passed is empty');
            }

            // set the headers
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'Reply-To: '.$msg['replytoName'].' <'.$msg['replyto'].'>';
            $headers[] = (! empty ($msg['fromName'])) ? 'From: '.$msg['fromName'].' <'.$msg['from'].'>' : 'From: '.$msg['replytoName'].' <'.$msg['from'].'>';

            # split the recipients (incase there's multiple recipients)
            $recipients = preg_split ('/\,/', $msg['recipient']);

            $results = array();
            $count = 0;
            if (is_array($recipients))
            {
                foreach ($recipients as $recipient)
                {
                    // send the email
                    if (! filter_var ($recipient, FILTER_VALIDATE_EMAIL) || ! mail (trim ($recipient), $msg['subject'], $msg['message'], implode ("\r\n", $headers), "-odb -f ".$msg['from']))
                    {
                        $error = (! filter_var ($recipient, FILTER_VALIDATE_EMAIL)) ? 'Invalid Email Address' : error_get_last()['message'];
                        $results[] = array ('recipient' => trim ($recipient), 'status' => 'failed', 'error' => $error, 'code' => 110);
                    }
                    else
                    {
                        $results[] = array ('recipient' => trim ($recipient), 'status' => 'success');
                    }
                }
            }
            else
            {
                if (! filter_var ($recipient, FILTER_VALIDATE_EMAIL) || ! mail (trim($recipients), $msg['subject'], $msg['message'], implode("\r\n", $headers), "-odb -f ".$msg['from']))
                {
                    $error = (! filter_var ($recipient, FILTER_VALIDATE_EMAIL)) ? 'Invalid Email Address' : error_get_last()['message'];
                    $results[] = array ('recipient' => trim ($recipients), 'status' => 'failed', 'error' => $error, 'code' => 110);
                }
                else
                {
                    $results[] = array ('recipient' => trim ($recipients), 'status' => 'success');
                }
            }
            
            return $results;
        }
    }
?>