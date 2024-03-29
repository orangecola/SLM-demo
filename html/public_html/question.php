<?php
	include_once('components/config.php');
	$Success=0;
	if (isset($_GET['id'])) {
        $video = $user->getVideo($_GET['id']);
        if ($video[0] == false) {
            goto redirect;
        }
        $question = $user->getQuestion($video[0]['question_id']);
        if (isset($_GET['option'])) {
            foreach($video[1] as $option) {
                if ($option['option_id'] == $_GET['option']) {
                    $user->addOptionFrequency($option['option_id']);
                    header("Location: question.php?id=".htmlentities($option['video_to']));
                }
            }
        }
    }
    else {
        redirect:
        header("Location: modulelist.php");
    }
	include('components/sidebar.php');
?>

<script>
	document.getElementById('questionlist.php').setAttribute("class", "current-page");

</script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?php echo htmlentities($question[0]['question_name']);?><img width="100" height="50" src="./images/hot_and_cold.jpg" alt="hot and cold">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo htmlentities($video[0]['video_text'])?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p>Please watch the video <b> FIRST </b>and select the option that you think is the best response for the situation presented.</p>
                        <form novalidate id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post">
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" >Video</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="videoWrapper">
                                        <iframe id="ytplayer" type="text/html" class="col-xs-12" src="https://www.youtube.com/embed/<?php echo htmlentities($video[0]['video_link']);?>?autoplay=0" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <?php
                                        foreach ($video[1] as $index=>$option) {
                                            echo '<a href="question.php?id='.htmlentities($video[0]['video_id']).'&option='.htmlentities($option['option_id']).'" class="btn btn-success col-xs-12"> Option '. ($index + 1) . ': ' .htmlentities($option['option_name']).'</a>';
                                            echo '<br />';
                                            echo '<br />';
                                        }

                                        // if video is the end video, show button to return to module list
                                        if (in_array($video[0]['video_id'], $question[0]['videos_end'])){
                                            echo '<a href="index.html" class="btn btn-danger col-xs-12">Return to Front Page</a>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<?php require 'components/footer.php'; ?>
<?php require 'components/closing.php'?>
