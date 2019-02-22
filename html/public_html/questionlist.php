<?php
    include_once('components/config.php');
    
    //Loading of Question List
    if (isset($_GET['id'])) {
    $module = $user->getModule($_GET['id']);
    if ($module[0] == false) {
        goto redirect;
    }
    }
    else {
        redirect:
        header("Location: modulelist.php");
    };
    
    require 'components/sidebar.php';
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Attempt Question </h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo htmlentities($module[0]['module_name']); ?> Question List</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <p>Select a question to attempt</p>
                    <div class="row">
                    <?php foreach ($module[1] as $question) {?>
                      <a href="question.php?id=<?php echo htmlentities($question['video_start'])?>">
                        <div class="col-md-55">
                          <div class="caption">
                            <p><?php echo htmlentities($question['question_name'])?></p>
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