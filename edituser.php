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
	
	$result = $user->getUser($_GET['id']);
	$result[1]['User_Password'] = "";
	
	if (!$result[0]) {
		header('Location: userlist.php');
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
        if (trim($_POST['User_Password']) != "") {
            $candidate['User_Password'] 	= password_hash(trim($_POST['User_Password']), PASSWORD_DEFAULT);
        }
        else {
            $candidate['User_Password'] = "";
        }
        
		$candidate['User_Username'] 	= trim($_POST['User_Username']);
		$candidate['User_Role'] 		= trim($_POST['User_Role']);
		$candidate['User_Status']	= trim($_POST['User_Status']);
		$same = true;
        
		foreach ($user->userFields as $value) {
        
        	echo ($candidate[$value] != $result[1][$value]);
			if ($candidate[$value] != $result[1][$value]) {
				$same = false;
				break;
			}
		}
		unset($value);
		
		if ($same) {
			$noChanges = 1;
		}
		else if (!$user->check($candidate['User_Username']) and $candidate['User_Username'] != $result[1]['User_Username']) {
			$usernameTaken = 1;
		}
		else {
			$user->editUser($result[1], $candidate);
			$result = $user->getUser($_GET['id']);
			$result['password'] = "";
			
			$Success = 1;
		}
	}
  
	include('components/sidebar.php');
?>

<script>
	document.getElementById('userlist.php').setAttribute("class", "current-page");
</script>
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
			<h2>Editing User <?php echo htmlentities($result[1]['User_Username']);?></h2>
			
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">
		  <?php
		  if ($noChanges == 1) {echo '<div class="alert alert-error alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong>Error</strong> No changes made
		  </div>';}
		  if ($usernameTaken == 1) {echo '<div class="alert alert-error alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong>Error</strong> Username already exists
		  </div>';}
		  if ($Success == 1) {echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<strong>Success</strong> User edited successfully
		  </div>';}
		  ?>
			<p>Edit information about a user here. To keep the password the same, leave the password field blank.</p>
			<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">

			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
				  <input type="text" id="username" class="form-control col-md-7 col-xs-12 required"  name="User_Username" data-validate-length-range="6">
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
				  <input type="password" id="password" name="User_Password" class="form-control col-md-7 col-xs-12 optional" placeholder="*unchanged*" >
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password <span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
				  <input id="password2" class="form-control col-md-7 col-xs-12 optional" type="password" data-validate-linked="User_Password">
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">User Role<span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
				  <select class="form-control" name="User_Role">
					<option id="user" value="user">User</option>
					<option id="admin" value="admin">Admin</option>
				  </select>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Status<span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
				  <select class="form-control" name="User_Status">
					<option id="active" value="active">Active</option>
					<option id="inactive" value="inactive">Inactive</option>
				  </select>
				</div>
			  </div>
			  <div class="item form-group">
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				  <a href="userlist.php" class="btn btn-primary" type="cancel">Cancel</a>
				  <button class="btn btn-primary" type="reset">Reset</button>
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
	document.getElementById("username").setAttribute("value", <?php echo json_encode($result[1]['User_Username']);?>);
	document.getElementById(<?php echo json_encode($result[1]['User_Role']);?>).setAttribute("selected", "selected");
	document.getElementById(<?php echo json_encode($result[1]['User_Status']);?>).setAttribute("selected", "selected");
</script>
<!-- /page content -->

<?php
	require 'components/footer.php';
	require 'components/closing.php';
?>