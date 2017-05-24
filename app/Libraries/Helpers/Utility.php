<?php

namespace App\Libraries\Helpers;

use Intervention\Image\Facades\Image;

class Utility
{
    const AUTO_COMPLETE_LIMIT = 20;

    public static function getValidImageExt($extensionDot = false)
    {
        if($extensionDot == true)
            return ['.jpg', '.jpeg', '.png', '.gif', '.JPG', '.JPEG', '.PNG', '.GIF'];

        return ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF'];
    }

    public static function saveFile($uploadFile, $savePath, $validExtensions)
    {
        if(in_array($uploadFile->getClientOriginalExtension(), $validExtensions))
        {
            $fullSavePath = public_path() . $savePath;

            if(!file_exists($fullSavePath))
                mkdir($fullSavePath, 0755, true);

            $fileName = str_replace('.', '', microtime(true)) . '.' . strtolower($uploadFile->getClientOriginalExtension());

            $uploadFile->move($fullSavePath, $fileName);

            $filePath = $fullSavePath . '/' . $fileName;
            $fileUrl = url($savePath . '/' . $fileName);

            return [$filePath, $fileUrl];
        }

        return [null, null];
    }

    public static function deleteFile($fileUrl)
    {
        $fileUrlParts = explode(request()->getHttpHost(), $fileUrl);

        if(count($fileUrlParts) > 1)
        {
            $filePath = $fileUrlParts[1];

            if($filePath[0] != '/')
                $filePath = '/' . $filePath;

            $filePath = public_path() . $filePath;

            if(file_exists($filePath) && is_file($filePath))
                unlink($filePath);
        }
    }

    public static function resizeImage($imagePath, $width)
    {
        $image = Image::make($imagePath);

        $imageWidth = $image->width();

        if($imageWidth > $width)
        {
            $image->resize($width, null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->save($imagePath);
        $image->destroy();
    }

    public static function formatNumber($number, $delimiter = '.')
    {
        if(!empty($number))
        {
            $number = implode('', explode($delimiter, $number));

            $formatted = '';
            $sign = '';

            if($number < 0)
            {
                $number = -$number;
                $sign = '-';
            }

            while($number >= 1000)
            {
                $mod = $number % 1000;

                if($formatted != '')
                    $formatted = $delimiter . $formatted;
                if($mod == 0)
                    $formatted = '000' . $formatted;
                else if($mod < 10)
                    $formatted = '00' . $mod . $formatted;
                else if($mod < 100)
                    $formatted = '0' . $mod . $formatted;
                else
                    $formatted = $mod . $formatted;

                $number = (int)($number / 1000);
            }

            if($formatted != '')
                $formatted = $sign . $number . $delimiter . $formatted;
            else
                $formatted = $sign . $number;

            return $formatted;
        }
        
        return 0;
    }
}