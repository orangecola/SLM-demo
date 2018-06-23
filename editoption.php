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

	$result = $user->getOption($_GET['id']);

	if (!$result[0]) {
		header('Location: userlist.php');
	}
  /*
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $candidate['id'] 	        = trim($_GET['id']);
		$candidate['videoFrom']     = trim($_POST['videoFrom']);
		$candidate['videoTo'] 		= trim($_POST['videoTo']);
		$candidate['optiontext']	= trim($_POST['optiontext']);
		$same = true;

        $user->editOption($candidate['id'], $candidate['videoFrom'], $candidate['videoTo'], $candidate['optiontext']);
        $result = $user->getOption($_GET['id']);
        $Success = 1;
		}
	*/

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
			<h2>Editing Option <?php echo htmlentities($result[0]['option_id']);?></h2>

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
			<p>Edit information about a option here.</p>
			<form id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post">
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Video From<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control required" name="videoFrom">
                                <option value="">Video From</option>
                                <?php
                                    foreach($result[1] as $row) {
                                        echo '<option value="'.$row['video_id'].'">'.$row['video_text'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Video To<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control required" name="videoTo">
                                <option value="">Video To</option>
                                <?php
                                    foreach($result[1] as $row) {
                                        echo '<option value="'.$row['video_id'].'">'.$row['video_text'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Option Text <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  class="form-control col-md-7 col-xs-12 required"  name="optiontext">
                        </div>
                    </div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="option" value="type" disabled="disabled">
                            <button type="submit" class="btn btn-success">Submit</button>
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
    document.getElementsByName("videoFrom")[0].value 	= <?php echo json_encode($result[0]["video_from"]);?>;
    document.getElementsByName("videoTo")[0].value 	= <?php echo json_encode($result[0]["video_to"]);?>;
	document.getElementsByName("optiontext")[0].setAttribute("value", <?php echo json_encode($result[0]['option_name']);?>);
</script>
<!-- /page content -->

<?php
	require 'components/footer.php';
	require 'components/closing.php';
?>
