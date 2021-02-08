<?php
include 'includes.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// If the GET request "id" exists (poll id)...
if (isset($_GET['id'])) {
    // MySQL query that selects the poll records by the GET request "id"
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the record
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the poll record exists with the id specified
    if ($poll) {
        // MySQL query that selects all the poll answers
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);
        // Fetch all the poll anwsers
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // If the user clicked the "Vote" button...
        if (isset($_POST['poll_answer'])) {
            // Update and increase the vote for the answer the user voted for
            $stmt = $pdo->prepare('UPDATE poll_answers SET votes = votes + 1 WHERE id = ?');
            $stmt->execute([$_POST['poll_answer']]);
            // Redirect user to the result page
            header ('Location: result.php?id=' . $_GET['id']);
            exit;
        }
    } else {
        
        $msg ='Poll with that ID does not exist.';
        
    }
} else {
    $msg ='No poll ID specified.';
        
}
?>
<?=template_header('Poll Vote')?>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if ($msg): ?>
    <div class="showback">
    <div class="alert alert-danger"><b><?=$msg?></b></div>
     </div>
    <?php else: ?>
        <div class="showback">
<h4><i class="fa fa-angle-right"></i> <?=$poll['title']?></h4>
    <b><?=$poll['desc']?></b>
    <form action="vote.php?id=<?=$_GET['id']?>" method="post">
        <?php for ($i = 0; $i < count($poll_answers); $i++): ?>
        <div class="radio">
            <label>
                <input type="radio" name="poll_answer" value="<?=$poll_answers[$i]['id']?>"<?=$i == 0 ? ' checked' : ''?>>
                <?=$poll_answers[$i]['title']?>
            </label>
        </div>
        <?php endfor; ?>
        <div>
            <!-- <input type="submit" value="Vote" class="btn btn-theme"> -->
            <a href="result.php?id=<?=$poll['id']?>" class="btn btn-theme02"><i class="fa fa-check"></i>View Result </button></a>
            <a href="#"class="btn btn-theme"><i class="fa fa-check"></i>Vote </button></a>
        </div>
    </form>
</div>
<?php endif; ?>
<?=template_footer()?>