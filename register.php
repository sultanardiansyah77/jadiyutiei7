<?php
require_once("config.php");

if (isset($_POST['email'])) {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $hp = $_POST['hp'];
    $pass = $_POST['password'];
    $pass_konfirm = $_POST['password_konfirm'];
    $regex_email = "/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/";
    $regex_name = "/^[a-zA-Z-'\s]+$/";
    $regex_phone = "/^[0-9]+$/";
    if (preg_match($regex_email, $email)) {
        if (preg_match($regex_phone, $hp)) {
            if (preg_match($regex_name, $name)) {
                $data = array(
                    'hp' => $hp,
                    'password' => $pass,
                    'ua' => $ua,
                    "name" => $name,
                    "email" => $email
                );
                $url = "$api_url/user/register";
                $resapi = $app->curl_post_json($url, $data);
                $res_data = json_decode($resapi, true);
                if (isset($res_data['status'])) {
                    $status = $res_data['status'];
                    if ($status == 1) {
                        $token = $res_data['data']['token'];
                        $cookie_name = $x_token;
                        setcookie($cookie_name, $token, time() + (86400 * (30*12)), "/");
                        header("Location: /user/home");
                        exit;
                    } else {
                        $msg = $res_data['error_msg'];
                        $alert = true;
                        $alert_msg =$msg;
                    }
                } else {
                    $alert = true;
                    $alert_msg = "Gagal menghubungi API";
                }
            } else {
                $alert = "alert alert-danger";
                $alert_msg = "Nama tidak valid !!";
            }
        } else {
            $alert = "alert alert-danger";
            $alert_msg = "No HP tidak valid !!";
        }
    } else {
        $alert = "alert alert-danger";
        $alert_msg = "Email tidak valid !!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("_/head.php");
    ?>
    <title>Register - <?php echo $c_brand ?></title>
</head>

<body class="mbg-primary">
    <div class="flex flex-col justify-between h-full">
        <div>
            <?php require_once("_/header.php")
            ?>
            <div class="w-full md:mt-[200px] mt-[100px]">
                <div class="mt-[100px] "></div>
                <?php
                if (isset($alert)) {
                ?>
                    <div class="md:w-[500px] w-full md:mx-auto p-6 rounded text-red-200 bg-red-500"><?php echo $alert_msg ?></div>
                <?php
                }
                ?>
                <form method="POST" class="mt-2">
                    <div class="md:w-[500px] w-full m-shadow md:px-12 px-6 py-12 rounded-lg mx-auto mb-10 flex flex-col gap-4">
                        <div class="w-full ">
                            <label for="nama" class="text-white">Nama</label>
                            <input type="text" id="nama" name="name" placeholder="Nama" class="form-input">
                        </div>
                        <div class="w-full ">
                            <label for="email" class="text-white">Email</label>
                            <input type="text" id="email" name="email" placeholder="Email" class="form-input">
                        </div>
                        <div class="w-full ">
                            <label for="hp" class="text-white">Hp WhatsApp</label>
                            <input type="text" id="hp" name="hp" placeholder="No WhatsApp" class="form-input">
                        </div>
                        <div class="w-full ">
                            <label for="password" class="text-white">Password</label>
                            <input type="password" id="password" name="password" placeholder="" class="form-input">

                        </div>
                        <div class="w-full ">
                            <label for="password_konfirm" class="text-white">Konfirmasi Password</label>
                            <input type="password" id="password_konfirm" name="password_konfirm" placeholder="" class="form-input">
                        </div>
                        <button class="btn-me" type="submit">Register</button>
                        <small class="text-red-500 pass-weak hidden"></small>
                        <small class="text-sky-400 pass-strong hidden">Nice, Password kamu sangat kuat. Silahkan register</small>
                    </div>
                </form>

                <div class="md:w-[500px] w-full flex flex-col mx-auto my-2 justify-center items-center gap gap-4">
                    <p class="text-white">Sudah punya akun ? <a href="/user/login" class="color-fifth">Login</a></p>
                    <p class="text-white">&copy; 2022 <?php echo $c_brand ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("_/general.php") ?>
    <script src="<?php echo $c_url ?>/assets/app.js"></script>
    <script>
        var password = $('#password')
        var btn_me = $(".btn-me")
        var pass_weak = $(".pass-weak")
        var pass_strong = $(".pass-strong")
        var msg_wk = "Password kamu terlalu lemah. Minimal 8 karakter. huruf kecil, huruf besar, angka, dan spesial karakter"
        var msg_strong = "Nice, Password kamu sangat kuat"
        var password_konfirm = $("#password_konfirm")
        var password = $("#password")

        btn_me.attr("disabled", true)
        password.on("keyup change", function(e) {
            if (CheckPassword(password.val())) {
                btn_me.attr("disabled", false)
                pass_strong.show()
                pass_strong.html(msg_strong)
                pass_weak.hide()
            } else {
                btn_me.attr("disabled", true)
                pass_weak.show()
                pass_weak.html(msg_wk)
                pass_strong.hide()
            }
        })

        password_konfirm.on("keyup change", function(e) {
            if (password_konfirm.val() == password.val()) {
                btn_me.attr("disabled", false)
                pass_strong.show()
                pass_strong.html("Silahkan lanjutkan register")
                pass_weak.hide()
            } else {
                btn_me.attr("disabled", true)
                pass_weak.show()
                pass_weak.html("Konfirmasi password kamu tidak sama")
                pass_strong.hide()
            }
        })

        function CheckPassword(value) {
            var decimal = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
            if (value.match(decimal)) {
                // alert('Correct, try another...')
                return true;
            } else {
                // alert('Wrong...!')
                return false;
            }
        }
    </script>
</body>

</html>