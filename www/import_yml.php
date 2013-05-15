<?php
require_once('../lib/init.php');

use Symfony\Component\Yaml\Parser;
use rubikscomplex\model\Test;
use rubikscomplex\model\Question;
use rubikscomplex\model\Answer;
use rubikscomplex\model\TestGroup;
use rubikscomplex\model\TestGrouping;
use rubikscomplex\util\Importer;

$pageTitle = 'Import Test YML';
include('../template/header.php');

$em = App::getManager();

printf('&nbsp;');
printf('<h1>IMPORT TEST YML</h1>');

$groupDescriptions = array(
    'HP3' => 'Health Practice 3',
    'HP4' => 'Health Practice 4',
    'HP5' => 'Health Practice 5',
    'NDM' => 'Nutrition, Digestion and Metabolism',
    'CRL' => 'Cardiac, Respiratory and Locomotor',
    'CSGD' => 'Control Systems and Development',
    'DMF' => 'Defense Mechanisms and their Failure',
    'SEM8_9' => 'Semesters 8 and 9',
    'SEM8_9_BLOCK1' => 'Cardiology, Respiratory and General Medicine',
    'SEM8_9_BLOCK2' => 'Neurosurgery, Neurology, ENT and Ophthalmology',
    'SEM8_9_BLOCK3' => 'Nephtology, Urology, Vascular Surgery and Endocrinology',
    'SEM8_9_BLOCK4' => 'Orthopaedics, Rheumatology and Dermatology',
    'SEM8_9_BLOCK5' => 'Oncology, Haematology and Infections Disease',
    'SEM8_9_BLOCK6' => 'Gastroenterology, Hepatobiliary and Colorectal Surgery',
    'SEM10_11' => 'Semesters 10 and 11',
    'SEM10_11_PAED' => 'Paediatrics',
    'SEM10_11_OBGYN' => 'Obstetrics and Gynaecology'
);

if (!isset($_REQUEST['filename']) && !isset($_REQUEST['dir'])) {
    printf('<p class="error">Error: No filename or directory specified.</p>');
}
else {
    if (isset($_REQUEST['clear'])) {
        $em->createQueryBuilder()->delete('rubikscomplex\model\Answer')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\Question')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\TestGrouping')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\Test')->getQuery()->execute();
        $em->createQueryBuilder()->delete('rubikscomplex\model\TestGroup')->getQuery()->execute();
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
        $error = null;
        try {
            $error = Importer::importYML($filename, $em, $debug, $groupDescriptions);
        }
        catch (Exception $e) {
            printf('<p>Exception caught parsing file %s: <pre>%s</pre></p>%s', $filename, $e->getMessage(), $debug['output']);
            break;
        }

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
