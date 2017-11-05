<?php
    include_once('components/config.php');
    require 'components/sidebar.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $modulename = $_POST['modulename'];
        if ($modulename != "") {
            $user->addModule($modulename);
        }
    }
    $modules = $user->getModules();
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Admin Panel  </h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Module List</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                    <?php if (count($modules) == 0): ?>
                        <div class="col-xs-12">
                            <p>No Modules yet, Add one today!</p>
                        </div>
                    
                    <?php else : 
                          foreach ($modules as $module) {?>
                      <a href="adminquestionlist.php?id=<?php echo htmlentities($module['module_id'])?>">
                        <div class="col-md-55">
                          <div class="caption">
                            <p><?php echo htmlentities($module['module_name'])?></p>
                          </div>
                        </div>
                      </a>
                          <?php } endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="New Module Name" name="modulename">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add new Module</button>
                                </span>
                            </div>
                            </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php
	include 'components/footer.php';
	include 'components/closing.php';
?>