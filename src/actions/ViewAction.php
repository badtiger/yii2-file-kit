<?php

namespace badtiger\filekit\actions;

use yii\web\HttpException;

/**
 * Class ViewAction
 * @package badtiger\filekit\actions
 * @author Eugene Terentev <eugene@terentev.net>
 */
class ViewAction extends BaseAction
{
    /**
     * @var string path request param
     */
    public $pathParam = 'path';
    /**
     * @var boolean, whether the browser should open the file within the browser window. Defaults to false,
     * meaning a download dialog will pop up.
     */
    public $inline = false;

    /**
     * @return static
     * @throws HttpException
     * @throws \HttpException
     */
    public function run()
    {
        $path = \Yii::$app->request->get($this->pathParam);
        $filesystem = $this->getFileStorage()->getFilesystem();
        if ($filesystem->fileExists($path) === false) {
            throw new HttpException(404);
        }
        return \Yii::$app->response->sendStreamAsFile(
            $filesystem->readStream($path),
            pathinfo($path, PATHINFO_BASENAME),
            [
                'mimeType' => $filesystem->mimeType($path),
                'inline' => $this->inline
            ]
        );
    }
}
