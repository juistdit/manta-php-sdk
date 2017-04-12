<?php

/* Do not change this file */
$aBasic = (include __DIR__ . '/../config.basic.php');
$aTestcases = (include __DIR__ . '/../config.testcases.php');

$aConfig = array_merge ( $aBasic,$aTestcases);

return $aConfig;
