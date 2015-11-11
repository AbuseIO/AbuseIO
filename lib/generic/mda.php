<?PHP
/******************************************************************************
* AbuseIO 3.0
* Copyright (C) 2015 AbuseIO Development Team (http://abuse.io)
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software Foundation
* Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
*******************************************************************************
*
* This include file contains functions to handle interactions from a MTA
*
******************************************************************************/


/*
** Function: delete_store
** Parameters:
**  message (array): The full message dataset returned from receive_mail function
** Returns: Nothing
*/
function delete_store($message) {
    if (isset($message['attachments']) && is_array($message['attachments'])) {
        foreach($message['attachments'] as $id => $attachment) {
            unlink("${message['store']}/${id}/${attachment}");
            rmdir("${message['store']}/${id}");
        }
        if (is_dir($message['store'])) {
            rmdir($message['store']);
        }
    }
}


/*
** Function: receive_mail
** Parameters:
**  call (array): 
**   type (string): required to be INTERNAL (using a passed along mail) or EXTERNAL (using STDIN)
**   message (string): required when type is EXTERNAL and should contain a full raw email (EML)
** Returns: The function will a direct exit value to the MTA either accepting or cancelling the message
*/
function receive_mail($call) {
    require_once 'Mail/mimeDecode.php';

    $params['include_bodies'] = true;
    $params['decode_bodies']  = true;
    $params['decode_headers'] = true;

    if ($call['type'] == "INTERNAL") {
        $raw = $call['message'];
    } elseif ($call['type'] == "EXTERNAL") {
        $raw = file_get_contents("php://stdin");
	// postfix gives you the data in mbox format (zee https://tools.ietf.org/html/rfc4155)
	// if the first row indicates it is a mbox style, we strip the first line
	if(strpos($raw,'From ') === 0) {
		$raw = preg_replace('/^.+\n/','',$raw);
	}
    } else {
        logger(LOG_ERR, __FUNCTION__ . " was called, but was unable to determin if this was internally passed or not");
        return false;
    }

    $decoder            = new Mail_mimeDecode($raw);
    $structure          = $decoder->decode($params);
    $html               = "";
    $plain              = "";
    $attachment_counter = 0;
    $message_store      = "";
    $attachments        = array();
    $arf                = array('headers' => '', 'plain' => '', 'html' => '', 'report' => '');

    // We cannot parse mail if some fields are unset
    if (
        empty($structure->headers['from']) ||
        empty($structure->headers['subject'])
    ) {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse email due to missing fields");
        return false;
    }

    if(KEEP_MAILS == true) {
        $archiveDir = APP . '/archive/' . date('Ymd') . '/';
        if (!is_dir($archiveDir)) {
            if(!mkdir($archiveDir)) {
                logger(LOG_ERR, __FUNCTION__ . " Unable to create archive subdir " . $archiveDir);
            } 
        }

        if (empty($structure->headers['message-id'])) {
            $archiveFile = rand(10,10) . ".eml";
        } else {
            $messageID = preg_replace('/[^a-zA-Z0-9_\.]/', '_', $structure->headers['message-id']);
            $archiveFile = $messageID . ".eml";      
        }

        if (!is_file($archiveDir.$archiveFile)) {
            file_put_contents($archiveDir.$archiveFile, $raw);
            logger(LOG_DEBUG, __FUNCTION__ . " Saved email message to " . $archiveDir . $archiveFile);
        } else {
            logger(LOG_ERR, __FUNCTION__ . " Unable to archive email because the file already exists " . $archiveDir . $archiveFile);
        }
    }

    if (isset($structure->body)) {
        $plain .= $structure->body;
    }

    if(isset($structure->parts)){
        $message_store = APP.'/tmp/'.substr( md5(rand()), 0, 8);
        foreach($structure->parts as $part){
            if (isset($part->disposition) && $part->disposition=='attachment'){
                $attachment_counter++;
                $attachment_file = "${message_store}/${attachment_counter}/" . $part->d_parameters['filename'];
                if (!empty($part->body) && $saved_file = save_attachment($attachment_file, $part->body)) {
                    $attachments[$attachment_counter] = $saved_file;
                }
            } elseif (isset($part->headers['content-type']) && strpos($part->headers['content-type'], "message/feedback-report") !== false) {
                // This is a ARF report feedback
                $arf['report'] = $part->body;

            } elseif (isset($part->headers['content-type']) && strpos($part->headers['content-type'], "message/rfc822") !== false) {
                // This is a ARF report message
                $arf['headers'] = $part->parts[0]->headers;

                // Skip this part if we're unable to determine content-type
                if (empty($part->parts[0]->headers['content-type'])) {
                    logger(LOG_ERR, "Unknown content type in parsing e-mail");
                    continue;
                }

                if(strpos($part->parts[0]->headers['content-type'],'text/plain')!==false){
                    $arf['plain'] .= $part->parts[0]->body;
                }
                if(strpos($part->parts[0]->headers['content-type'],'text/html')!==false){
                    $arf['html'] .= $part->parts[0]->body;
                }

            } elseif(isset($part->parts) && count($part->parts)>0) {
                foreach($part->parts as $sp){
                    // Plain text we just add to the body
                    if(strpos($sp->headers['content-type'],'text/plain')!==false){
                        $plain .= $sp->body;
                    }
                    // Plain html we just add to the body
                    if(strpos($sp->headers['content-type'],'text/html')!==false){
                        $html .= $sp->body;
                    }
                    // Save attachments
                    if(isset($sp->disposition) && $sp->disposition=='attachment'){
						$attachment_counter++;
                        $attachment_file = "${message_store}/${attachment_counter}/". $sp->d_parameters['filename'];
                        if (!empty($sp->body) && $saved_file = save_attachment($attachment_file, $sp->body)) {
                            $attachments[$attachment_counter] = $saved_file;
                        }
                    }
                }

            } elseif (isset($part->headers['content-type'])) {
                if (strpos($part->headers['content-type'],'name=')!==false){
                    // This is a mime multipart attachment!
                    $regex = "name=\"(.*)\"";
                    preg_match("/${regex}/m", $part->headers['content-type'], $match);
                    if (count($match) === 2) {
                        $attachment_counter++;
                        $filename = $match[1];
                        $attachment_file = "${message_store}/${attachment_counter}/" . $filename;
                        if (!empty($part->body) && $saved_file = save_attachment($attachment_file, $part->body)) {
                            $attachments[$attachment_counter] = $saved_file;
                        }
                    } else {
                        logger(LOG_ERR, "Unknown mime type in parsing e-mail");
                        return false;
                    }
                } elseif(strpos($part->headers['content-type'],'text/plain')!==false){
                    $plain .= $part->body;
                } elseif(strpos($part->headers['content-type'],'text/html')!==false){
                    $html .= $part->body;
                } else {
                    logger(LOG_ERR, "Unknown content type in parsing e-mail");
                    return false;
                }

            } else {
                $plain .= $part->body;
            }
        }
    }

    $plain = str_replace("=\n", "", $plain);
    $plain = str_replace("=20", "", $plain);

    $message['headers']     = $structure->headers;
    $message['from']        = $structure->headers['from'];
    $message['subject']     = $structure->headers['subject'];
    $message['body']        = $plain;
    $message['html']        = $html;
    $message['attachments'] = $attachments;
    $message['store']       = $message_store;
    $message['raw']         = $raw;

    if (strlen($arf['report']) > 1) {
        $message['arf']     = $arf;
    }

    if(KEEP_EVIDENCE == true) {
        // We will save evidence in the SQL databases for linking in CLI/Webgui or
        // to re-use that dataset. It will keep record of which cases are related
        // to a specific set of evidence records.
        $message['evidenceid'] = evidenceStore($message['from'], $message['subject'], $raw);
    }

    return $message;
}


