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
                    <div class="row">
                    <?php if (count($module[1]) == 0): ?>
                        <div class="col-xs-12">
                            <p>No Questions yet, Add one today!</p>
                        </div>
                    
                    <?php else : 
                          foreach ($module[1] as $question) {?>
                      <a href="question.php?id=<?php echo htmlentities($question['video_start'])?>">
                        <div class="col-md-55">
                          <div class="caption">
                            <p><?php echo htmlentities($question['question_name'])?></p>
                          </div>
                        </div>
                      </a>
                          <?php } endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="New Question Name" name="questionname">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add new Question</button>
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