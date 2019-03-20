<?php

/**
 * A PHP helper class to efficiently defer JavaScript for your website.
 * (c) 2019 AppSeeds https://appseeds.net/
 *
 * @package   shinsenter/defer.php
 * @since     1.0.0
 * @author    Mai Nhut Tan <shin@shin.company>
 * @copyright 2019 AppSeeds
 * @see       https://github.com/shinsenter/defer.php/blob/develop/README.md
 */

namespace shinsenter;

trait DeferOptions
{
    public $options = [
        // Debug optimized tags (instead of optimized HTML)
        'debug_mode'            => false,

        // Disable libxml errors and warnings
        'hide_warnings'         => true,

        // Library injection
        'append_defer_js'       => false,
        'default_defer_time'    => 10,

        // Page optimizations
        'enable_preloading'     => true,
        'enable_dns_prefetch'   => true,
        'fix_render_blocking'   => true,
        'minify_output_html'    => true,

        // Tag optimizations
        'enable_defer_css'      => true,
        'enable_defer_scripts'  => true,
        'enable_defer_images'   => true,
        'enable_defer_iframes'  => true,

        // Web-font optimizations
        'defer_web_fonts'       => true,
        'web_fonts_patterns'    => [
            '_debugbar.*stylesheets',
            'fonts\.google(apis)?\.com',
            '(font-awesome|typicons|devicons|iconset)([-_][\d\.]+)?(\.min)?\.css',
        ],

        // Blacklist patterns
        'do_not_optimize'       => [
            'document\.write\s*\(',
            'modernizr([-_][\d\.]+)?(\.min)?\.js',
            '(jquery([-_][\d\.]+)?(\.min)?\.js|jquery-core)',
        ],

        // Content placeholders
        'use_color_placeholder' => false,
        'empty_gif'             => 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
        'empty_src'             => 'about:blank',
    ];

    /**
     * @since  1.0.0
     * @param $key
     * @return mixed
     */
    public function __get($key = null)
    {
        if (is_null($key)) {
            return $this->options;
        }

        if (!isset($this->options[$key])) {
            $key = $this->getOptionKey($key);
        }

        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return false;
    }

    /**
     * @since  1.0.0
     * @param $property
     * @param $value
     */
    public function __set($property, $value = null)
    {
        if (is_array($property)) {
            $values = $property;
        } else {
            $values = [$property => $value];
        }

        foreach ($values as $key => $flag) {
            if (!isset($this->options[$key])) {
                $key = $this->getOptionKey($key);
            }

            if (isset($this->options[$key])) {
                switch (true) {
                    case is_array($this->options[$key]):
                        $this->options[$key] = array_merge($this->options[$key], (array) $flag);
                        break;
                    case is_numeric($this->options[$key]):
                        $this->options[$key] = (int) $flag;
                        break;
                    case is_string($this->options[$key]):
                        $this->options[$key] = (string) $flag;
                        break;
                    default:
                        $this->options[$key] = (bool) $flag;
                        break;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | The options for AMP page
    |--------------------------------------------------------------------------
     */

    /**
     * @since  1.0.0
     * @return mixed
     */
    protected function setAmpOptions()
    {
        $this->__set([
            'enable_preloading'    => false,
            'fix_render_blocking'  => false,
            'append_defer_js'      => false,
            'enable_defer_css'     => false,
            'enable_defer_scripts' => false,
            'enable_defer_images'  => false,
            'enable_defer_iframes' => false,
            'defer_web_fonts'      => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Getter and Setter
    |--------------------------------------------------------------------------
     */

    /**
     * @since  1.0.0
     * @param $property
     */
    protected function getOptionKey($property)
    {
        return trim(strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $property)));
    }
}