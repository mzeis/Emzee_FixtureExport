<?php

class Emzee_FixtureExport_Model_Export_Product_Handler implements Emzee_FixtureExport_Model_Export_HandlerInterface
{
    /**
     * Entity ID counter for the fixture data set.
     *
     * @var int
     */
    protected $_entityId = 1;

    protected $_rows = array();

    /**
     * @return boolean
     */
    public function getIsProductInProgress()
    {
        return !empty($this->_rows);
    }

    /**
     * Processes the row and returns the data to be written.
     *
     * @param array $row
     * @throws Exception
     * @return array Data that should be written to the output file (one line per array entry).
     */
    public function getDataToBeWritten(array $row)
    {
        $result = array();
        $dataRow = Mage::getModel('emzee_fixtureexport/export_product_dataRow');
        $dataRow->processRow($row);
        unset($row);

        if (!$dataRow->isFirstRowOfProduct() && !$this->getIsProductInProgress()) {
            throw new Exception(Mage::helper('emzee_fixtureexport')->__("Start of new product entity expected."));
        }

        if ($dataRow->isFirstRowOfProduct()) {
            if ($this->getIsProductInProgress()) {
                $result = $this->flushProduct();
            }

            $dataRow->setEntityId($this->_entityId);
            $this->_entityId++;
        }
        $this->addRow($dataRow);

        return $result;
    }

    /**
     * @param Emzee_FixtureExport_Model_Export_Product_DataRow $dataRow
     * @return Emzee_FixtureExport_Model_Export_Product_Handler
     */
    public function addRow(Emzee_FixtureExport_Model_Export_Product_DataRow $dataRow)
    {
        $this->_rows[] = $dataRow;
        return $this;
    }

    /**
     * Gathers the information on the product and generates the array for the YAML file.
     *
     * @return array
     */
    public function flushProduct()
    {
        $basicData = array();
        $categoryData = array();
        $stockData = array();
        $storeViewData = array();
        $websiteData = array();

        foreach ($this->_rows as $row) {
            /**
             * @var $row Emzee_FixtureExport_Model_Export_Product_DataRow
             */
            if ($row->hasEntityId()) {
                array_unshift($basicData, "    - entity_id: {$row->getEntityId()}");
            }

            foreach ($row->getBasics() as $key => $value) {
                $basicData[] = "      {$key}: {$value}";
            }

            foreach ($row->getStock() as $key => $value) {
                if (empty($stockData)) {
                    $stockData[] = '      stock:';
                }
                $stockData[] = "        {$key}: {$value}";
            }

            foreach ($row->getWebsites() as $website) {
                if (empty($websiteData)) {
                    $websiteData[] = '      website_ids:';
                }
                $websiteData[] =  "        - {$website}";
            }

            foreach ($row->getCategories() as $category) {
                if (empty($categoryData)) {
                    $categoryData[] = '      category_ids:';
                }
                $categoryData[] =  "        - {$category}";
            }

            foreach ($row->getStoreViewData() as $store => $storeData) {
                if (empty($storeViewData)) {
                    $storeViewData[] = "      /stores:";
                }
                $storeViewData[] = "        {$store}:";
                foreach ($storeData as $key => $value) {
                    $storeViewData[] = "          {$key}: {$value}";
                }
            }
        }

        $result = array_merge($basicData, $stockData, $websiteData, $categoryData, $storeViewData);

        $this->reset();
        return $result;
    }

    public function reset()
    {
        $this->_rows = array();
    }
}