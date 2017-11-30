<?php
    include_once('components/config.php');
    require 'components/sidebar.php';
    
    $modules = $user->getModules();
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Welcome!  </h3>
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
                    <p>Select a module to attempt its questions. Do ensure that you are familiar with the content before attempting the questions.</p>
                    <div class="row">                    
                    <?php foreach ($modules as $module) {?>
                      <a href="questionlist.php?id=<?php echo htmlentities($module['module_id'])?>">
                        <div class="col-md-55">
                          <div class="caption">
                            <p><?php echo htmlentities($module['module_name'])?></p>
                          </div>
                        </div>
                      </a>
                          <?php } ?>
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