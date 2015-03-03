<?php
include('../../lib/core/loader.php');

if (empty($_GET['id']) || empty($_GET['token']) || !is_numeric($_GET['id'])) {
    die("error1");
}

$report = reportGet($_GET['id']);
$token  = md5("${report['ID']}${report['IP']}${report['Class']}");

if ($_GET['token'] != $token) {
    die("error2");
}
?>
<html>
<body>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
    $('#button').click(function(){
        if(!$('#iframe').length) {
                $('#iframeHolder').html('<iframe id="iframe" src="/ash/infotext/Open_DNS_Resolver.html" width="850" height="450"></iframe>');
        }
    });   
});
</script>

<table>
    <tr>
        <td><a href=''>Het is probleem verholpen</a></td>
        <td><a href=''>Dit is geen probleem</a></td>
        <td><u><a id="button">Ik wil meer informatie</a></u></td>
    </tr>
<table>

<div id="iframeHolder"></div>
