<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

define('SF_ROOT_DIR', sfConfig::get('sf_root_dir'));

define('CSV_LINE_SEPARATOR',    "\n");
define('CSV_VALUE_SEPARATOR',   ";");
define('TRANSLATE_FILE_MASK',   '([a-z_]+)\.csv');
define('I18N_FILE_MASK',        '%s.%s.xml');
define('TRANSLATE_DIR',         SF_ROOT_DIR.'/data/translations');
define('I18N_DIR',              SF_ROOT_DIR.'/apps/frontend/i18n');


function getCsvVar($val) {
    $val = trim($val);
    if (ereg("^'(.*)'$", $val, $regs)) {
        $val = $regs[1];
    }
    return $val;
}
function parseCsv($str) {
	$rows = explode(CSV_LINE_SEPARATOR, trim($str));
    $values = array();
    $i = 0;
    foreach($rows as $row) {
    	if ($i == 0) {
    		$colsName = explode(CSV_VALUE_SEPARATOR, $row);
    	} else {
    		$y = 0;
            $row = trim($row);
            if (empty($row)) continue;
            if (strpos($row, '#') == 1) continue;
            $vals = explode(CSV_VALUE_SEPARATOR, $row);
            foreach($vals as $val) {
                $values[getCsvVar($colsName[$y])][getCsvVar($vals[0])] = getCsvVar($val);
                $y++;
            }
    	}
        $i++;
    }
    unset($values['base']);
    return $values;
}

function createXLIFF($lang, array $arr) {
	$dom = new DOMDocument('1.0');
    $xliff = $dom->createElement('xliff');
    $body = $dom->createElement('body');
    $body->setAttribute('original', 'global');
    $body->setAttribute('source-language', $lang);
    $body->setAttribute('datatype', 'plaintext');
    $xliff->appendChild($body);
    $dom->appendChild($xliff);
    $i = 1;
    foreach($arr as $key => $val) {
        $transUnit = $dom->createElement('trans-unit');
        $transUnit->setAttribute('id', $i++);
        $source = $dom->createElement('source', $key);
        $target = $dom->createElement('target', $val);
        $transUnit->appendChild($source);
        $transUnit->appendChild($target);
        $body->appendChild($transUnit);
    }
    $dom->encoding = 'utf-8';
    $dom->formatOutput = true;
    return $dom;
}

$files = scandir(TRANSLATE_DIR);

foreach($files as $file) {
	if (ereg(TRANSLATE_FILE_MASK, $file, $regs)) {
        $dataArray = parseCsv(file_get_contents(TRANSLATE_DIR.'/'.$file));
        foreach($dataArray as $lang => $array) {
            $dataXML = createXLIFF($lang, $array);
            $dataString = $dataXML->saveXML();
            file_put_contents(I18N_DIR.'/'.sprintf(I18N_FILE_MASK, $regs[1], $lang), $dataString);
        }
	}
}
