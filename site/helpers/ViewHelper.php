<?php
abstract class ViewHelper {
	public static function loadModel($view, $name, $prefix, $config = array()) {
		$modelName = preg_replace ( '/[^A-Z0-9_]/i', '', $name );
		$classPrefix = preg_replace ( '/[^A-Z0-9_]/i', '', $prefix );
		
		$model = JModelLegacy::getInstance ( $modelName, $classPrefix, $config );
		$view->setModel($model, false);
	}
}

?>
