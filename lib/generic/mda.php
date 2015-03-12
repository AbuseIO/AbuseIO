<?PHP
/*
This include file contains functions to handle interactions from a MTA

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

function receive_mail($call) {
    require_once 'Mail/mimeDecode.php';

    $params['include_bodies'] = true;
    $params['decode_bodies']  = true;
    $params['decode_headers'] = true;

    if ($call['type'] == "INTERNAL") {
        $raw        = $call['message'];
    } elseif ($call['type'] == "EXTERNAL") {
        $raw        = file_get_contents("php://stdin");
    } else {
        logger(LOG_ERR, __FUNCTION__ . " was called, but was unable to determin if this was internally passed or not");
        return false;
    }

    $decoder        = new Mail_mimeDecode($raw);
    $structure      = $decoder->decode($params);
    $html           = "";
    $plain          = "";
    $i              = 0;
    $message_store  = "";
    $attachments    = array();
    $arf            = array('headers' => '', 'plain' => '', 'html' => '', 'report' => '');

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
                $i++;
                $attachment_file = "${message_store}/${i}/" . $part->d_parameters['filename'];
                if ($saved_file = save_attachment($part->headers['content-type'], $attachment_file, $part->body)) {
                    $attachments[$i] = $saved_file;
                }
            } elseif (isset($part->headers['content-type']) && strpos($part->headers['content-type'], "message/feedback-report") !== false) {
                // This is a ARF report feedback
                $arf['report'] = $part->body;

            } elseif (isset($part->headers['content-type']) && strpos($part->headers['content-type'], "message/rfc822") !== false) {
                // This is a ARF report message
                $arf['headers'] = $part->parts[0]->headers;

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
						$i++;
                        $attachment_file = "${message_store}/${i}/". $sp->d_parameters['filename'];
                        if ($saved_file = save_attachment($sp->headers['content-type'], $attachment_file, $sp->body)) {
                            $attachments[$i] = $saved_file;
                        }
                    }
                }

            } elseif (isset($part->headers['content-type'])) {
                if (strpos($part->headers['content-type'],'name=')!==false){
                    // This is a mime multipart attachment!
                    $regex = "name=\"(.*)\"";
                    preg_match("/${regex}/m", $part->headers['content-type'], $match);
                    if (count($match) === 2) {
                        $i++;
                        $filename = $match[1];
                        $attachment_file = "${message_store}/${i}/" . $filename;
                        if ($saved_file = save_attachment($part->headers['content-type'], $attachment_file, $part->body)) {
                            $attachments[$i] = $saved_file;
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
function save_attachment($type, $file, $body) {
    $attachment_path = dirname($file);
    if (!file_exists($attachment_path) && !mkdir($attachment_path, 0777, true)) {
        logger(LOG_ERR, "Error creating message store ${attachment_path}");
    }

    // Save attachment
    if(!file_put_contents($file, $body)) {
        logger(LOG_ERR, "Error saving attachment ${file}");
        return false;
    }

    switch ($type) {
        case 'application/zip':
        case 'multipart/x-zip':
            // extract zip file
            $zip = new ZipArchive;
            if (true === $zip->open($file)) {
                $zip->extractTo($attachment_path, array($zip->getNameIndex('0')));
                unlink($file);
                return substr(basename($file), 0, -4);
            } else {
                logger(LOG_ERR, "Error extracting zip file to ${attachment_path}");
                return false;
            }
            break;
        default:
            break;
    }
    return basename($file);
}
?>
