<?php
/**
 * Created by PhpStorm.
 * User: zapleo
 * Date: 16.04.18
 * Time: 15:24
 */

namespace common\helpers;

use common\models\Quizes;
use yii\bootstrap\Html;

class FileUrlHelper
{
    public static function getFileType($url)
    {
        if (static::getYouTubeId($url))
            return Quizes::FILE_TYPE_YOUTUBE;

        if (static::getVimeoId($url))
            return Quizes::FILE_TYPE_VIMEO;

        if (static::checkImage($url))
            return Quizes::FILE_TYPE_IMAGE;

        return false;
    }

    public static function getYouTubeId($url)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }

        return false;
    }

    public static function getMediaContent($file_type, $file_url)
    {
        if ($file_type == Quizes::FILE_TYPE_YOUTUBE)
            return static::getYouTubePlayer($file_url);

        if ($file_type == Quizes::FILE_TYPE_VIMEO)
            return static::getVimeoPlayer($file_url);

        if ($file_type == Quizes::FILE_TYPE_IMAGE)
            return static::getImage($file_url);

        return false;
    }

    public static function getYouTubePlayer($video_id)
    {
        return '<iframe width="100%" height="300px" src="https://www.youtube.com/embed/'.$video_id.'" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>';
    }

    public static function getVimeoPlayer($video_id)
    {
        return '<iframe src="https://player.vimeo.com/video/'.$video_id.'?color=ffffff&badge=0" width="100%" height="300px" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }

    public static function getImage($url)
    {
        return Html::img($url, ['style' => 'max-width: 100%; margin-bottom: 30px;', 'class' => 'img-thumbnail']);
    }

    public static function getVimeoId($url)
    {
        if (preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $match)) {
            return $match[5];
        }

        return false;
    }

    public static function checkImage($url)
    {
        if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $url)) {
            if (static::checkRemoteFile($url))
                return true;
        }

        return false;
    }

    private static function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result !== FALSE)
            return true;

        return false;
    }
}