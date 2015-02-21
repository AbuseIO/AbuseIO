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
        //TODO Unhandled mail
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
        empty($structure->headers['subject']) ||
        empty($structure->headers['message-id'])
    ) {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse email due to missing fields");
        return false;
    }

    if(KEEP_MAILS == true) {
        file_put_contents(APP.'/archive/'.str_replace(array(" ","(",")","/"), "_", $structure->headers['message-id']).".eml", $raw);      
    }

    if (isset($structure->body)) {
        $plain .= $structure->body;
    }
    if(isset($structure->parts)){
        $message_store = APP.'/tmp/'.substr( md5(rand()), 0, 8);
        foreach($structure->parts as $part){
            if (isset($part->disposition) && $part->disposition=='attachment'){
                $i++;
                $attachment_path = "${message_store}/${i}/";
                $attachment_file = $attachment_path . $part->d_parameters['filename'];

                if (!mkdir($attachment_path, 0777, true)) {
                    //FATAL ERROR LOG - Failed to create message store
                }

                file_put_contents($attachment_file, $part->body);
                if (substr($part->d_parameters['filename'], -4, 4) == ".zip") {
                    $zip = new ZipArchive;
                    if (true === $zip->open($attachment_file)) {
                        $filename = $zip->getNameIndex('0');
                        $zip->extractTo($attachment_path, array($zip->getNameIndex('0')));
                        unlink($attachment_file);
                    } else {
                        //FATAL ERROR LOG - ERROR PARSING
                    }
                    $attachments[$i] = substr($part->d_parameters['filename'], 0, -4);
                } else {
                    $attachments[$i] = $part->d_parameters['filename'];
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
                    if(strpos($sp->headers['content-type'],'text/html')!==false){
                        $html .= $sp->body;
                    }
                    /*
                    Removed this part and replace  with ARF matching a few lines up
                    if (strpos($sp->headers['content-type'],'multipart/alternative')!==false){
                        // Seems we got a multipart message. Lets parse this for possible ARF handling
                        $arf['headers'] = $sp->headers;
                        foreach($sp->parts as $multipartSP){

                            if(strpos($multipartSP->headers['content-type'],'text/plain')!==false){
                                $arf['plain'] .= $multipartSP->body;
                            }
                            if(strpos($multipartSP->headers['content-type'],'text/html')!==false){
                                $arf['html'] .= $multipartSP->body;
                            }
                        }
                    }
                    */
                }

            } elseif (isset($part->headers['content-type'])) {
                if (strpos($part->headers['content-type'],'name=')!==false){
                    // This is a mime multipart attachment!
                    $i++;

                    $regex = "name=\"(.*)\";";
                    preg_match("/${regex}/m", $part->headers['content-type'], $match);
                    if (count($match) === 2) {
                        $filename = $match[1];
                        $attachment_path = "${message_store}/${i}/";
                        $attachment_file = $attachment_path . $filename;

                        if (!mkdir($attachment_path, 0777, true)) {
                            //FATAL ERROR LOG - Failed to create message store
                        }

                        file_put_contents($attachment_file, $part->body);
                        $attachments[$i] = $filename;

                    } else {
                        // Unhandled mime exit
                        return false;
                    }
                } elseif(strpos($part->headers['content-type'],'text/plain')!==false){
                    $plain .= $part->body;
                } elseif(strpos($part->headers['content-type'],'text/html')!==false){
                    $html .= $part->body;
                /*
                Removed this part and replace  with ARF matching a few lines up
                } elseif(strpos($part->headers['content-type'],'message/feedback-report')!==false){
                    $arf['report'] .= $part->body;
                */
                } else {
                    //Todo: unknown content type should be handled
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

?>
