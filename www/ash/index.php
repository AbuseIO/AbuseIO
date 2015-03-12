<?php
include('../../lib/core/loader.php');

if (empty($_GET['id']) || empty($_GET['token']) || !is_numeric($_GET['id'])) {
    die('<h2>401 - Unauthorized</h2>');
}

if ($isset($_GET['lang']) && strlen($_GET['lang']) == 2){
    $infolang = $_GET['lang'];
} else {
    $infolang = 'en';
}

$report = reportGet($_GET['id']);
$token  = md5("${report['ID']}${report['IP']}${report['Class']}");

if ($_GET['token'] != $token) {
    die('<h2>401 - Unauthorized</h2>');
}

$title = "Ticket {$report['ID']}";

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

                <dt>Ticket status</dt>
                <dd><?php echo "<span class='label label-${labelClass[$report['Status']]}'>${report['Status']}</span>"; ?></dd>

            </dl>
            <a href='' class="btn btn-primary btn-sm">This issue has been resolved</a>
            <a href='' class="btn btn-default btn-sm">This issue can be ignored</a>
        </div>

        <?php
        $infotext = "infotext/${infolang}".str_replace(" ", "_", $report['Class']).".html";
        if (file_exists($infotext)) {
            echo '<div style="padding-top: 1em;">';
            include($infotext);
            echo '</div>';
        }
        ?>

    </div>
  </body>
</html>
