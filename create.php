<?php
include 'includes.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Check if POST variable "title" exists, if not default the value to blank, basically the same for all variables
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
    // Insert new record into the "polls" table
    $stmt = $pdo->prepare('INSERT INTO polls VALUES (NULL, ?, ?)');
    $stmt->execute([$title, $desc]);
    // Below will get the last insert ID, this will be the poll id
    $poll_id = $pdo->lastInsertId();
    // Get the answers and convert the multiline string to an array, so we can add each answer to the "poll_answers" table
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
    foreach ($answers as $answer) {
        // If the answer is empty there is no need to insert
        if (empty($answer)) continue;
        // Add answer to the "poll_answers" table
        $stmt = $pdo->prepare('INSERT INTO poll_answers VALUES (NULL, ?, ?, 0)');
        $stmt->execute([$poll_id, $answer]);
    }
    // Output message
    $msg = 'Created Successfully!';
}
//else
?>
<?=template_header('Create Poll')?>
<div class="col-lg-12">
  <div class="form-panel">
    <div class=" form">
      <form class="cmxform form-horizontal style-form" id="commentForm" method="post" action="create.php">
        <div class="form-group ">
          <label for="title" class="control-label col-lg-2">Post (required)</label>
            <div class="col-lg-10">
              <input class=" form-control" name="title" id="title" minlength="2" type="text" required />
            </div>
        </div>
        <!-- <div class="form-group ">
          <label for="symbol" class="control-label col-lg-2">Symbol (optional)</label>
          <div class="col-lg-10">
            <input class="form-control " id="symbol" type="text" name="symbol"  />
          </div>
        </div> -->
        <div class="form-group ">
          <label for="desc" class="control-label col-lg-2">Description</label>
          <div class="col-lg-10">
            <input class="form-control " id="desc" type="text" name="desc" />
          </div>
        </div>
        <div class="form-group ">
          <label for="answers" class="control-label col-lg-2">Aspirants (per line)</label>
          <div class="col-lg-10">
            <textarea class="form-control " id="answers" name="answers" required></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10">
            <button class="btn btn-theme" type="submit">Save</button>
            <button class="btn btn-theme04" type="button">Cancel</button>
          </div>
        </div>
                </form>
                <?php if ($msg): ?>
                  <div class="alert alert-success"><b>Your poll is <?=$msg?></b></div>
    <?php endif; ?>
</div>
              </div>
            </div>
            <!-- /form-panel -->
          </div>
          <!-- /col-lg-12 -->
    

<?=template_footer()?>