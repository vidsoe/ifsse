<?php namespace IFSSE;

final class Loader {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// private
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static $file = '';

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// public
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function get_file(){
    	return self::$file;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function load($file = ''){
    	self::$file = $file;
		add_action('plugins_loaded', [__CLASS__, 'plugins_loaded']);
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public static function plugins_loaded(){
    	if(!function_exists('is_plugin_active')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if(is_plugin_active('vidsoe/vidsoe.php')){
            vidsoe()->build_update_checker('https://github.com/vidsoe/ifsse', self::$file, 'ifsse');
        }
		foreach(glob(plugin_dir_path(self::$file) . 'classes/*.php') as $file){
			$class = basename($file, '.php');
			if('loader' === $class){
				continue;
			}
			$class = __NAMESPACE__ . '\\' . str_replace('-', '_', $class);
			require_once($file);
			if(is_callable([$class, 'load'])){
				call_user_func([$class, 'load']);
			}
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
