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
?>
<?=template_header('votes')?>
<p>Form inputs</p>
<?php
@$poll=$_GET['poll']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not. 
echo "Data Error";
exit;
}
///////// Getting the data from Mysql table for first list box//////////
$quer2= $pdo->prepare("SELECT DISTINCT poll, id FROM polls order by poll"); 
///////////// End of query for first list box////////////

/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
if(isset($cat) > 0){
$quer="SELECT DISTINCT title FROM poll_answers where poll_id=$cat order by subcategory"; 
}else{$quer="SELECT DISTINCT subcategory FROM subcategory order by subcategory"; } 
////////// end of query for second subcategory drop down list box ///////////////////////////

echo "<form method=post name=f1 action='dd-check.php'>";
/// Add your form processing page address to action in above line. Example  action=dd-check.php////
//////////        Starting of first drop downlist /////////
echo "<select name='poll' onchange=\"reload(this.form)\"><option value=''>Select one</option>";
foreach ($dbo->query($quer2) as $noticia2) {
if($noticia2['cat_id']==@$cat){echo "<option selected value='$noticia2[cat_id]'>$noticia2[category]</option>"."<BR>";}
else{echo  "<option value='$noticia2[cat_id]'>$noticia2[category]</option>";}
}
echo "</select>";
//////////////////  This will end the first drop down list ///////////

//////////        Starting of second drop downlist /////////
echo "<select name='subcat'><option value=''>Select one</option>";
foreach ($dbo->query($quer) as $noticia) {
echo  "<option value='$noticia[subcategory]'>$noticia[subcategory]</option>";
}
echo "</select>";
//////////////////  This will end the second drop down list ///////////
?>
<br><br>
<a href=dd.php>Reset and start again</a>
<br><br>
<?=template_footer()?>