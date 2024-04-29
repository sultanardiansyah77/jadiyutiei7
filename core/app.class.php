<?php

class App
{
    function regex_email($email){
        $regex_email = "/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/";
        if (preg_match($regex_email, $email)) {
            return true;
        }else{
            return false;
        }
    }

    function idr($angka)
    {
        $jadi = "Rp, " . number_format($angka, 0, ',', '.');
        return $jadi;
    }

    function contains($soource, $partof)
    {
        $hasil_validate = substr_count($soource, $partof);
        if ($hasil_validate > 0) {
            return true;
        } else {
            return false;
        }
    }

    function curl_post_json($url, $data = array())
    {
        $data_json = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function curl_post_json_with_auth($url, $data = array(), $token)
    {
        $data_json = json_encode($data);
        $curl = curl_init();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: $token",
                "Ua: $ua"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function grab_data($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function grab_data_auth($url, $token)
    {
        $curl = curl_init();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: $token",
                "Ua: $ua"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function status_general($status){
        if ($status == "Pending"){
            return "<span class='p-1 bg-orange-600 text-white text-sm rounded-lg'>Pending</span>";
        }else if  ($status == "Sukses"){
            return "<span class='p-1 bg-green-600 text-white text-sm rounded-lg'>Sukses</span>";
        }else if  ($status == "Gagal"){
            return "<span class='p-1 bg-red-600 text-white text-sm rounded-lg'>Gagal</span>";
        }else if  ($status == "Proses"){
            return "<span class='p-1 bg-blue-600 text-white text-sm rounded-lg'>Proses</span>";
        }else{
            return "<span class='p-1 bg-pink-600 text-white text-sm rounded-lg'>Unknown</span>";
        }
    }

    function tgl_indo($created){
        $ex_cr = explode(" ", $created);
        $tanggal = $ex_cr[0];
        $bulan = array (
            1 =>   'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Juni',
            'Juli',
            'Agus',
            'Sept',
            'Okt',
            'Nov',
            'Des'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0]." ".$ex_cr[1];
    }
}
