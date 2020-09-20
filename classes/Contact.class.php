<?php

require('mail/sendmail.php');

use Database as DB;

class Contact extends DB\TinyMVCDatabaseObject{
    const tablename  = 'user';
    
    public function putMessage($email, $subject, $message, $locale, $username){
        global $localization;
        
        if ($subject == 0){
            $subject_text = "TopReal Contact request: Error";
        }
        elseif ($subject == 1){
            $subject_text = "TopReal Contact request: Question";
        }
        elseif ($subject == 2){
            $subject_text = "TopReal Contact request: Suggestion";
        }
        
        $rand = uniqid();
        
        try{
            $response = sendMail("redb.dev@gmail.com", "Top Real Services Limited", "redb.dev@gmail.com", $subject_text." ".$rand, "From email: ".$email.($username != -1 ? "<br>Name: ".$username : "")."<br>Text: ".$message);
            $response = sendMail("redb.dev@gmail.com", "Top Real Services Limited", $email, $localization->getVariable($locale, "contact_form_submitted").$rand, "<div style='direction:".($localization->isArabian($locale) ? "rtl" : "ltr")."'>".$localization->getVariable($locale, "dear_customer")
                    . "<p>".$localization->getVariable($locale, "contact_email_message_1")
                    . "<br>".$localization->getVariable($locale, "contact_email_message_2")
                    . "<p>".$message.""
                    . "<p>".$localization->getVariable($locale, "contact_email_message_3")
                    . "<br>".$localization->getVariable($locale, "contact_email_message_4")
                    . "<br>".$localization->getVariable($locale, "contact_email_message_5")
                    . "<p>".$localization->getVariable($locale, "contact_email_message_6")."</div>");
        }
        catch (Exception $e){
            $response = ['error' => ['code' => $e->getCode(), 'description' => $e->getMessage()]];
        }
        
        return $response;
    }
}
