<?php
include('../../lib/core/loader.php');

if (empty($_GET['id']) || empty($_GET['token']) || !is_numeric($_GET['id'])) {
    die('<h2>401 - Unauthorized</h2>');
}

$report = reportGet($_GET['id']);
$token  = md5("${report['ID']}${report['IP']}${report['Class']}");

if ($_GET['token'] != $token) {
    die('<h2>401 - Unauthorized</h2>');
}
?>
<html>
<body>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
    $('#button').click(function(){
        if(!$('#iframe').length) {
                $('#iframeHolder').html('<iframe id="iframe" src="/ash/infotext/<?PHP echo str_replace(" ", "_", $report['Class']); ?>.html" width="850" height="450"></iframe>');
        }
    });   
});
</script>

<h1>Ticket <?php echo $report['ID'] . " - " . $report['IP']; ?></h1>

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
    <dd><?php echo $report['Type']; ?></dd>

    <dt>First Seen</dt>
    <dd><?php echo date("d-m-Y H:i", $report['FirstSeen']); ?></dd>

    <dt>Last Seen</dt>
    <dd><?php echo date("d-m-Y H:i", $report['LastSeen']); ?></dd>

    <dt>Ticket status</dt>
    <dd><?php echo $report['Status']; ?></dd>

</dl>

<table>
    <tr>
        <td><a href=''>This problem has been resolved</a></td>
        <td><a href=''>This problem can be ignored</a></td>
        <td><u><a id="button">More information</a></u></td>
    </tr>
<table>

<div id="iframeHolder"></div>
