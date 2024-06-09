<?php
class Api {
    public $api_url = 'http://domain/api.php';
    public $api_key = '';

    public function order($data) {
        $post = array_merge(array(
            'key' => $this->api_key,
            'action' => 'add'
        ) , $data);
        return json_decode($this->connect($post));
    }

    public function status($order_id) {
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'order_id' => $order_id
        )));
    }

    private function connect($post) {
        $_post = Array();
        if (is_array($post)) {
            foreach($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
}

$api = new Api();
$order = $api->order(array('service' => 1, 'link' => 'http://example.com/test', 'quantity' => 100));
$status = $api->status($order->order_id);
?>
