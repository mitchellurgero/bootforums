<?php
/*
post.php - The Juicy Script. Reads, and Parses the threads db files for posts from users.
Script Created by Mitchell Urgero
Date: Sometime in 2016 ;)
Website: https://urgero.org
E-Mail: info@urgero.org

Script is distributed with Open Source Licenses, do what you want with it. ;)
"I wrote this because I saw that there are not that many databaseless Forums for PHP. It needed to be done. I think it works great, looks good, and is VERY mobile friendly. I just hope at least one other person
finds this PHP script as useful as I do."

*/
session_start();
require("db.php");
require("config.php");
include("Parsedown.php");
include("ParsedownExtra.php"); //Might use ParsedownExtra down the road, who knows.
require("functions.php");
//Begin page
if($config['ssl'] == true){
	if($_SERVER["HTTPS"] != "on")
	{
    	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    	die();
	}
}
$usdata = $config['user_data'];
$thdata = $config['thread_data'];
include("header.php");
echo '<div class="container">';
//Body content
if($_GET['type'] == "new"){
	?>
	<div class="page-header">
	  	<h1><?= L("new.post") ?></h1>
	</div>
	<p><?= L("use.md") ?></p>
	<form action="submit.php" method="POST" id="new" role="form">
		<input style="display: none;" name="type" id="type" value="new"></input>
		<input class="form-control" name="post-id" id="post-id" placeholder="<?= L("post.name") ?>" maxlength="60"></input><br />
		<textarea name="text" id="text" rows="10" cols="100%" class="form-control"></textarea><br />
		<button type="submit" class="btn btn-primary lgn pull-right"><?= L("submit") ?></button>
	</form>
	<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?= L("new.post") ?>';
    });

</script>
	<?php
}
if($_GET['type'] == "reply"){
	?>
	<div class="page-header">
	  	<h1><?php $to = $_GET['post']; echo sprintf(L("reply.to"),file_get_contents("$thdata/$to.name")) ?></h1>
	</div>
	<?php
	if(!file_exists("$thdata/".$_GET['post'].'.dat')){ echo L("post.not.exist");} else {
	?>
	<p><?= L("use.md") ?></p>
	<form action="submit.php" method="POST">
		<input style="display: none;" name="type" id="type" value="reply"></input>
		<input style="display: none;" name="post-id" id="post-id" value="<?php echo $_GET['post']; ?>"></input>
		<textarea name="text" id="text" rows="10" cols="100%" class="form-control"></textarea><br />
		<button type="submit" class="btn btn-primary pull-right"><?= L("submit") ?></button>
	</form>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']; $to = $_GET['post']; echo" | ".sprintf(L("reply.to.2"),file_get_contents("$thdata/$to.name")) ?>';
    });

</script>
	<?php
	}
}
if($_GET['type'] == "edit"){
	?>
	<div class="page-header">
	  	<h1><?php $to = $_GET['post']; echo sprintf(L("edit.reply.to"),file_get_contents("$thdata/$to.name")) ?><br /><small><?= sprintf(L("post.number"),$_GET['reply_num']) ?></small></h1>
	</div>
	<?php
	if(!file_exists("$thdata/".$_GET['post'].'.dat')){ echo L("post.not.exist");} else {
	?>
	<p><?= L("use.md") ?></p>
	<form action="submit.php" method="POST">
		<input style="display: none;" name="type" id="type" value="edit"></input>
		<input style="display: none;" name="reply_num" id="reply_num" value="<?php echo $_GET['reply_num']; ?>"></input>
		<input style="display: none;" name="post-id" id="post-id" value="<?php echo $_GET['post']; ?>"></input>
		<?php
			$post_id = clean($_GET['post']);
			$posts = new Fllat($post_id , $thdata);
			$p = $posts -> select();
			$temp_time = "";
			$temp_text = "";
			$canEdit = false;
			foreach($p as $pp){
				$k = array_search($pp, $p);
				$k++;
				if($pp['user'] == $_SESSION['username'] && $k == $_GET['reply_num']){
					$canEdit = true;
					$temp_text = $pp['post'];
					$temp_time = $pp['time'];
					break;
				}
			}
			if($canEdit === false){
				echo L("not.perms.edit.reply");
			} else {
				echo '<textarea name="text" id="text" rows="10" cols="100%" class="form-control">'.$temp_text.'</textarea><br />
				<input style="display: none;" name="time" id="time" value="'.$temp_time.'"></input>
				<button type="submit" class="btn btn-primary pull-right">'.L("submit").'</button>
				';
			}
		?>


	</form>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']; $to = $_GET['post']; echo" | ".sprintf(L("reply.to.2"),file_get_contents("$thdata/$to.name")) ?>';
    });

