<?php
/**
 * Creates XML report.
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
 * GenPrimaryXML class
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
class GenPrimaryXML extends BaseReport
{
    protected $sub_report_name;
    protected $position = 0;
    /**
     * Creates XML
     *
     * @return null
     */
    public function createXML()
    {
        $contents = '<?xml version="1.0" encoding="UTF-8"?>
<jasperReport xmlns="http://jasperreports.sourceforge.net/jasperreports" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://jasperreports.sourceforge.net/jasperreports http://jasperreports.sourceforge.net/xsd/jasperreport.xsd" name="Daily system load" language="groovy" pageWidth="595" pageHeight="842" whenNoDataType="AllSectionsNoDetail" columnWidth="555" leftMargin="20" rightMargin="20" topMargin="20" bottomMargin="20" uuid="' . generateUuidV4() . '">
    <property name="ireport.zoom" value="1.0"/>
    <property name="ireport.x" value="0"/>
    <property name="ireport.y" value="0"/>
    <subDataset name="dataset1" uuid="' . generateUuidV4() . '"/>
    <parameter name="SUBREPORT_DIR" class="java.lang.String" isForPrompting="false">
        <defaultValueExpression><![CDATA["' . $this->folder . DIRECTORY_SEPARATOR . '"]]></defaultValueExpression>
    </parameter>
    <queryString>
        <![CDATA[]]>
    </queryString>
    <summary>
        <band height="802">
';
        file_put_contents($this->file_name, $contents);
    }
    /**
     * Updates XML
     *
     * @return null
     */
    public function updateXML()
    {
        $content = $this->prepareXML(getIncludeContents(__DIR__ . DIRECTORY_SEPARATOR . 'templates/subreport.xml'));
        file_put_contents($this->file_name, $content, FILE_APPEND);
        $this->position++;
    }
    /**
     * Closes XML
     *
     * @return null
     */
    public function closeXML()
    {
        $contents = '        </band>
    </summary>
</jasperReport>
';
        file_put_contents($this->file_name, $contents, FILE_APPEND);
    }
    /**
     * Sets folder
     *
     * @param string $folder Folder
     *
     * @return null
     */
    public function setFolder( $folder )
    {
        $this->folder = $folder;
    }
    /**
     * Gets folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }
    /**
     * Sets subreport name
     *
     * @param string $sub_report_name Subreport name
     *
     * @return null
     */
    public function setSubReportName( $sub_report_name )
    {
        $this->sub_report_name = $sub_report_name;
    }
    /**
     * Gets subreport name
     *
     * @return string
     */
    public function getSubReportName()
    {
        return $this->sub_report_name;
    }
    /**
     * Sets subreport uuid
     *
     * @param string $uuid UUID
     *
     * @return null
     */
    public function setSubReportUUID( $uuid )
    {
        $this->uuid = $uuid;
    }
    /**
     * Gets subreport UUID
     *
     * @return string
     */
    public function getSubReportUUID()
    {
        return $this->uuid;
    }
}
