<?php
/*
** Parser       : AbuseHub / AbuseIX
** Description  : The parser will read and parse AbuseHub mails
** Configuration: You can define the following settings in settings.conf
**                to overwrite the defaults.
**                ABUSEHUB_DEFAULTTYPE: Report type (default: INFO)
**                ABUSEHUB_FEED_IGNORE: What feeds to ignore (default: none)
**                ABUSEHUB_FEED_ABUSE: What feeds to mark as 'ABUSE' (default: none)
**                ABUSEHUB_FEED_INFO: What feeds to mark as 'INFO' (default: none)
*/
function parse_abusehub($message) {

    $source = "Abusehub";
    $default_type = 'INFO';

    // Ignore some feeds if an ignore list exists in the configuration
    $feed_ignore = (defined('ABUSEHUB_FEED_IGNORE'))?explode(',',str_replace(' ', '', ABUSEHUB_FEED_IGNORE)):array();
    // Make feeds of type INFO by default and mark the ones in this list as type ABUSE
    $feed_type_abuse = (defined('ABUSEHUB_FEED_ABUSE'))?explode(',',str_replace(' ', '', ABUSEHUB_FEED_ABUSE)):array();
    $feed_type_info = (defined('ABUSEHUB_FEED_INFO'))?explode(',',str_replace(' ', '', ABUSEHUB_FEED_INFO)):array();

    if (defined('ABUSEHUB_DEFAULTTYPE')) {
        $default_type = ABUSEHUB_DEFAULTTYPE;
    }

    foreach( $message['attachments'] as $attachmentID => $attachment) {

    	// skip extra info from reliable notifier for now and use abusehub csv format only 
    	if (!preg_match("/^abusehubcsv\-.*\.csv/i", $attachment))
    		continue;

    	$reports = csv_to_array("${message['store']}/${attachmentID}/${attachment}");

        if(!is_array($reports)) {
            logger(LOG_ERR, __FUNCTION__ . " A parser error was detected. Will not try to continue to parse this e-mail");
            logger(LOG_WARNING, __FUNCTION__ . " FAILED message from ${source} subject ${message['subject']}");
            return false;
        }

        // This map makes the reports a bit nicer
        $fieldMap = array (
            'reliable_notifier'             => 'Reliable Notifier',
            'report_type'                   => 'Report Type',
            'infection_type'                => 'Infection Type',
            'event_date'                    => 'Event Date',
            'event_time'                    => 'Event Time',
            'src_asn'                       => 'Source ASN',
            'src_ip'                        => 'Source IP',
            'src_port'                      => 'Source Port',
            'protocol'                      => 'Protocol',
            'original_hotmail_recipient'    => 'Original Hotmail Recipient',
            'hotmail_reporting_ip'          => 'Hotmail Reporting URL',
            'dst_asn'                       => 'Destination ASN',
            'dst_ip'                        => 'Destination IP',
            'dst_port'                      => 'Destination Port',
            'agent_id'                      => 'Agent ID',
            'src_url'                       => 'Source URL',
            'dst_url'                       => 'Destination URL',
            'src_email'                     => 'Source Email',
            'dst_email'                     => 'Destination Email',
            'src_hostname'                  => 'Source Hostname',
            'dst_hostname'                  => 'Destination Hostname',
            'correlation_score1'            => 'Correlation Score 1',
            'correlation_score2'            => 'Correlation Score 2',
            'correlation_score3'            => 'Correlation Score 3',
            'correlation_score4'            => 'Correlation Score 4',
            'priority'                      => 'Priority',
        );

        foreach($reports as $id => $report) {
            $fields = array_keys($report);
            $information = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'dst_asn':
                    case 'src_asn':
                        $information[$fieldMap[$field]] = '<a href="http://viewdns.info/asnlookup/?asn='.$report[$field].'" target="_blank">' .$report[$field]. '</a>';
                        break;
                    default:
                        $information[$fieldMap[$field]] = $report[$field];
                }
            }

            $class = $report['report_type'];
            $timestamp = strtotime($report['event_date'] . ' ' . $report['event_time']);
            $type = $default_type;
            $searchstr = str_replace(' ','_',$class);
            if (in_array($searchstr, $feed_type_info))
                $type = 'INFO';
            elseif (in_array($searchstr, $feed_type_abuse))
                $type = 'ABUSE';
            elseif (in_array($searchstr, $feed_ignore)) {
                logger(LOG_WARNING, __FUNCTION__ . " Feed \"${searchstr}\" in ABUSEHUB_IGNOREFEEDS, SKIPPED message from ${source} subject ${message['subject']}");
                continue;
            }

            $outReport = array(
                'source'        => $source,
                'ip'            => $report['src_ip'],
                'class'         => $class,
                'type'          => $type,
                'timestamp'     => $timestamp,
                'information'   => $information,
                'domain'        => $report['src_url'],
                'uri'           => '/' 
            );

            $reportID = reportAdd($outReport);
            if (!$reportID) return false;
            if(KEEP_EVIDENCE == true && $reportID !== true)
                evidenceLink($message['evidenceid'], $reportID);
        }
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
