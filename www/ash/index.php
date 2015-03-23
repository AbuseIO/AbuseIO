<?php
include('../../lib/core/loader.php');

if (empty($_GET['id']) || empty($_GET['token']) || !is_numeric($_GET['id'])) {
    die('<h2>401 - Unauthorized</h2>');
}

if (isset($_GET['lang']) && strlen($_GET['lang']) == 2){
    $infolang = $_GET['lang'];
} else {
    $infolang = 'en';
}

$report = reportGet($_GET['id']);
$token  = md5("${report['ID']}${report['IP']}${report['Class']}");

if ($_GET['token'] != $token) {
    die('<h2>401 - Unauthorized</h2>');
}

$title = "ASH - Ticket {$report['ID']}";

$labelClass = array(
    'ABUSE'     => 'warning',
    'INFO'      => 'info',
    'ALERT'     => 'danger',
    'OPEN'      => 'warning',
    'CLOSED'    => 'info',
    'ESCALATED' => 'danger',
    'NO'        => 'warning',
    'YES'       => 'info',
    '0'         => 'warning',
    '1'         => 'info',
); 

if (!empty($_GET['action'])) {
    if ($_GET['action'] == 'addNote') {
        if (strlen($_GET['noteMessage']) < 10) {
            $changeMessage = "<span class='label label-warning'>YOUR REPLY WAS NOT SUBMITTED, BECAUSE THERE WAS INSUFFICIANT INFORMATION IN THE NOTE!</span>";

        } elseif ($_GET['noteType'] == 'message') {
            reportNoteAdd('Customer', $_GET['id'], htmlentities(strip_tags($_GET['noteMessage'])));
            $changeMessage = "<span class='label label-info'>YOUR REPLY HAS BEEN REGISTERED</span>";

        } elseif ($_GET['noteType'] == 'ignore') {
            reportNoteAdd('Customer', $_GET['id'], htmlentities(strip_tags($_GET['noteMessage'])));
            reportIgnored($_GET['id']);
            $report['CustomerIgnored'] = 1;
            $report['CustomerResolved'] = 0;
            $changeMessage = "<span class='label label-info'>YOUR REPLY HAS BEEN REGISTERED AND YOU WILL NO LONGER RECEIVE NEW NOTIFICATIONS ON THIS EVENT</span>";

        } elseif ($_GET['noteType'] == 'resolve') {
            reportNoteAdd('Customer', $_GET['id'], htmlentities(strip_tags($_GET['noteMessage'])));
            reportResolved($_GET['id']);
            $report['CustomerIgnored'] = 0;
            $report['CustomerResolved'] = 1;
            $changeMessage = "<span class='label label-info'>YOUR REPLY HAS BEEN REGISTERED AND THE EVENT WAS MARKED AS RESOLVED</span>";

        } else {
            $changeMessage = "<span class='label label-warning'>UNKNOWN REPLY COMMAND</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container" style="padding-bottom: 4em;">
        <div>
            <h1><?php echo $title; ?></h1>

            <dl class="dl-horizontal">
                <dt>IP address</dt>
                <dd><?php echo $report['IP']; ?></dd>

                <?php
                    $reverse = gethostbyaddr($report['IP']);
                    if ($reverse != $report['IP'] && $reverse !== false) {
                ?>
                <dt>Reverse DNS</dt>
                <dd><?php echo gethostbyaddr($report['IP']); ?></dd>
                <?php } ?>

                <?php if (!empty($report['Domain'])) { ?>
                <dt>Domain</dt>
                <dd><?php echo $report['Domain']; ?></dd>
                <?php } ?>

                <?php if (!empty($report['URI'])) { ?>
                <dt>URI</dt>
                <dd><?php echo $report['URI']; ?></dd>
                <?php } ?>

                <dt>Classification</dt>
                <dd><?php echo $report['Class']; ?></dd>

                <dt>Source</dt>
                <dd><?php echo $report['Source']; ?></dd>

                <dt>Type</dt>
                <dd><?php echo "<span class='label label-${labelClass[$report['Type']]}'>${report['Type']}</span>"; ?></dd>

                <dt>First Seen</dt>
                <dd><?php echo date("d-m-Y H:i", $report['FirstSeen']); ?></dd>

                <dt>Last Seen</dt>
                <dd><?php echo date("d-m-Y H:i", $report['LastSeen']); ?></dd>

                <dt>Report count</dt>
                <dd><?php echo $report['ReportCount']; ?></dd>

                <dt>Ticket status</dt>
                <dd><?php echo "<span class='label label-${labelClass[$report['Status']]}'>${report['Status']}</span>"; ?></dd>

                <dt>Reply status</dt>
                <dd><?php
                    if($report['CustomerIgnored'] == 1) {
                        echo "<span class='label label-warning'>CUSTOMER IGNORED</span>";
                    } elseif($report['CustomerResolved'] == 1) {
                        echo "<span class='label label-info'>CUSTOMER RESOLVED</span>";
                    } else {
                        echo "<span class='label label-warning'>AWAITING REPLY</span>";
                    }
                ?></dd>

            </dl>
        </div>

        <?php
        if (!empty($changeMessage)) { echo $changeMessage; }

        $statictext = APP . "/etc/ash.template";
        if (file_exists($statictext)) {
            echo '<div style="padding-top: 1em;">';
            include($statictext);
            echo '</div>';
        }
        ?>

        <form method='GET'>
        <input type='hidden' name='action' value='addNote'>
        <input type='hidden' name='id'     value='<?php echo $_GET['id']; ?>'>
        <input type='hidden' name='token'  value='<?php echo $_GET['token']; ?>'>
        <div class="row">
            <div class="col-md-6 form-group form-group-sm">
                <label for='noteMessage'>Your reply : </label>
                <textarea rows="5" cols="79" name='noteMessage'></textarea>
            </div>
            <div class="col-md-6 form-group form-group-sm"><br>
                <input type="radio" name="noteType" value="message" checked>Reply<br>
<?php if($report['Type'] == "INFO") { ?>
                <input type="radio" name="noteType" value="ignore" onclick="javascript:alert('When setting the report to ignored you will no longer receive any notifications of this event. Be very carefull using this option!');">Reply and mark as ignored<br>
<?php } ?>
                <input type="radio" name="noteType" value="resolve" onclick="javascript:alert('If you mark this case as resolved and no new abuse is received within certain period then the ticket will be closed. Only mark the event as resolved when you actually resolved the issue.')";>Reply and mark as resolved<br>
                <br>
                <input type='submit' class='btn btn-primary btn-sm' name='' value='Submit'><br>
            </div>
        </div>
        </form>

        <?php
        $infotext = APP . "/www/ash/infotext/${infolang}/".str_replace(" ", "_", $report['Class']).".html";
        if (file_exists($infotext)) {
            echo '<div style="padding-top: 1em;">';
            include($infotext);
            echo '</div>';
        }
        ?>

    </div>
  </body>
</html>
