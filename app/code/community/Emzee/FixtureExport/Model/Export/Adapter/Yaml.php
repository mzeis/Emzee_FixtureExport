<?php

/**
 * Export adapter for EcomDev_PHPUnit fixture files.
 */
class Emzee_FixtureExport_Model_Export_Adapter_Yaml extends Mage_ImportExport_Model_Export_Adapter_Abstract
{
    /**
     * @var Emzee_FixtureExport_Model_Export_HandlerInterface
     */
    protected $_entityHandler = null;

    /**
     * Entity type of the export.
     *
     * @var string
     */
    protected $_entityType = null;

    /**
     * Export file handler.
     *
     * @var resource
     */
    protected $_fileHandler;

    /**
     * Indicates whether the header for the YAML file has already been written.
     *
     * @var bool
     */
    protected $_isHeaderWritten = false;

    /**
     * Line break character.
     *
     * @var string
     */
    protected $_lineBreakCharacter = "\n";

    /**
     * Closes the stream for the export file.
     *
     * @return void
     */
    public function __destruct()
    {
        if (is_resource($this->_fileHandler)) {
            fclose($this->_fileHandler);
        }
    }

    /**
     * Opens the stream for the export file.
     *
     * @return Emzee_FixtureExport_Model_Export_Adapter_Yaml
     */
    protected function _init()
    {
        $this->_fileHandler = fopen($this->_destination, 'w');
        return $this;
    }

    /**
     * Adds a line to the file.
     *
     * @param string $string The string to be written
     * @return Emzee_FixtureExport_Model_Export_Adapter_Yaml
     */
    protected function _writeLine($string)
    {
        fwrite($this->_fileHandler, $string . $this->_lineBreakCharacter);
        return $this;
    }

    /**
     * Adds multiple lines to the file.
     *
     * @param array $strings An array of strings
     * @return Emzee_FixtureExport_Model_Export_Adapter_Yaml
     */
    protected function _writeLines(array $strings)
    {
        $output = implode($this->_lineBreakCharacter, $strings);
        $this->_writeLine($output);
        return $this;
    }

    /**
     * MIME-type for 'Content-Type' header.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'text/yaml';
    }

    /**
     * Returns file extension for downloading.
     *
     * @return string
     */
    public function getFileExtension()
    {
        return 'yaml';
    }

    /**
     * Implements this method without code because core code always calls it in the beginning (whether we need it or not).
     *
     * @param array $headerCols
     * @throws Exception
     * @return Mage_ImportExport_Model_Export_Adapter_Abstract
     */
    public function setHeaderCols(array $headerCols)
    {
        return $this;
    }

    /**
     * Writes the header of the YAML file.
     *
     * @param array $rowData
     * @throws Exception
     * @return Emzee_FixtureExport_Model_Export_Adapter_Yaml
     */
    public function writeHeader(array $rowData)
    {
        switch ($this->_getEntityType($rowData)) {
            case Mage_Catalog_Model_Product::ENTITY:
                $this->_writeLine('eav:')
                     ->_writeLine('  catalog_product:');
                break;
            default:
                throw new Exception(Mage::helper('emzee_fixtureexport')->__("Export of this data type is not supported."));
        }

        $this->_isHeaderWritten = true;
        return $this;
    }

    /**
     * Flushes the last product to the export file and returns the export file.
     *
     * @return string
     */
    public function getContents()
    {
        /**
         * @todo: Refactor
         */
        $data = $this->_entityHandler->flushProduct();
        if (is_array($data) && !empty($data)) {
            $this->_writeLines($data);
        } elseif (is_string($data)) {
            $this->_writeLine($data);
        }

        return parent::getContents();
    }

    /**
     * Write row data to source file.
     *
     * @param array $rowData
     * @throws Exception
     * @return Emzee_FixtureExport_Model_Export_Adapter_Yaml
     */
    public function writeRow(array $rowData)
    {
        if (false === $this->_isHeaderWritten) {
            $this->writeHeader($rowData);
        }

        switch ($this->_getEntityType($rowData)) {
            case Mage_Catalog_Model_Product::ENTITY:
                $data = $this->_entityHandler->getDataToBeWritten($rowData);
                if (is_array($data) && !empty($data)) {
                    $this->_writeLines($data);
                } elseif (is_string($data)) {
                    $this->_writeLine($data);
                }
                break;
            default:
                throw new Exception(Mage::helper('emzee_fixtureexport')->__("Export of this data type is not supported."));
        }

        return $this;
    }

    /**
     * Determines which entity type the data row describes.
     *
     * @param array $rowData
     * @return null|string The name of the entity type or null.
     */
    protected function _getEntityType(array $rowData)
    {
        if ($this->_entityType !== null) {
            return $this->_entityType;
        }

        if (isset($rowData['_type'])) {
            $this->_entityHandler = Mage::getModel('emzee_fixtureexport/export_product_handler');
            $this->_entityType = Mage_Catalog_Model_Product::ENTITY;
        }

        return $this->_entityType;
    }

    /**
     * @return Emzee_FixtureExport_Model_Export_Entity_HandlerInterface
     */
    public function getEntityHandler()
    {
        return $this->_entityHandler;
    }


}
