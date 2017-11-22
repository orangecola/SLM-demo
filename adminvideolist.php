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
        echo "<a href=\"statistics.php?id=".htmlentities($video['video_id'])."\" class=\"btn btn-default btn-xs\"><i class='fa fa-folder'></i> View</a>";
        echo "<a href=\"editvideo.php?id=".htmlentities($video['video_id'])."\" class=\"btn btn-info btn-xs\"><i class='fa fa-edit'></i>Edit</a>";
        if ($question[0]['video_start'] == $video['video_id']) {
            echo "Start";
        }
        else {
            echo "<a href=\"adminvideolist.php?id=".htmlentities($question[0]['question_id'])."&start=".htmlentities($video['video_id'])."\" class=\"btn btn-primary btn-xs\"><i class='fa fa-location-arrow'></i> Make Start</a>";
        }
        echo "</td>";
		echo '</tr>';
    }
    
    function printOptionRow($option, $question) {
		echo '<tr>';
		echo "<td>".htmlentities(getVideoText($option["video_from"], $question[1]))."</td>";
		echo "<td>".htmlentities(getVideoText($option["video_to"], $question[1]))."</td>";
		echo "<td>".htmlentities($option['option_name'])."</td>";
		echo "<td>".htmlentities($option['frequency'])."</td>";
		echo "<td>";
        echo "<a href=\"placeholder.php?id=".htmlentities($option['option_id'])."\" class=\"btn btn-info btn-xs\"><i class='fa fa-edit'></i>Edit</a>";
        echo "<a href=\"placeholder.php?id=".htmlentities($option['option_id'])."\" class=\"btn btn-danger btn-xs\"><i class='fa fa-delete'></i>Delete</a>";
        echo "</td>";
		echo '</tr>';
    }
?>
<!-- page content -->
<script type="text/javascript" src="build/js/graph.js"></script>
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
                    <div class="x_content">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" id="canvas"></svg>
                                            <script type="text/javascript">
                                                var g = new Graph("canvas", 960, 700 );
                                                
                                                <?php
                                                    echo "g.createVertex('Start', '#73789C');";
                                                    
                                                    
                                                    foreach($question[1] as $video) {
                                                        echo "g.createVertex(".json_encode($video["video_text"]).", '#2A3F54');\n";
                                                        if ($question[0]['video_start'] == $video['video_id']) {
                                                             echo "g.createEdge('Start', ".json_encode($video["video_text"]).");\n";
                                                        }
                                                    }
                                                    
                                                    foreach($question[2] as $option) {
                                                        echo "g.createVertex(".json_encode($option["option_name"]).", '#D3D6DA');\n";
                                                        echo "g.createEdge(".json_encode($question[1][$option["video_from"] - 1]['video_text']).", ".json_encode($option["option_name"]).");\n";
                                                        echo "g.createEdge(".json_encode($option["option_name"]).", ".json_encode(getVideoText($option["video_to"], $question[1])).");\n";
                                                    }
                                                ?>                              
                                                g.go();
                                            </script>
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
<?php
	include 'components/footer.php';
    include 'components/datatables.php';
	include 'components/closing.php';
?>