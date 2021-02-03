<?php
include 'includes.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// MySQL Query that will get all the answers from the "poll_answers" table ordered by the number of votes (descending)
$stmt = $pdo->prepare('SELECT * FROM polls');
$stmt->execute();
// Fetch all polls
// $polls = $stmt->fetch(PDO::FETCH_ASSOC);
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($polls) {
	    // MySQL Query that will get all the answers from the "poll_answers" table ordered by the number of votes (descending)
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ? ORDER BY votes DESC');
        // Fetch all poll answers
          // Total number of votes, will be used to calculate the percentage
    
    $color = 0;   
}
else {
// Output msg
$msg = 'No Polls available for display!';
}
?>
<?=template_header('Poll Results')?>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if ($msg): ?>
    <div class="showback">
    <div class="alert alert-danger"><b><?=$msg?></b></div>
     </div>
    <?php else: ?>
<?php foreach ($polls as $poll):
    $color = $poll['col_id']; 
    if ($color==1) {
         $styl = 'badge-red';
    }
    elseif ($color==2) {
        $styl = 'badge-green';
   }
   elseif ($color==3) {
    $styl = 'badge-purple';
    }
    elseif ($color==4) {
        $styl = 'badge-white';
    }
?> 
    <div class="showback">
    <div class="badge-r2  <?=$styl?>"><small><?=$poll['title']?></small></div>
    <h4><i class="fa fa-angle-right"></i> <?=$poll['title']?></h4>
    <b><?=$poll['desc']?></b>
    <?php 
            $stmt->execute([$poll['id']]);
            $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total_votes = 0;
            foreach ($poll_answers as $poll_answer) {
                    // Every poll answers votes will be added to total votes
                    $total_votes += $poll_answer['votes'];
                }
            
          foreach ($poll_answers as $poll_answer): ?>

    <p><?=$poll_answer['title']?> <span>(<?=$poll_answer['votes']?> Votes)</span></p>
    <div class="progress progress-striped active">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow=<?=@round(($poll_answer['votes']/$total_votes)*100)?>% aria-valuemin="0" aria-valuemax="100" style="width: <?=@round(($poll_answer['votes']/$total_votes)*100)?>%">
            <span class="sr-only"><?=@round(($poll_answer['votes']/$total_votes)*100)?>% Complete</span>
            <b class="dark"><?=@round(($poll_answer['votes']/$total_votes)*100)?>%</b>
        </div>
    </div>
<?php endforeach;?>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
<?=template_footer()?>