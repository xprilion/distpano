<?php

    include('head.php');
    include('resultAssimilator.php');

    $taskhash = $_GET["taskhash"];
    $imghash1 = $_GET["imghash1"];
    $imghash2 = $_GET["imghash2"];

    echo $taskhash." | ".$imghash1." | ".$imghash2." <br><br> ";

    $h = $_GET["h"];
    $v = $_GET["v"];

    $res = $_GET["res"];

    if((strlen(trim(ltrim(rtrim($res)))) > 0) && (res!="error")){

        //$res = "23,34|54,24;45,25|57,85;34,77|53,75;";

        echo $res;

        echo "<hr>";

        $cps = explode(";", $res);
        unset($cps[count($cps)-1]);

        $cpoints  = Array("x1"=>Array(), "y1"=>Array(), "x2"=>Array(), "y2"=>Array());

        foreach($cps as $cp){
            $pts = explode("|", $cp);
            print_r($pts);
            $ax1 = explode(",", $pts[0]);
            $ax2 = explode(",", $pts[1]);

            $cpoints["x1"][] = $ax1[0];
            $cpoints["y1"][] = $ax1[1];
            $cpoints["x2"][] = $ax2[0];
            $cpoints["y2"][] = $ax2[1];

        }

        print_r($cpoints);

    }
    else{
        $res = 0;
    }

    $imgid1 = 0;
    $imgid2 = 0;

    $taskid = 0;

    $sql["getImgHID"] = "SELECT * FROM pending WHERE name_hash = '$imghash1'";
    $resHandle["getImgHID"] = mysqli_query($conn, $sql["getImgHID"]);
    $imgid1_r = mysqli_fetch_assoc($resHandle["getImgHID"]);
    $imgid1 = $imgid1_r["id"];

    $sql["getImgVhash"] = "SELECT * FROM pending WHERE name_hash = '$imghash2'";
    $resHandle["getImgVhash"] = mysqli_query($conn, $sql["getImgVhash"]);
    $imgid2_r = mysqli_fetch_assoc($resHandle["getImgVhash"]);
    $imgid2 = $imgid2_r["id"];

    $sql["getTaskID"] = "SELECT * FROM tasks WHERE hashname = '$taskhash'";
    $resHandle["getTaskID"] = mysqli_query($conn, $sql["getTaskID"]);
    $taskid_r = mysqli_fetch_assoc($resHandle["getTaskID"]);
    $taskid = $taskid_r["taskid"];


    //$sql["res"] = "UPDATE action SET result='$res', status = '2' WHERE imgid1 = '$imgid1' AND imgid2 = '$imgid2' AND v = '$h' AND h = '$v' AND taskid = '$taskid'";

    $sql["checkAction"] = "SELECT * FROM action WHERE imgid1 = '$imgid1' AND imgid2 = '$imgid2' AND v = '$v' AND h = '$h' AND taskid = '$taskid'";

    $resHandle["checkAction"] = mysqli_query($conn, $sql["checkAction"]);

    if(mysqli_num_rows($resHandle["checkAction"])){
        $sql["res"] = "UPDATE action SET result='$res', status = '2' WHERE imgid1 = '$imgid1' AND imgid2 = '$imgid2' AND v = '$v' AND h = '$h' AND taskid = '$taskid'";
    }
    else{
        $sql["res"] = "INSERT INTO action (imgid1, imgid2, taskid, result, status, h, v) VALUES('$imgid1', '$imgid2', '$taskid', '$res', '2', '$h', '$v')";
    }

    echo "<hr>";
    echo $sql["res"];
    echo '<br>';
    if(!mysqli_query($conn, $sql["res"])){
        echo mysqli_error($conn);
    }

    if(checkResult($taskid, $imgid1, $imgid2, "JPG", "JPG")){
        echo "enough points";
        stitchQuick($taskid, $imgid1, $imgid2, "JPG", "JPG");
    }

?>