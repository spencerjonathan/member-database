<?php

abstract class EmailHelper
{

    public static function sendEmail($email, $subject, $body, $isHtml=false) {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $fromname = $config->get('fromname');
        $mailfrom = $config->get('mailfrom');
        $site = $config->get('sitename');
        $mailer->isHtml($isHtml);
        
        return $mailer->sendMail($mailfrom, $fromname, $email, $site . " - " . $subject, $body);
    }
    
    public static function createToken() {
        // Set the confirmation token.
        return JApplicationHelper::getHash(JUserHelper::genRandomPassword());
    }
    
    public static function storeToken($email, $hashedToken)
    {
        $db = JFactory::getDbo();
        $currentDate = date('Y-m-d H:i:s');

        $expiryDate = new DateTime();
        $expiryDate->add(new DateInterval("P5D"));

        $expiryDateText = $expiryDate->format('Y-m-d H:i:s');

        // Create a new query object.
        $query = $db->getQuery(true);

        // Insert columns.
        $columns = array(
            'email',
            'hash_token',
            'expiry_date',
            'created_date'
        );

        // Insert values.
        $values = array(
            $db->quote($email),
            $db->quote($hashedToken),
            $db->quote($expiryDateText),
            $db->quote($currentDate)
        );

        // Prepare the insert query.
        $query->insert($db->quoteName('#__md_member_token'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));

        // Set the query using our newly populated query object and execute it.
        $db->setQuery($query);
        $result = $db->execute();

        return true;
    }
    
    public static function emailAddressExistsInDB($email) {
        // Find the user id for the given email address.
        $db = JFactory::getDbo ();
        $query = $db->getQuery(true)
        ->select('count(*)')
        ->from($db->quoteName('#__md_member'))
        ->where($db->quoteName('email') . ' = ' . $db->quote($email));
        
        // Get the user object.
        $db->setQuery($query);
        
        try
        {
            $memberCount = $db->loadResult();
        }
        catch (RuntimeException $e)
        {
            $this->setError(JText::sprintf('Error searching for email address', $e->getMessage()), 500);
            
            return false;
        }
        
        // Check for a user.
        if (empty($memberCount) || $memberCount == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    
}

?>
