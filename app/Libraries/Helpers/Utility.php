<?php

namespace App\Libraries\Helpers;

use Illuminate\Support\Facades\Cookie;
use Intervention\Image\Facades\Image;

class Utility
{
    const ACTIVE_DB = 1;
    const INACTIVE_DB = 0;

    const TRUE_LABEL = 'Kích Hoạt';
    const FALSE_LABEL = 'Vô Hiệu';

    const AUTO_COMPLETE_LIMIT = 20;

    const LARGE_SET_LIMIT = 1000;

    const LANGUAGE_COOKIE_NAME = 'language';
    const BACK_URL_COOKIE_NAME = 'back_url';

    const MINUTE_ONE_MONTH = 43200;

    const SECOND_ONE_HOUR = 3600;

    const FRONTEND_ROWS_PER_PAGE = 6;

    public static function getTrueFalse($value = null)
    {
        $trueFalse = [
            self::ACTIVE_DB => self::TRUE_LABEL,
            self::INACTIVE_DB => self::FALSE_LABEL,
        ];

        if($value !== null && isset($trueFalse[$value]))
            return $trueFalse[$value];

        return $trueFalse;
    }

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

    public static function formatTimeString($second)
    {
        if(!empty($second))
        {
            $timeString = '';

            if($second >= 3600)
            {
                $mod = $second % 3600;

                $hours = (int)($second / 3600);

                $second = $mod;

                $timeString .= $hours . ' h';
            }

            if($second >= 60)
            {
                $mod = $second % 60;

                $minutes = (int)($second / 60);

                $second = $mod;

                if($timeString != '')
                    $timeString .= ' ';

                $timeString .= $minutes . ' m';
            }

            if($second > 0)
            {
                if($timeString != '')
                    $timeString .= ' ';

                $timeString .= $second . ' s';
            }

            return $timeString;
        }

        return '';
    }

    public static function setBackUrlCookie($request, $backUrlPaths)
    {
        $set = false;

        $referer = $request->headers->get('referer');

        if(!empty($referer))
        {
            if(is_array($backUrlPaths))
            {
                $hasPath = false;

                foreach($backUrlPaths as $backUrlPath)
                {
                    $hasPath = strpos($referer, $backUrlPath);

                    if($hasPath !== false)
                        break;
                }
            }
            else
                $hasPath = strpos($referer, $backUrlPaths);

            if($hasPath !== false && $referer != $request->fullUrl())
            {
                Cookie::queue(Cookie::make(self::BACK_URL_COOKIE_NAME, $referer, 10));

                $set = true;
            }
            else if(Cookie::get(self::BACK_URL_COOKIE_NAME) && $referer == $request->fullUrl())
                $set = true;
        }

        if($set == false)
            Cookie::queue(Cookie::forget(self::BACK_URL_COOKIE_NAME));
    }

    public static function getBackUrlCookie($defaultBackUrl)
    {
        $backUrl = Cookie::queued(self::BACK_URL_COOKIE_NAME);

        if(empty($backUrl))
            $backUrl = Cookie::get(self::BACK_URL_COOKIE_NAME);
        else
            $backUrl = $backUrl->getValue();

        if(!empty($backUrl))
            return $backUrl;

        return $defaultBackUrl;
    }

    public static function getValueByLocale($obj, $attributeName)
    {
        $locate = app()->getLocale();

        if($locate == 'en')
            $locateAttributeName = $attributeName . '_en';
        else
            $locateAttributeName = $attributeName;

        if(is_object($obj))
        {
            if(!empty($obj->$locateAttributeName))
                return $obj->$locateAttributeName;

            if(!empty($obj->$attributeName))
                return $obj->$attributeName;
        }
        else
        {
            if(!empty($obj[$locateAttributeName]))
                return $obj[$locateAttributeName];

            if(!empty($obj[$attributeName]))
                return $obj[$attributeName];
        }

        return '';
    }
}