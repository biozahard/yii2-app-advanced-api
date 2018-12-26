<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/console');

/**
 * small helper for debugging
 * @param array ...$args
 */
function dd(...$args)
{
    echo time() . "\n";
    if (empty($args)) {
    } else {
        foreach ($args as $item) {
            echo "<pre>";
            var_dump($item);
            echo "</pre>";
        }
    }
    exit;
}