<?php
include 'includes.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// MySQL query that selects all the polls and poll answers
$stmt = $pdo->query('SELECT p.*, GROUP_CONCAT(pa.title ORDER BY pa.id) AS answers FROM polls p LEFT JOIN poll_answers pa ON pa.poll_id = p.id GROUP BY p.id');
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?=template_header('Polls')?>
	<p>Welcome to the index page, you can view the list of polls below.</p>
    
     <!-- row -->
     <div class="row mt">
          <div class="col-md-12">
            <div class="content-panel">
            <?php if ($polls): ?>
    <div class="showback">
    <table class="table table-striped table-advance table-hover">
                <h4><i class="fa fa-angle-right"></i> Advanced Table</h4>
                <hr>
                <thead>
                  <tr>
                    <th><i class="fa fa-bullhorn"></i> #</th>
                    <th class="hidden-phone"><i class="fa fa-question-circle"></i> Posts</th>
                    <th><i class="fa fa-bookmark"></i> Aspirants</th>
                    <th><i class=" fa fa-edit"></i> Symbol</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($polls as $poll): ?>
                  <tr>
                    <td>
                      <a href="basic_table.html#"><?=$poll['id']?></a>
                    </td>
                    <td class="hidden-phone"><?=$poll['title']?></td>
                    <td><?=$poll['answers']?> </td>
                    <td><span class="label label-success label-mini">active</span></td>
                    <td>
                      <a href="vote.php?id=<?=$poll['id']?>"  class="btn btn-success btn-xs" title="View Poll"><i class="fa fa-check"></i></a>
                      
                      <a href="delete.php?id=<?=$poll['id']?>" class="btn btn-danger btn-xs" title="Delete Poll"><i class="fa fa-trash-o "></i></a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
     </div>
     <a href="create.php" class="btn btn-round btn-info">Add Poll</a>
    <?php else: 
        $msg = 'No Polls available for display!';?>
        <div class="alert alert-danger"><b><?=$msg?></b></div>
        <a href="create.php" class="btn btn-round btn-primary">Create Poll</a>
    <?php endif; ?>
            </div>
            <!-- /content-panel -->
          </div>
          <!-- /col-md-12 -->
        </div>
        <!-- /row -->
        
	
</div>

<?=template_footer()?>