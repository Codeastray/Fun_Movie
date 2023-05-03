<?php
session_start();
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);
$C_UID = $mydata["UID"];
$moviename = $mydata["moviename"];
// 先過濾前端點擊影片後，有沒有值傳進來
if (isset($mydata["moviename"])) {
	if ($mydata["moviename"] != "" ) {
		require_once("movie_dbtool.php");
		$conn = create_connect();
    // 先新增欄位，將a_user_recent.M_ID的欄位填入與a_user.ID相同的值
		$sql = "INSERT INTO a_user_recent (M_ID) SELECT a_user.ID FROM a_user WHERE  UID = '$C_UID'";
		if (execute_sql($conn, "funmovie", $sql)) {
           //還沒寫好↓
            $sql = " UPDATE a_user_recent SET Recent = (SELECT c_movie.Movie_ID FROM c_movie WHERE c_movie.Moviename ='$moviename')";
			echo '{"state" : true, "message" : "最近觀看新增成功!" }';
		} else {
			echo '{"state" : false, "message" : "最近觀看新增失敗!:' . mysqli_error($conn) . $sql . ' "}';
		}
		mysqli_close($conn);
	} else {
		echo '{"state" : false, "message" : "欄位不得為空白!" }';
	}
} else {
	echo '{"state" : false, "message" : "欄位錯誤!" }';
}
