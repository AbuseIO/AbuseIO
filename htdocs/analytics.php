<?php
    $title = 'Analytics and statistics';
    include('../lib/core/loader.php');
    include('../lib/frontend/top.php');
    $summary = reportSummary("3650");
?>
<div class="row">
    <div class="col-md-6">
        <div id="summary">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                      <th>Classification</th>
                      <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($summary as $nr => $element) {
                            echo "<tr><td><a href='reports.php?Class=${element['Class']}'>${element['Class']}</a></td><td>${element['Count']}</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('../lib/frontend/bottom.php'); ?>
