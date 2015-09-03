<?php

if (!function_exists('bootform')) {

    /**
     * Access the bootform builder to avoid facades.
     * @return AdamWathan\BootForms\BasicFormBuilder
     */
    function bootform()
    {
        return app('bootform');
    }

}

if (!function_exists('request')) {

    /**
     * Access the request
     * @param  string|null $what
     * @param  mixed       $default
     * @return mixed
     */
    function request($what = null, $default = null)
    {
        $request = app('request');

        if (is_null($what)) {
            return $request;
        }

        return $request->get($what, $default);
    }

}
