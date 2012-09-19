<?php
require_once('../lib/bootstrap.php');

use Symfony\Component\Yaml\Parser;
use rubikscomplex\model\Test;
use rubikscomplex\model\Question;
use rubikscomplex\model\Answer;
use rubikscomplex\model\TestGroup;
use rubikscomplex\model\TestGrouping;
use rubikscomplex\util\Importer;

$pageTitle = 'Import Test YML';
include('../template/header.php');

printf('<h1>IMPORT TEST YML</h1>');

if (!isset($_REQUEST['filename'])) {
    printf('<p class="error">Error: No filename specified.</p>');
}
else {
    $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : '';
    $filename = $dir.DIRECTORY_SEPARATOR.$_REQUEST['filename'];

    $debug = array();
    $debug['output'] = '';

    $error = Importer::importYML($filename, $em, $debug);

    if ($error !== null) {
        printf('<p class="error">Error importing file <i>\'%s\'</i>: %s</p>', $filename, $error);
    }
    else {
        printf('<p class="success">Successfully imported file <i>\'%s\'</i>.</p>', $filename);
    }

    printf('<h2>Debug:</h2>');
    printf($debug['output']);
}

include('../template/footer.php');
?>
