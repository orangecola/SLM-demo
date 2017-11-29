<?php 
	include_once('components/config.php');
	$Success=0;
	$noChanges=0;
	$usernameTaken=0;
	
	if(!($_SESSION['role'] == 'admin'))
	  {
		 header('Location: logout.php');
	  }
	
	if(!(isset($_GET['id']))) {
			header('Location: userlist.php');
	}
	
	$result = $user->getVideo($_GET['id'])[0];

	if (!$result[0]) {
		header('Location: userlist.php');
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		$candidate['id'] 	        = trim($_GET['id']);
		$candidate['videoid'] 		= trim($_POST['videoid']);
		$candidate['videoname']	    = trim($_POST['videoname']);
		$same = true;
        
        $user->editVideo($candidate['id'], $candidate['videoid'], $candidate['videoname']);
        $result = $user->getVideo($_GET['id'])[0];
        $Success = 1;
		}
	
  
	include('components/sidebar.php');
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>User Management</h3>
	  </div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		

	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>Editing Video <?php echo htmlentities($result['video_id']);?></h2>
			
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">
		  <?php
		  if ($Success == 1) {echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong>Success</strong> User edited successfully
		  </div>';}
		  ?>
			<p>Edit information about a video here.</p>
			<form id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post">
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" >Video</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="videoWrapper">
                            <iframe id="ytplayer" type="text/html" class="col-xs-12" src="https://www.youtube.com/embed/<?php echo htmlentities($result['video_link']);?>?autoplay=0" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>	
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Instructions
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Upload your video to youtube, and then copy and paste the video id, which is after the v (without the v=), below:
                        <img src="images/videoinstruction.jpg"> 
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Video ID <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"  class="form-control col-md-7 col-xs-12 required"  name="videoid">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Video Name <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"  class="form-control col-md-7 col-xs-12 required"  name="videoname">
                    </div>
                </div>
                <div class="item form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="adminvideolist.php?id=<?php echo htmlentities($result['question_id']); ?>" class="btn btn-info "></i>Cancel</a>
                       <a href="adminvideolist.php?id=<?php echo htmlentities($result['question_id']);?>&deletevideo=<?php echo htmlentities($_GET['id']);?>" class="btn btn-danger"><i class='fa fa-delete'></i>Delete</a>
                    </div>
                </div>
            </form>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<script>
	document.getElementsByName("videoid")[0].setAttribute("value", <?php echo json_encode($result['video_link']);?>);
	document.getElementsByName("videoname")[0].setAttribute("value", <?php echo json_encode($result['video_text']);?>);
</script>
<!-- /page content -->

<?php
	require 'components/footer.php';
	require 'components/closing.php';
?>