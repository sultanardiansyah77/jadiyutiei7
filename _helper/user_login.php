<?php
$res_user = get_user($app);
$res_user_json = json_decode($res_user, true);
if (isset($res_user_json['status'])) {
    $status = $res_user_json['status'];
    if ($status == 1) {
        $rc = $res_user_json['rc'];
        if ($rc == "401") {
            $msg = $res_user_json['message'];
            $_SESSION['msg'] = $msg;
            header("Location: /user/login?c=1");
            exit;
        } else {
            $data_user = $res_user_json['data'];
        }
    } else {
        $msg = $res_user_json['error_msg'];
        $_SESSION['msg'] = $msg;
        header("Location: /user/login?c=1");
        exit;
    }
} else {
    $_SESSION['msg'] = "Gagal mendapatkan data user. Silahkan login kembali";
    header("Location: /user/login?c=1");
    exit;
}