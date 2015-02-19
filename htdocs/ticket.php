<?php
    $title = 'Ticket '.$_GET['id'];
    include('../bin/loader.php');
    include('../lib/frontend/top.php');

if (empty($_GET['id'])) {
    echo '<h2>404 - Invalid ticket</h2>';
    include('../lib/frontend/bottom.php');
    die();
}

$report = reportGet($_GET['id']);

if (!$report) {
    echo '<h2>404 - Invalid ticket</h2>';
    include('../lib/frontend/bottom.php');
    die();
}

?>

<dl class="dl-horizontal">
    <dt>IP address</dt>
    <dd><?php echo "<a href='reports.php?IP=${report['IP']}'>${report['IP']}</a>"; ?></dd>

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

    <dt>Customer Code</dt>
    <dd><?php echo "<a href='reports.php?CustomerCode=${report['CustomerCode']}'>${report['CustomerCode']}</a>"; ?></dd>

    <dt>Customer Name</dt>
    <dd><?php echo "<a href='reports.php?CustomerName=${report['CustomerName']}'>${report['CustomerName']}</a>"; ?></dd>

    <dt>Customer Contact(s)</dt>
    <dd><?php echo $report['CustomerContact']; ?></dd>

    <dt>Classification</dt>
    <dd><?php echo "<a href='reports.php?Class=${report['Class']}'>${report['Class']}</a>"; ?></dd>

    <dt>Source</dt>
    <dd><?php echo "<a href='reports.php?Source=${report['Source']}'>${report['Source']}</a>"; ?></dd>

    <dt>First Seen</dt>
    <dd><?php echo date("d-m-Y H:m", $report['FirstSeen']); ?></dd>

    <dt>Last Seen</dt>
    <dd><?php echo date("d-m-Y H:m", $report['LastSeen']); ?></dd>

    <dt>Report Count</dt>
    <dd><?php echo $report['ReportCount']; ?></dd>

    <dt>Notified Count</dt>
    <dd><?php echo $report['NotifiedCount']; ?></dd>

</dl>

<h2>Information</h2>

<dl class="dl-horizontal">
<?php
    $info_array = json_decode($report['Information'], true);
    foreach($info_array as $field => $value) {
        echo "<dt>${field}</dt>";
        echo "<dd>${value}</dd>";
    }
?>
</dl>

<?php
if (NOTES == true) {

if(isset($_GET['action']) && $_GET['action'] == 'addNote') {
    if(isset($_SERVER['REMOTE_USER'])) {
        $submittor = "Abusedesk (${_SERVER['REMOTE_USER']})";
    } else {
        $submittor = "Abusedesk";
    }

    reportNoteAdd($submittor, $_GET['id'], $_GET['Note']);
}
if(isset($_GET['action']) && $_GET['action'] == 'delNote' && is_numeric($_GET['noteid'])) {
    reportNoteDelete($_GET['noteid']);
}


?>
<h2>Notes</h2>

<table class="table table-striped table-condensed">
    <thead>
        <tr>
          <th width='125'>Date</td>
          <th width='175'>Submittor</td>
          <th>Note</td>
          <th width='1'> </td>
        </tr>
    </thead>
    <tbody>
<?php
$filter  = "AND ReportID = ${_GET['id']} ORDER BY Timestamp DESC";
$notes = reportNoteList($filter);

foreach($notes as $nr => $note) {
    echo "
    <tbody>
        <tr>
          <td>".date("d-m-Y H:m", $note['Timestamp'])."</td>
          <td>${note['Submittor']}</td>
          <td>${note['Text']}</td>
          <td><a href='?action=delNote&id=${_GET['id']}&noteid=${note['ID']}' title='Delete this note'>X<a/></td>
        </tr>
    ";
}
?>
    </tbody>
</table>

<br>

<form method='GET' action="ticket.php">
    <input type='hidden' name='action' value='addNote'>
    <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
    <div class="row">
        <div class="col-md-6 form-group form-group-sm">
            <label for='Ticket'>Create new note:</label>
            <textarea rows="4" cols="80" name='Note'></textarea>
        </div>
        <div class="col-md-12">
            <button type='submit' class="btn btn-primary">Add</button>
        </div>
    </div>
</form>

<?php } ?>
