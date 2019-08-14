<?php

class ReJuNi_Optimizer_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    /**
     * @return Mage_Catalog_Model_Product_Image
     */
    public function saveFile()
    {
        $filename = $this->getNewFile();
        $this->getImageProcessor()->save($filename);
        Mage::helper('core/file_storage_database')->saveFile($filename);

        // Run rejuni_catalog_product_save_image_after
        Mage::dispatchEvent('rejuni_catalog_product_save_image_after', array($this->_eventObject => $this));

        return $this;
    }

}