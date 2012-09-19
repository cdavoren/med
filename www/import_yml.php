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

printf('&nbsp;');
printf('<h1>IMPORT TEST YML</h1>');

if (!isset($_REQUEST['filename']) && !isset($_REQUEST['dir'])) {
    printf('<p class="error">Error: No filename or directory specified.</p>');
}
else {
    if (isset($_REQUEST['clear'])) {
        $em->createQueryBuilder()->delete('rubikscomplex\model\Answer')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\Question')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\TestGrouping')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\Test')->getQuery()->execute();
        $em->flush();
    }

    $filenames = array();
    if (isset($_REQUEST['dir']) && !isset($_REQUEST['filename'])) {
        $filenames = glob($_REQUEST['dir'].DIRECTORY_SEPARATOR.'*.yml');
    }
    else {
        $filenames[] = $_REQUEST['filename'];
    }

    $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : '';

    $debug = array();
    $debug['output'] = '';

    foreach ($filenames as $filename) {
        printf('<p>Parsing %s...</p>', $filename);
        $error = Importer::importYML($filename, $em, $debug);

        if ($error !== null) {
            printf('<p class="error">Error importing file <i>\'%s\'</i>: %s</p>', $filename, $error);
        }
        else {
            printf('<p class="success">Successfully imported file <i>\'%s\'</i>.</p>', $filename);
        }
    }
    printf('<h2>Debug:</h2>');
    printf($debug['output']);
}

include('../template/footer.php');
?>
