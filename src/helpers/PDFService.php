<?php

namespace quoma\core\helpers;

use quoma\modules\config\models\Config;
use Yii;
use yii\base\Component;
use GuzzleHttp\Client;

/**
 * Description of PDFService
 *
 * @author mmoyano
 */
class PDFService extends Component
{
    const LANDSCAPE = 'Landscape';
    const PORTRAIT = 'Portrait';
    /* orientation = The orientation of the output document, must be either const LANDSCAPE OR PORTRAIT.
     */
    public static function makePdf($view, $orientation = PDFService::PORTRAIT)
    {
        $url = Config::getValue('wkhtmltopdf_docker_host');
        $port = Config::getValue('wkhtmltopdf_docker_port');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_PORT, $port);

        $data = [
            'html' => $view,
            'orientation' => $orientation,
            'options' => array(
            ),
        ];

        $data_str = http_build_query($data);

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . mb_strlen($data_str),
            'Expect:'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);

        $result = curl_exec($ch);

        if ($result === false) {
            $message = 'Curl error: ' . curl_error($ch);
            \Yii::error($message);
            error_log($message);
        }

        curl_close($ch);

        return $result;
    }
}
