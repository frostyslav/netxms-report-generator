<?php
/**
 * Creates XML files for report generation.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Report generation
 * @author   Rostyslav Fridman <rostyslav.fridman@gmail.com>
 * @author   Nikita Koptel <n.koptel@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link     http://netxms.example.com
 */

require_once 'dbconfig.php';
require_once 'baseReport.php';
require_once 'genPrimaryXML.php';
require_once 'createSubReport.php';

$file = 'credentials.json';

if (file_exists($file)) {
    $json = json_decode(file_get_contents($file), true);
} else {
    die("Cannot read credentials file" . PHP_EOL);
}

$conn_string = "host=$json[host] port=$json[port] dbname=$json[dbname] user=$json[username] password=$json[password] sslmode=$json[sslmode]";

try {
    $dbconn = pg_connect($conn_string);
    if ( $dbconn ) {
        echo "Connection to DB succesfull" . PHP_EOL;
    }
} catch ( Exception $e ) {
    echo $e->getMessage();
}

$query = prepareQuery();

// Example intervals for reports
$intervals = array( "1 day" => "daily", "7 days" => "weekly", "1 month" => "monthly" );

foreach ( $intervals as $interval => $hrf_interval ) { // HRF = Human Readable Format
    $result = pg_query($query);

    if ( !$result ) {
        echo "An error occurred" . PHP_EOL;
    }
    $mainReport = new GenPrimaryXML();
    $mainReport->setFileName("$hrf_interval.jrxml");
    $mainReport->createXML();
    echo $mainReport->getFileName("$hrf_interval.jrxml") . PHP_EOL;

    $sorted_results = array();

    while ($row = pg_fetch_row($result)) {
        $sorted_results[$row[1]] = $row[0];
    }

    natsort($sorted_results);

    foreach ($sorted_results as $node_id => $node_name) {

        if ( !isset( $node_name ) || !isset( $node_id ) ) {
            die( "Query error or out of range" . PHP_EOL );
        }

        /*
            One graph on page example
        */

        $subquery     = " AND (items.name = 'System.CPU.Usage')";
        $description  = "system cpu usage";
        populateSubReport($mainReport, $node_id, $node_name, $interval, $hrf_interval, $description, $subquery, $subquery2, $first_color, $second_color, $parameter = null, $graph_type);

        /*
            Two graphs on page example
        */

        $subquery     = " AND (items.name = 'System.CPU.Usage')";
        $subquery2    = " AND (items.name = 'System.Memory.Physical.Free')";
        $description  = "system load";
        $first_color  = "#009933";
        $second_color = "#FF9933";
        $graph_type   = "two on page";
        populateSubReport($mainReport, $node_id, $node_name, $interval, $hrf_interval, $description, $subquery);

        /*
            Use of parameter example
            We are changing NetXMS local variables to readable names
            This can slow down the generation of graphs
        */

        $subquery     = " AND (items.name = 'System.CPU.Usage'" .
                        " AND items.name = 'System.Memory.Physical.Free'";
        $first_color  = "#9999FF";
        $second_color = "#FFCC00";
        $description  = "human readable system load";
        $parameter    = "CASE
                            WHEN items.name = 'System.CPU.Usage' THEN 'CPU Load'
                            WHEN items.name = 'System.Memory.Physical.Free' THEN 'Free memory'
                        END";
        populateSubReport($mainReport, $node_id, $node_name, $interval, $hrf_interval, $description, $subquery, $subquery2 = null, $first_color, $second_color, $parameter);
    }

    // Add closing tags on the main reports
    $mainReport->closeXML();
}

pg_close($dbconn);

/**
 * Populates subreport
 *
 * @param object $report       Report object
 * @param string $node_id      Node ID
 * @param string $node_name    Node name
 * @param string $interval     Interval
 * @param string $hrf_interval HRF interval
 * @param string $description  Description
 * @param string $subquery     SubQuery
 * @param string $subquery2    Second subquery
 * @param string $first_color  First color
 * @param string $second_color Second color
 * @param string $parameter    Parameter
 * @param string $graph_type   Graph type
 *
 * @return null
 */
function populateSubReport(
    $report,
    $node_id,
    $node_name,
    $interval,
    $hrf_interval,
    $description,
    $subquery,
    $subquery2 = null,
    $first_color = null,
    $second_color = null,
    $parameter = "items.name",
    $graph_type = "one on page"
) {

    $file_name = resolveFileName($node_name, $description, $hrf_interval);
    if ( !file_exists($file_name) ) {
        $subReport = new CreateSubReport();
        $subReport->setNodeId($node_id);
        $subReport->setNodeName($node_name);
        $subReport->setInterval($interval);
        $subReport->setHRFInterval($hrf_interval);
        $subReport->setFileName($file_name);
        $subReport->setSubQuery($subquery);
        $subReport->setDescription($description);
        $subReport->setParameter($parameter);
        if ( $graph_type == "two on page" ) {
            $subReport->setSubQuery2($subquery2);
            $subReport->setFirstColor($first_color);
            $subReport->setSecondColor($second_color);
            $subReport->createTwoGraphXML();
        } else {
            $subReport->createOneGraphXML();
        }
        echo $subReport->getFileName() . PHP_EOL;
        $report->setSubReportUUID($subReport->getUUID());
        $report->setSubReportName($file_name);
        $report->updateXML();
    } else {
        $xml    = simplexml_load_file($file_name);
        $header = $xml->attributes();
        $report->setSubReportUUID($header[ 'uuid' ]);
        $report->setSubReportName($file_name);
        $report->updateXML();
    }
}

/**
 * Resolves file name
 *
 * @param string $node_name    Node name
 * @param string $description  Description
 * @param string $hrf_interval HRF interval
 *
 * @return string
 */
function resolveFileName( $node_name, $description, $hrf_interval )
{
    $uname        = $node_name;
    $udescription = $description;
    $uname        = str_replace(' ', '_', $uname);
    $udescription = str_replace(' ', '_', $udescription);
    $file_name    = $uname . '-' . $hrf_interval . '-' . $udescription . '.jrxml';
    return $file_name;
}

/**
 * Reads from file
 *
 * @param string $filename File name
 *
 * @return string
 */
function getIncludeContents($filename)
{
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

/**
 * Generates UUID
 *
 * @return null
 */
function generateUuidV4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
