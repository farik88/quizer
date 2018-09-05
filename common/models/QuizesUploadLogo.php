<?php
/**
 * Created by PhpStorm.
 * User: zapleo
 * Date: 13.04.18
 * Time: 15:20
 */

namespace common\models;

use yii\base\Model;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class QuizesUploadLogo extends Model
{
    /**
     * @var UploadedFile
     */
    public $logo;

    public $crop;

    public function rules()
    {
        return [
            [['logo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif', 'maxFiles' => 1, 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * @param \common\models\base\Quizes $model
     *
     * @return bool
     */
    public function upload(\common\models\base\Quizes $model)
    {
        if ($this->validate()) {
            $path = '../../frontend/web/uploads/quize_logo';
            $picture_name = \Yii::$app->security->generateRandomString(10) . '.' . $this->logo->extension;

            if (FileHelper::createDirectory($path))
                if ($this->logo->saveAs($path . '/' . $picture_name))
                    return $this->savePicture($model, $path, $picture_name);
        }

        return false;
    }

    /**
     * @param $model
     * @param $path
     * @param $picture_name
     *
     * @return bool
     */
    private function savePicture(\common\models\base\Quizes $model, $path, $picture_name)
    {
        Image::crop($path . '/' . $picture_name, $this->crop['width'], $this->crop['height'], [$this->crop['x'], $this->crop['y']])
            ->save($path . '/' . $picture_name, ['quality' => 100]);

        if (!empty($model->logo)) {
            if (file_exists('../../frontend/web/' . $model->logo))
                FileHelper::unlink('../../frontend/web/' . $model->logo);
        }

        $model->logo = 'uploads/quize_logo/' . $picture_name;

        return $model->save();
    }
}