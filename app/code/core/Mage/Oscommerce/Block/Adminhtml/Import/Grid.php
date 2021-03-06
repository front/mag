<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Oscommerce
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * osCommerce import grid block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Oscommerce_Block_Adminhtml_Import_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('convertOscGrid');
        $this->setDefaultSort('id');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('oscommerce/oscommerce')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    =>Mage::helper('oscommerce')->__('ID'),
            'width'     =>'50px',
            'index'     =>'import_id',
        ));
        $this->addColumn('name', array(
            'header'    =>Mage::helper('oscommerce')->__('Adapter Name'),
            'index'     =>'name',
        ));
        $this->addColumn('host', array(
            'header'    =>Mage::helper('oscommerce')->__('IP or Hostname'),
            'index'     =>'host',
            'width'     =>'120px',
        ));

        $this->addColumn('db_name', array(
            'header'    =>Mage::helper('oscommerce')->__('Db Name'),
            'index'     =>'db_name',
            'width'     =>'120px',
        ));

        $this->addColumn('created_at', array(
            'header'    =>Mage::helper('oscommerce')->__('Created At'),
            'type'      => 'date',
            'align'     => 'center',
            'index'     =>'created_at',
        ));
        $this->addColumn('updated_at', array(
            'header'    =>Mage::helper('oscommerce')->__('Updated At'),
            'type'      => 'date',
            'align'     => 'center',
            'index'     =>'updated_at',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}