</script>
	<?php
	}
}
if($_GET['type'] == "view"){
	$post_id = clean($_GET['post']);
	?>
	<div class="page-header">
		<?php
		if(file_exists("$thdata/$post_id.lock") || file_exists("$thdata/$post_id.lockadmin")){
			echo '<div class="alert alert-warning">'.L("thread.locked").'</div>';
		}
		?>
	  	<h1><?php $to = $_GET['post']; echo sprintf(L("view.title"),file_get_contents("$thdata/$to.name")) ?></h1>
	</div>
	<?php

	if($_SESSION['username']){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			?>
			<a href="post.php?type=reply&post=<?php echo $_GET['post']; ?>" class="btn btn-primary"><?= L("reply.to.post") ?></a>
			<?php
		}
		if(isAdmin($_SESSION['username'])){
			if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
				echo '&nbsp;&nbsp;<a href="./submit.php?type=lock&post='.$post_id.'" class="btn btn-primary btn-sm">'.L("lock.thread").'</a><br />';
			} else {
				echo '&nbsp;&nbsp;<a href="./submit.php?type=unlock&post='.$post_id.'" class="btn btn-primary btn-sm">'.L("unlock.thread").'</a><br />';
			}
		}
	}
	?>

	<?php

	if(!file_exists("$thdata/$post_id.dat")){ echo L("post.not.exist"); } else {
	$posts = new Fllat($post_id , $thdata);
	$p = $posts -> select();
	$canLock = $posts -> canUpdatePost(0, $_SESSION['username']);
	if($canLock){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			if(!isAdmin($_SESSION['username'])){ echo '&nbsp;&nbsp;<a href="./submit.php?type=lock&post='.$post_id.'" class="btn btn-primary btn-sm">'.L("lock.thread").'</a><br />'; }
		} elseif(!file_exists("$thdata/$post_id.lockadmin")) {
			if(!isAdmin($_SESSION['username'])) {echo '&nbsp;&nbsp;<a href="./submit.php?type=unlock&post='.$post_id.'" class="btn btn-primary btn-sm">'.L("unlock.thread").'</a><br />';}
		}
	} else {
		echo '<br />';
	}
	if(isAdmin($_SESSION['username'])){
		echo '<br /><a href="./submit.php?type=delete&post='.$post_id.'" class="btn btn-danger btn-sm">'.L("delete.thread").'</a><br />';
	}
	$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;

	if($_GET['page'] == 'first') $page = 1;
	elseif($_GET['page'] == 'last') $page = count($p);
	/*
	if($_GET['page'] == "first" || $_GET['page'] == "last"){
		$page = $_GET['page'];
		if($page == "first"){
			$page = 1;
		}
		if($page == "last"){
			$page = count($p);
		}
	}
	*/

	$total = count( $p ); //total items in array
	$limit = $config['perPageThread']; //per page
	$totalPages = ceil( $total/ $limit ); //calculate total pages
	$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
	$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
	$offset = ($page - 1) * $limit;
	if( $offset < 0 ) $offset = 0;
	$p = array_slice( $p, $offset, $limit ,true);
	echo '<ul class="pagination">';
	echo '<li><a href="./post.php?page=first&type=view&post='.$post_id.'">'.L("first").'</a></li>';
	for($i = 1; $i <= $totalPages; $i++){
		if($i == $page){
			echo '<li class="active"><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		} else {
			echo '<li><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		}
	}
	echo '<li><a href="./post.php?page=last&type=view&post='.$post_id.'">'.L("last").'</a></li>';
	echo "</ul>";
	foreach($p as $pp){
		$k = array_search($pp, $p);
		$k++;
		$pmd = Parsedown::instance()
   		->setMarkupEscaped(true) # escapes markup (HTML)
   		->text($pp['post']);
   		if($pp['user'] == $_SESSION['username'] && !file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
   			$edit = '<a href="./post.php?type=edit&post='.$post_id.'&reply_num='.$k.'">'.L("edit.reply").'</a>';
   		} else {
   			$edit = "";
   		}
		echo '<div class="panel panel-default">
  				<div class="panel-heading"><b>'.$pp['user'].'</b> @ '.$pp['time'].'&nbsp;&nbsp;'.$edit.'<span class="pull-right">#'.$k.'</span></div>
  				<div class="panel-body" style="overflow:auto;word-wrap: break-word;">'.$pmd.'</div>
			</div>';
	}
	echo '<ul class="pagination">';
	echo '<li><a href="./post.php?page=first&type=view&post='.$post_id.'">'.L("first").'</a></li>';
	for($i = 1; $i <= $totalPages; $i++){
		if($i == $page){
			echo '<li class="active"><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		} else {
			echo '<li><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		}
	}
	echo '<li><a href="./post.php?page=last&type=view&post='.$post_id.'">'.L("last").'</a></li>';
	echo "</ul>";

	}
?><br />
<?php
	if($_SESSION['username']){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			?>
			<a href="post.php?type=reply&post=<?php echo $_GET['post']; ?>" class="btn btn-primary"><?= L("reply.to.post") ?></a>
			<?php
		}
	}
	?>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']." | "; $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?>';
    });

</script>
<?php

}
echo '</div>';
include("footer.php");
?>
