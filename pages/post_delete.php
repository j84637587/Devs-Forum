<?php
if(!$_SESSION['usern']) { $redir = $web['forum_url']."sign_in/"; header("Location: $redir"); }
?>

	    <table border="0" width="100%" class="forum_cat">
		 <tr>
		  <td class="forum_cat_name" style="padding-bottom:10px;">Delete</td>
		 </tr>
		 <tr>
		  <td class="forum_cat_bg">

		   <?php
			$type = $_GET['type'];
			$id = $_GET['id'];
			$user_id = userinfo($_SESSION['usern'],"id");

			if($type == "thread") {
				$sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id' and user_id='$user_id'");
				if(mysqli_num_rows($sql)>0) {
				$delete = mysqli_query($conn, "DELETE FROM threads WHERE id='$id' and user_id='$user_id'");
				$delete = mysqli_query($conn, "DELETE FROM replies WHERE thread_id='$id'");
				?>
				    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
					<tr>
					 <td align="center" style="font-size:16px; font-weight:bold;">
					  Your thread has been deleted successfully!
					 </td>
					</tr>
					<tr>
					 <td align="center">
					  <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
					 </td>
					</tr>
				   </table>
				<?php
				} else {
				header("Location: $web[forum_url]");
				}
			} else {
				$sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id' and user_id='$user_id'");
				if(mysqli_num_rows($sql)>0) {
				$row = mysqli_fetch_array($sql);
				$thread_id = $row['thread_id'];
				$delete = mysqli_query($conn, "DELETE FROM replies WHERE id='$id' and user_id='$user_id'");
				?>
				    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
					<tr>
					 <td align="center" style="font-size:16px; font-weight:bold;">
					  Your reply has been deleted successfully!
					 </td>
					</tr>
					<tr>
					 <td align="center">
					  <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($thread_id); ?>/<?php echo $thread_id; ?>/">點擊返回文章.</a>
					 </td>
					</tr>
					<tr>
					 <td align="center">
					  <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
					 </td>
					</tr>
				   </table>
				<?php
				} else {
				header("Location: $web[forum_url]");
				}
			}
		   ?>

		  </td>
		 </tr>
		</table>
