<?php

/**
 * @method getCategories
 * @method getStock
 * @method getStoreViewData
 * @method getWebsites
 */
class Emzee_FixtureExport_Model_Export_Product_DataRow extends Varien_Object
{
    protected $_entityId = null;

    protected $_type = null;

    protected function _construct()
    {
        $this->setData(array(
           'basics' => array(),
           'categories' => array(),
           'stock' => array(),
           'store_view_data' => array(),
           'websites' => array()
        ));
    }

    /**
     * Returns whether a product type was defined in the current row.
     *
     * @return bool
     */
    public function containsType()
    {
        return $this->_type !== null;
    }

    public function getBasics()
    {
        $result = array();
        if ($this->containsType()) {
            $result['type_id'] = $this->getType();
        }

        $result = array_merge($result, $this->getData('basics'));
        return $result;
    }

    public function setStock(array &$row)
    {
        $result = array();

        $attributes = array(
            'backorders',
            'enable_qty_increments',
            'is_decimal_divided',
            'is_in_stock',
            'is_qty_decimal',
            'manage_stock',
            'max_sale_qty',
            'min_qty',
            'min_sale_qty',
            'notify_stock_qty',
            'qty',
            'qty_increments',
            'stock_status_changed_auto',
            'use_config_backorders',
            'use_config_enable_qty_inc',
            'use_config_manage_stock',
            'use_config_max_sale_qty',
            'use_config_min_qty',
            'use_config_min_sale_qty',
            'use_config_notify_stock_qty',
            'use_config_qty_increments',
        );

        foreach ($attributes as $attribute) {
            if (array_key_exists($attribute, $row)) {
                $result[$attribute] = $row[$attribute];
                unset($row[$attribute]);
            }
        }

        $this->setData('stock', $result);
    }


    public function getType()
    {
        return $this->_type;
    }

    /**
     * Returns whether this is the first row of a product declaration.
     *
     * @return bool
     */
    public function isFirstRowOfProduct()
    {
        return $this->containsType();
    }

    public function getEntityId()
    {
        return $this->_entityId;
    }

    public function hasEntityId()
    {
        return $this->_entityId !== null;
    }

    public function processRow(array $row)
    {
        if (isset($row['_type']) && $row['_type'] !== '') {
            $this->_type = $row['_type'];
        }

        $this->removeUnsupportedData($row);
        $this->setStock($row);
        $this->setWebsites($row);
        $this->setCategories($row);
        $this->setBasics($row);
    }

    public function removeUnsupportedData(&$row)
    {
        $attributes = array(
            '_root_category', /** @todo: could be used for finding out category ids */
            '_type'
        );

        if (!$this->isFirstRowOfProduct()) {
            $attributes[] = '_attribute_set';
            $attributes[] = 'sku';
        }

        foreach ($attributes as $attribute) {
            unset($row[$attribute]);
        }
    }

    public function setBasics(array &$row)
    {
        if (isset($row['_store']) && $row['_store'] !== '') {
            $store = $row['_store'];
            unset($row['_store']);
            $storeViewData = $this->getStoreViewData();
            $storeViewData[$store] = $row;
            $this->setData('store_view_data', $storeViewData);
            return;
        }
        unset($row['_store']);
        $this->setData('basics', $row);
    }


    public function setCategories(array &$row)
    {
        if (isset($row['_category'])) {
            $categories = $this->getCategories();
            $categories[] = $row['_category'];
            $this->setData('categories', $categories);
        }
        unset($row['_category']);
    }

    public function setEntityId($id)
    {
        $this->_entityId = $id;
    }

    public function setWebsites(array &$row)
    {
        if (isset($row['_product_websites'])) {
            $websites = $this->getWebsites();
            $websites[] = $row['_product_websites'];
            $this->setData('websites', $websites);
        }
        unset($row['_product_websites']);
    }
}
