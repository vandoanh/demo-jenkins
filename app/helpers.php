<?php

if (!function_exists('format_date')) {

    /**
     * Function format date from a string
     *
     * @param string $strTime
     * @param string $format
     *
     * @return string
     */
    function format_date($strTime, $format = 'default')
    {
        if (empty($strTime)) {
            return '';
        }

        if (auth()->check()) {
            $timezone = auth()->user()->getTimeZone();
        } else {
            $timezone = 'Asia/Ho_Chi_Minh';
        }

        $masks = [
            'default' => 'd/m/Y H:i:s', // 20/10/2008 12:37:21
            'shortDate' => 'd/m/y', // 20/10/08
            'mediumDate' => 'd M, Y', // 20 Tháng 10, 2008
            'longDate' => 'd F, Y', // 20 Tháng mười, 2008
            'fullDate' => 'D, d F, Y', // Chủ nhật, 20 Tháng mười, 2008
            'shortTime' => 'H:i', // 5:46
            'mediumTime' => 'H:i:s', // 5:46:21
        ];

        if (array_key_exists($format, $masks)) {
            $format = $masks[$format];
        }

        return \Carbon\Carbon::parse($strTime)->timezone($timezone)->format($format);
    }

    function format_date_localize($strTime, $format = 'default')
    {
        if (empty($strTime)) {
            return '';
        }

        if (auth()->check()) {
            $timezone = auth()->user()->getTimeZone();
        } else {
            $timezone = 'Asia/Ho_Chi_Minh';
        }

        $masks = [
            'default' => '%d/%m/%Y %H:%M:%S', // 20/10/2008 12:37:21
            'article' => '%A, %d/%m/%Y',
            'shortDate' => '%d/%m/%y', // 20/10/08
            'mediumDate' => '%d %M, %Y', // 20 Tháng 10, 2008
            'longDate' => '%D, %d %F, %Y', // Chủ nhật, 20 Tháng mười, 2008
            'shortTime' => '%H:%M', // 5:46
            'mediumTime' => '%H:%M:%S', // 5:46:21
        ];

        if (array_key_exists($format, $masks)) {
            $format = $masks[$format];
        }

        //set locale
        \Carbon\Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));

        return \Carbon\Carbon::parse($strTime)->timezone($timezone)->formatLocalized($format);
    }
}

if (!function_exists('format_date_chat')) {

    /**
     * Function format date chat from a string
     *
     * @param string $strTime
     * @param string $timezone
     *
     * @return string
     */
    function format_date_chat($strTime, $timezone = 'Asia/Ho_Chi_Minh')
    {
        $date = \Carbon\Carbon::parse($strTime)->timezone($timezone);
        $now = \Carbon\Carbon::now($timezone);

        $diff = $now->diffInYears($date);
        $format = 'H:i';

        if ($diff > 1) {
            $format = 'd/m/Y H:i';
        } else {
            $diff = $now->diffInDays($date);

            if ($diff > 1) {
                $format = 'd/m H:i';
            }
        }

        return $date->format($format);
    }
}

if (!function_exists('url_static')) {

    /**
     * Function get link static
     *
     * @param string $filename
     *
     * @return mixed
     */
    function url_static($filename)
    {
        if (env('CDN_BYPASS', true)) {
            if (starts_with($filename, 'images/')) {
                return asset('static/' . $filename, request()->secure());
            } else {
                return url(mix('/static/' . $filename), [], request()->secure());
            }
        } else {
            return CDN::asset('/' . $filename);
        }
    }
}

if (!function_exists('image_url')) {

    /**
     * Function show image
     *
     * @param string $file_name
     * @param string $template
     *
     * @return string
     */
    function image_url($file_name, $template = 'original')
    {
        if (empty($file_name) || str_contains($file_name, config('constants.image.default.name'))) {
            return config('site.media.url.image') . '/no-image/' . config('constants.image.default.name');
        }

        if (str_contains($file_name, config('constants.image.avatar.name'))) {
            return config('site.media.url.image') . '/no-avatar/' . $file_name;
        }

        $file_name = ltrim($file_name, '/');

        if (!filter_var($file_name, FILTER_VALIDATE_URL) === false) {
            if (starts_with($file_name, config('site.media.url.image') . '/images/original/')) {
                return str_replace('/images/original/', '/images/' . $template . '/', $file_name);
            }

            return $file_name;
        } else {
            return config('site.media.url.image') . '/' . $template . '/' . $file_name;
        }
    }
}

if (!function_exists('show_error')) {

    /**
     * @param object $errors
     * @param string $key
     * @param string $layout_default
     *
     * @return mixed
     */
    function show_error($errors, $key, $layout_default = 'validation')
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        $arrMessage = [];
        foreach ($key as $item) {
            if ($errors->has($item)) {
                $arrMessage[] = $errors->first($item);
            }
        }

        $arrMessage = array_unique($arrMessage);
        $arrStrMessage = '';
        foreach ($arrMessage as $message) {
            $arrStrMessage .= view($layout_default, [
                'message' => $message,
                'class' => $key[0],
                'id' => $key[0] . '-error',
            ]);
        }

        return $arrStrMessage;
    }
}

if (!function_exists('check_paging')) {

    /**
     * Function to check item paging valid or not
     *
     * @param int $item
     *
     * @return int
     */
    function check_paging($item = null)
    {
        if ($item && in_array($item, config('site.general.pagination.list'))) {
            return $item;
        }

        return config('site.general.pagination.default');
    }
}

if (!function_exists('pagination')) {

    /**
     * pagination
     *
     * @param object $arrData
     * @param string $pagination
     * @param int $item
     * @param string $position
     *
     * @return string
     */
    function pagination($arrData, $pagination, $item, $position)
    {
        return view('pagination', [
            'arrData' => $arrData,
            'pagination' => $pagination,
            'item' => $item,
            'position' => $position
        ]);
    }
}
