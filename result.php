<?php
include 'includes.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// If the GET request "id" exists (poll id)...

if (isset($_GET['id'])) {
    // MySQL query that selects the poll records by the GET request "id"
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $msg = '';
    // Fetch the record
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the poll record exists with the id specified
    if ($poll) {
        
        // MySQL Query that will get all the answers from the "poll_answers" table ordered by the number of votes (descending)
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ? ORDER BY votes DESC');
        $stmt->execute([$_GET['id']]);
        // Fetch all poll answers
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Total number of votes, will be used to calculate the percentage
        $total_votes = 0;
        foreach ($poll_answers as $poll_answer) {
            // Every poll answers votes will be added to total votes
            $total_votes += $poll_answer['votes'];
        }
    } else {
        
        $msg ='Poll with that ID does not exist.';
        
    }
} else {
    $msg ='No poll ID specified.';
        
}
?>
<?=template_header('Poll Results')?>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if ($msg): ?>
    <div class="showback">
    <div class="alert alert-danger"><b><?=$msg?></b></div>
     </div>
    <?php else: ?>
<div class="showback">
<h4><i class="fa fa-angle-right"></i> <?=$poll['title']?></h4>
    <b><?=$poll['desc']?></b>
    <?php foreach ($poll_answers as $poll_answer): ?>
    <p><?=$poll_answer['title']?> <span>(<?=$poll_answer['votes']?> Votes)</span></p>
    <div class="progress progress-striped active">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow=<?=@round(($poll_answer['votes']/$total_votes)*100)?>% aria-valuemin="0" aria-valuemax="100" style="width: <?=@round(($poll_answer['votes']/$total_votes)*100)?>%">
            <span class="sr-only"><?=@round(($poll_answer['votes']/$total_votes)*100)?>% Complete</span>
                <b class="dark"><?=@round(($poll_answer['votes']/$total_votes)*100)?>%</b>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
<?=template_footer()?>