/*
** Function: save_attachment
** Parameters:
**  type (string): The mime type of the file
**	file (string): The absolute filepath
**	body (string): The contents of the file
** Returns: The function will return the filename or FALSE when failed to save the file.
**
** Note: You can add more actions for specific file types
*/
function save_attachment($file, $body) {

    if (empty($body)) {
        logger(LOG_ERR, "Cannot save empty attachment ${attachment_path}");
        return false;
    }

    $file_info = pathinfo($file);
    if (!file_exists($file_info['dirname']) && !mkdir($file_info['dirname'], 0777, true)) {
        logger(LOG_ERR, "Error creating message store ${attachment_path}");
        return false;
    }

    // Save attachment
    if(!file_put_contents($file, $body)) {
        logger(LOG_ERR, "Error saving attachment ${file}");
        return false;
    }

    // We can't rely on mime-type, so we'll grab the extension so we work from there
    switch ($file_info['extension']) {
        case 'zip':
            // extract zip file
            $zip = new ZipArchive;
            if (true === $zip->open($file)) {
                $zip->extractTo($file_info['dirname'], array($zip->getNameIndex('0')));
                unlink($file);
                return $zip->getNameIndex('0');
            } else {
                logger(LOG_ERR, "Error extracting zip file to ". $file_info['dirname']);
                return false;
            }
            break;
        default:
            break;
    }
    return basename($file);
}


/*
** Function: bounce
** Parameters:
**  message (array): The full message dataset returned from receive_mail function
** Returns: The function will a direct exit value to the MTA either accepting or cancelling the message
*/
function bounce($message) {
    logger(LOG_WARNING, "Attempting to bounce message to admin because i was unabled to parse it");

    $tempfile = APP.'/tmp/'.mt_rand();
    file_put_contents($tempfile, $message['raw']);

    $bodytext = "AbuseIO tried to parse a message but was not able to.\n\n";
    if(!empty($message['from'])) {
        $bodytext .= "From: ${message['from']}\n";
    }
    if(!empty($message['subject'])) {
        $bodytext .= "Subject: ${message['subject']}\n";
    }
    $bodytext .= "\nYou will find the original email attached.\n";

    $email = new PHPMailer();
    $email->From      = NOTIFICATIONS_FROM_ADDRESS;
    $email->FromName  = NOTIFICATIONS_FROM_NAME;
    $email->Subject   = 'AbuseIO failed parsing attempt';
    $email->Body      = $bodytext;
    $email->AddAddress(FALLBACK_MAIL);
    $email->AddAttachment($tempfile, 'bounce.eml', 'base64', 'message/rfc822');

    if(!$email->Send()) {
        logger(LOG_ERR, "Bouncing to " . FALLBACK_MAIL . " failed.");
        unlink($tempfile);
        exit(1);

    } else {
        logger(LOG_WARNING, "Bounced to " . FALLBACK_MAIL . " successfully.");
        unlink($tempfile);
        exit(0);

    }
}
?>
