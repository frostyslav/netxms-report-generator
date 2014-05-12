<?php
/**
 * Creates XML subreport.
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
 * CreateSubReport class
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
class CreateSubReport extends BaseReport
{
    protected $node_id;
    protected $node_name;
    protected $subquery;
    protected $subquery2;
    protected $description;
    protected $parameter = "items.name";
    protected $interval = "1 day";
    protected $hrf_interval = "daily";
    protected $first_color = "#0099FF";
    protected $second_color = "#33CC00";
    protected $third_color = "#FF0033";
    protected $fourth_color = "#FF9933";

    /**
     * Magic function
     *
     * @param string $name      Name
     * @param string $parameter Parameter
     *
     * @return null
     */
    public function __call( $name, $parameter )
    {
        return false;
    }
    /**
     * Constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->uuid = generateUuidV4();
    }
    /**
     * Creates one graph XML
     *
     * @return null
     */
    public function createOneGraphXML()
    {
        $content = $this->prepareXML(getIncludeContents(__DIR__ . DIRECTORY_SEPARATOR . 'templates/one_graph.xml'));
        file_put_contents($this->file_name, $content);
    }
    /**
     * Creates two graphs XML
     *
     * @return null
     */
    public function createTwoGraphXML()
    {
        $content = $this->prepareXML(getIncludeContents(__DIR__ . DIRECTORY_SEPARATOR . 'templates/two_graph.xml'));
        file_put_contents($this->file_name, $content);
    }
    /**
     * Sets description
     *
     * @param string $description Description
     *
     * @return null
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }
    /**
     * Gets description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * Sets first color
     *
     * @param string $first_color First color
     *
     * @return null
     */
    public function setFirstColor( $first_color )
    {
        $this->first_color = $first_color;
    }
    /**
     * Gets first color
     *
     * @return string
     */
    public function getFirstColor()
    {
        return $this->first_color;
    }
    /**
     * Sets interval
     *
     * @param string $interval Interval
     *
     * @return null
     */
    public function setInterval( $interval )
    {
        $this->interval = $interval;
    }
    /**
     * Gets interval
     *
     * @return string
     */
    public function getInterval()
    {
        return $this->interval;
    }
    /**
     * Sets interval in human readable format
     *
     * @param string $hrf_interval HRF interval
     *
     * @return null
     */
    public function setHRFInterval( $hrf_interval )
    {
        $this->hrf_interval = $hrf_interval;
    }
    /**
     * Gets interval in human readable format
     *
     * @return string
     */
    public function getHRFInterval()
    {
        return $this->hrf_interval;
    }
    /**
     * Sets node id
     *
     * @param string $node_id Node ID
     *
     * @return null
     */
    public function setNodeId( $node_id )
    {
        $this->node_id = $node_id;
    }
    /**
     * Gets node id
     *
     * @return string
     */
    public function getNodeId()
    {
        return $this->node_id;
    }
    /**
     * Sets node name
     *
     * @param string $node_name Node name
     *
     * @return null
     */
    public function setNodeName( $node_name )
    {
        $this->node_name = $node_name;
    }
    /**
     * Gets node name
     *
     * @return string
     */
    public function getNodeName()
    {
        return $this->node_name;
    }
    /**
     * Sets parameter
     *
     * @param string $parameter Parameter
     *
     * @return null
     */
    public function setParameter( $parameter )
    {
        $this->parameter = $parameter;
    }
    /**
     * Gets parameter
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
    /**
     * Sets second color
     *
     * @param string $second_color Second color
     *
     * @return null
     */
    public function setSecondColor( $second_color )
    {
        $this->second_color = $second_color;
    }
    /**
     * Gets second color
     *
     * @return string
     */
    public function getSecondColor()
    {
        return $this->second_color;
    }
    /**
     * Sets subquery
     *
     * @param string $subquery Subquery
     *
     * @return null
     */
    public function setSubquery( $subquery )
    {
        $this->subquery = $subquery;
    }
    /**
     * Gets subquery
     *
     * @return string
     */
    public function getSubquery()
    {
        return $this->subquery;
    }
    /**
     * Sets second subquery
     *
     * @param string $subquery2 Subquery2
     *
     * @return null
     */
    public function setSubquery2( $subquery2 )
    {
        $this->subquery2 = $subquery2;
    }
    /**
     * Gets second subquery
     *
     * @return string
     */
    public function getSubquery2()
    {
        return $this->subquery2;
    }
    /**
     * Sets UUID
     *
     * @param string $uuid UUID
     *
     * @return null
     */
    public function setUUID( $uuid )
    {
        $this->uuid = $uuid;
    }
    /**
     * Gets uuid
     *
     * @return string
     */
    public function getUUID()
    {
        return $this->uuid;
    }

}
