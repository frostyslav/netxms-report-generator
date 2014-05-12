<?php
/**
 * Prepares PostgreSQL query
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
/**
 * Prepares query
 *
 * @return string
 */
function prepareQuery()
{
    // Enter the names of your servers, to speed up the query
    // Please change the names according to your corresponding installation
    $allowed_names = array('webserver.*', 'monitoring.*', 'database.*');

    foreach ($allowed_names as &$name) {
        $name = "object_properties.name ~ '$name'";
    }

    $query = "SELECT object_properties.name AS server, object_properties.object_id AS id FROM object_properties WHERE (";
    $query .= implode(" OR ", $allowed_names);
    $query .= ") AND object_properties.status != '6' ORDER BY server";
    return $query;
}
