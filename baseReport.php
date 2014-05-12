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
/**
 * Abstract class.
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
abstract class BaseReport
{
    protected $file_name;
    protected $uuid;
    protected $folder;
    /**
     * Constructor
     *
     * @param string $file File name
     *
     * @return null
     */
    public function __construct($file = 'credentials.json')
    {
        if (file_exists($file)) {
            $json = json_decode(file_get_contents($file), true);
            $this->folder = $json['folder'];
        } else {
            $this->folder = '/opt/xml-reports';
        }
    }
    /**
     * Prepares XML
     *
     * @param string $content Content
     *
     * @return string
     */
    protected function prepareXML($content)
    {
        $data = get_object_vars($this);
        $data['uuid_v4'] = generateUuidV4();
        return str_replace(
            array_map(
                create_function('$key', 'return "%" . $key . "%";'),
                array_keys($data)
            ),
            $data, $content
        );
    }
    /**
     * Gets file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }
    /**
     * Sets file name
     *
     * @param string $file_name File name
     *
     * @return null
     */
    public function setFileName($file_name)
    {
        try {
            echo $this->folder . PHP_EOL;
            if (file_exists($this->folder)) {
                if (!is_writable($this->folder)) {
                    throw new Exception('Dir is not writable');
                }
            } elseif (!mkdir($this->folder, 0777)) {
                throw new Exception('Cannot create dir');
            }
        } catch (Exception $e) {
            exit($e);
        }

        $this->file_name =  $this->folder . DIRECTORY_SEPARATOR . $file_name;
    }
}
