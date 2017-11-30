<?php
    include_once('components/config.php');
    //Loading of Question List
    if (isset($_GET['id'])) {
        $question = $user->getQuestion($_GET['id']);
        if ($question[0] == false) {
            goto redirect;
        }
    }
    else {
        redirect:
        header("Location: modulelist.php");
    };
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['video'])) {
            if ($_POST['videoid'] != "" and $_POST['videoname'] != "") {
                $user->addVideo($_POST['videoid'], $question[0]['question_id'], $_POST['videoname']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
        else if (isset($_POST['option'])) {
            if ($_POST['videoFrom'] != "" and $_POST['videoTo'] != "" and $_POST['optiontext'] != "") {
                $user->addOption($_POST['videoFrom'], $_POST['videoTo'], $_POST['optiontext'], $question[0]['question_id']);
                $question = $user->getQuestion($_GET['id']); 
            }
        }
    }
    else if (isset($_GET['start'])) {
        foreach($question[1] as $video) {
            if ($video['video_id'] == $_GET['start']) {
                $user->setStartingVideo($video['video_id'], $question[0]['question_id']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
    }
    else if (isset($_GET['assignEnd'])) {
        foreach($question[1] as $video) {
            if ($video['video_id'] == $_GET['assignEnd']) {
                $user->setEndingVideo($video['video_id'], $question[0]['question_id']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
    }
    else if (isset($_GET['unassignEnd'])) {
        foreach($question[1] as $video) {
            if ($video['video_id'] == $_GET['unassignEnd']) {
                $user->removeEndingVideo($video['video_id'], $question[0]['question_id']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
    }
    else if (isset($_GET['deleteoption'])) {
        foreach($question[2] as $option) {
            if ($option['option_id'] == $_GET['deleteoption']) {
                $user->deleteOption($_GET['deleteoption']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
    }
    else if (isset($_GET['deletevideo'])) {
        foreach($question[1] as $video) {
            if ($video['video_id'] == $_GET['deletevideo']) {
                if ($question[0]['video_start'] == $_GET['deletevideo']) {
                    $user->setStartingVideo(NULL, $video['video_id']);
                }
                $user->deleteVideo($_GET['deletevideo']);
                $question = $user->getQuestion($_GET['id']);
            }
        }
    }
    require 'components/sidebar.php';
    
    //-----------------------------------------------------------
    // desc: retrieve the video name based on the ID
    // params: $option (object), $videoList (list of objects)
    // returns: $videoName (string)
    //-----------------------------------------------------------
    function getVideoText($videoID, $videoList){
        
        // iterate through videos to find specific video based on id and retrieve video name
        $videoName = "";
        foreach ($videoList as $video){
            if($video["video_id"] == $videoID){
                $videoName = $video["video_text"];
            }
        }
        
        return $videoName;
    }
    
    function printVideoRow($video, $question) {
		echo '<tr>';
		echo "<td>".htmlentities($video['video_id'])."</td>";
		echo "<td>".htmlentities($video['video_link'])."</td>";
		echo "<td>".htmlentities($video['video_text'])."</td>";
		echo "<td>";
        echo "<a class='btn btn-primary btn-xs' data-toggle='modal' data-target="."#video".htmlentities($video['video_id'])."view href="."#video".htmlentities($video['video_id'])."view><i class='fa fa-folder'></i> View </a>";
        echo "<a href=\"adminvideolist.php?id=".htmlentities($_GET['id'])."&deletevideo=".htmlentities($video['video_id'])."\" class=\"btn btn-danger btn-xs\"><i class='fa fa-delete'></i>Delete</a>";
        echo "<a href=\"editvideo.php?id=".htmlentities($video['video_id'])."\" class=\"btn btn-info btn-xs\"><i class='fa fa-edit'></i>Edit</a>";
        if ($question[0]['video_start'] == $video['video_id']) {
            echo "Start";
        }
        else {
            echo "<a href=\"adminvideolist.php?id=".htmlentities($question[0]['question_id'])."&start=".htmlentities($video['video_id'])."\" class=\"btn btn-primary btn-xs\"><i class='fa fa-location-arrow'></i> Make Start</a>";
        }

        // if video is assigned as end, show unassign end button for video. Else, show assign end button.
        if (in_array($video['video_id'], $question[0]['videos_end'])) {
            echo "<a href=\"adminvideolist.php?id=".htmlentities($question[0]['question_id'])."&unassignEnd=".htmlentities($video['video_id'])."\" class=\"btn btn-warning btn-xs\"><i class='fa fa-location-arrow'></i>Unassign End</a>";
        }
        else {
            echo "<a href=\"adminvideolist.php?id=".htmlentities($question[0]['question_id'])."&assignEnd=".htmlentities($video['video_id'])."\" class=\"btn btn-success btn-xs\"><i class='fa fa-location-arrow'></i>Assign End</a>";
        }
        echo "</td>";
		echo '</tr>';
    }
    
    function printVideoModal($video) {
		#Modal for more information
		echo "<div id='video".htmlentities($video['video_id'])."view' class='modal fade' role='dialog'>";
		echo 	"<div class='modal-dialog'>";
		echo 		"<div class='modal-content'>";
		echo 			"<div class='modal-header'>";
		echo 				"<button type='button' class='close' data-dismiss='modal'>&times;</button>";
		echo 					"<h3 class='modal-title'>Video ".htmlentities($video['video_id'])." Information</h3>";
		echo 			"</div>";
		echo	 		"<div class='modal-body'>";
		echo				"<div class='x_title'><h4>Video Preview</h4></div>";
        echo                    "<div class='videoWrapper'>";
        echo                        '<iframe id="ytplayer" type="text/html" class="col-xs-12" src="https://www.youtube.com/embed/'.htmlentities($video['video_link']).'?autoplay=0" frameborder="0"></iframe>';
        echo                '</div>';
        echo                '<div class="x_title"><h4>Options Selected Information</h4></div>';
        echo                '<div id="video'.htmlentities($video['video_id']).'_chart"></div>';
		echo 			"</div>";
		echo 		"<div class='modal-footer'>";
        echo        "<a href=\"adminvideolist.php?id=".htmlentities($_GET['id'])."&deletevideo=".htmlentities($video['video_id'])."\" class=\"btn btn-danger\"><i class='fa fa-delete'></i>Delete</a>";
        echo        "<a href=\"editvideo.php?id=".htmlentities($video['video_id'])."\" class=\"btn btn-info\"><i class='fa fa-edit'></i>Edit</a>";
		echo 			"<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
		echo		"</div>";
		echo	"</div>";
		echo "</div>";
	}
    
    function printOptionRow($option, $question) {
		echo '<tr>';
		echo "<td>".htmlentities(getVideoText($option["video_from"], $question[1]))."</td>";
		echo "<td>".htmlentities(getVideoText($option["video_to"], $question[1]))."</td>";
		echo "<td>".htmlentities($option['option_name'])."</td>";
		echo "<td>".htmlentities($option['frequency'])."</td>";
		echo "<td>";
        echo "<a href=\"editoption.php?id=".htmlentities($option['option_id'])."\" class=\"btn btn-info btn-xs\"><i class='fa fa-edit'></i>Edit</a>";
        echo "<a href=\"adminvideolist.php?id=".htmlentities($_GET['id'])."&deleteoption=".htmlentities($option['option_id'])."\" class=\"btn btn-danger btn-xs\"><i class='fa fa-delete'></i>Delete</a>";
        echo "</td>";
		echo '</tr>';
    }
?>

<!-- Chart Drawing -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      <?php
        foreach($question[1] as $video) {
            echo 'google.charts.setOnLoadCallback(draw'.htmlentities($video['video_id']).'chart);';
            echo "\n";
        }
        
        foreach($question[1] as $video) {
            echo 'function draw'.htmlentities($video['video_id']).'chart() {';
            echo 'var data = new google.visualization.DataTable();';
            echo 'data.addColumn("string", "Option");';
            echo 'data.addColumn("number", "Frequency");';
            foreach ($question[2] as $option) {
                if ($option['video_from'] == $video['video_id']) {
                    echo 'data.addRow(["'.htmlentities($option['option_name']).'",'.htmlentities($option['frequency']).']);';
                }
            }
            echo 'var options = {title:""};';
            echo 'var chart = new google.visualization.PieChart(document.getElementById("video'.htmlentities($video['video_id']).'_chart"));';
            echo 'chart.draw(data, options)';
            echo "}";
        }
      ?>
    </script>
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
                        <h2><?php echo htmlentities($question[0]['question_name']); ?> Question List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <?php if ($question[0]['video_start'] == NULL) {echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Warning!</strong> No start point set!
                    </div>';} ?>
                    <div class="x_content">
                    <p>Edit the options and videos here.</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Diagram View</a></li>
                                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false" onclick="video()">Video List</a></li>
                                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false" onclick="option()">Option List</a></li>
                                        
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                            <div style="overflow: scroll;">
                                                <div class="mermaid" style="width:2000px;">
                                                    graph TD
                                                    start[Start]
                                                    <?php 
                                                        foreach($question[1] as $video) {
                                                            echo htmlentities($video['video_id']) . '[' . htmlentities($video['video_text']) . "]\n";
                                                            if ($question[0]['video_start'] == $video['video_id']) {
                                                                echo 'start-->' . htmlentities($video['video_id']) . "\n";
                                                            }
                                                        }
                                                        foreach($question[2] as $option) {
                                                            echo htmlentities($option['video_from']) . '--' . htmlentities($option['option_name']) . '-->' . htmlentities($option['video_to']) . "\n";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                            <table style="width:100%"id="datatable" class="table table-striped table-bordered dt-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Video ID</th>
                                                        <th>Video Link</th>
                                                        <th>Video Name</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach($question[1] as $video) {
                                                            printVideoRow($video, $question);
                                                            printVideoModal($video);
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div class="x_title">
                                                <h2>Add Video</h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <form id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post">
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
                                                        <input type="hidden" name="video" value="type">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                            <table style="width:100%"  class="table table-striped table-bordered dt-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Video From</th>
                                                        <th>Video To</th>
                                                        <th>Option Name</th>
                                                        <th>Frequency</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach($question[2] as $option) {
                                                            printOptionRow($option, $question);
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div class="x_title">
                                                <h2>Add Option</h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <form id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post">
                                                <div class="item form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Video From<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control required" name="videoFrom" disabled="disabled">
                                                            <option value="">Video From</option>
                                                            <?php 
                                                                foreach($question[1] as $row) {
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
                                                                foreach($question[1] as $row) {
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
                                                        <input type="text"  class="form-control col-md-7 col-xs-12 required"  name="optiontext" disabled="disabled">
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
<script>
    function video() {
        document.getElementsByName("videoFrom")[0].setAttribute("disabled", "disabled");
        document.getElementsByName("videoTo")[0].setAttribute("disabled", "disabled");
        document.getElementsByName("optiontext")[0].setAttribute("disabled", "disabled");
        document.getElementsByName("option")[0].setAttribute("disabled", "disabled");
        
        document.getElementsByName("video")[0].removeAttribute("disabled");
        document.getElementsByName("videoid")[0].removeAttribute("disabled");
        document.getElementsByName("videoname")[0].removeAttribute("disabled");
    };
    function option() {
        document.getElementsByName("video")[0].setAttribute("disabled", "disabled");
        document.getElementsByName("videoid")[0].setAttribute("disabled", "disabled");
        document.getElementsByName("videoname")[0].setAttribute("disabled", "disabled");
        
        document.getElementsByName("videoFrom")[0].removeAttribute("disabled");
        document.getElementsByName("videoTo")[0].removeAttribute("disabled");
        document.getElementsByName("optiontext")[0].removeAttribute("disabled");
        document.getElementsByName("option")[0].removeAttribute("disabled");
    };
</script>
<script type="text/javascript" src="build/js/mermaid.min.js"></script>
<script>mermaid.initialize({startOnLoad:true});</script>
<?php
    include 'components/footer.php';
    include 'components/datatables.php';
    include 'components/closing.php';
?>    