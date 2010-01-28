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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Adminhtml AdminNotification controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_NotificationController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/notification')
            ->_addBreadcrumb(Mage::helper('adminnotification')->__('Messages Inbox'), Mage::helper('adminhtml')->__('Messages Inbox'))
            ->_addContent($this->getLayout()->createBlock('adminhtml/notification_inbox'))
            ->renderLayout();
    }

    public function markAsReadAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $session = Mage::getSingleton('adminhtml/session');
            $model = Mage::getModel('adminnotification/inbox')
                ->load($id);

            if (!$model->getId()) {
                $session->addError(Mage::helper('adminnotification')->__('Unable to proceed. Please, try again'));
                $this->_redirect('*/*/');
                return ;
            }

            try {
                $model->setIsRead(1)
                    ->save();
                $session->addSuccess(Mage::helper('adminnotification')->__('Message was successfully marked as read'));
            }
            catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('Error while marking as read. Please try again later.'));
            }

            $this->_redirectReferer();
            return;
        }
        $this->_redirect('*/*/');
    }

    public function massMarkAsReadAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $ids = $this->getRequest()->getParam('notification');
        if (!is_array($ids)) {
            $session->addError(Mage::helper('adminnotification')->__('Please select messages'));
        }
        else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('adminnotification/inbox')
                        ->load($id);
                    if ($model->getId()) {
                        $model->setIsRead(1)
                            ->save();
                    }
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('adminnotification')->__('Total of %d record(s) were successfully marked as read', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('Error while marking as read. Please try again later.'));
            }
        }
        $this->_redirect('*/*/');
    }

    public function removeAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $session = Mage::getSingleton('adminhtml/session');
            $model = Mage::getModel('adminnotification/inbox')
                ->load($id);

            if (!$model->getId()) {
                $session->addError(Mage::helper('adminnotification')->__('Unable to proceed. Please, try again'));
                $this->_redirect('*/*/');
                return ;
            }

            try {
                $model->setIsRemove(1)
                    ->save();
                $session->addSuccess(Mage::helper('adminnotification')->__('Message was successfully removed'));
            }
            catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('Error while removing. Please try again later.'));
            }

            $this->_redirectReferer();
            return;
        }
        $this->_redirect('*/*/');
    }

    public function massRemoveAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $ids = $this->getRequest()->getParam('notification');
        if (!is_array($ids)) {
            $session->addError(Mage::helper('adminnotification')->__('Please select messages'));
        }
        else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('adminnotification/inbox')
                        ->load($id);
                    if ($model->getId()) {
                        $model->setIsRemove(1)
                            ->save();
                    }
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('adminnotification')->__('Total of %d record(s) were successfully removed', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('Error while marking. Please try again later.'));
            }
        }
        $this->_redirectReferer();
    }

    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'markAsRead':
                $acl = 'system/adminnotification/mark_as_read';
                break;

            case 'massMarkAsRead':
                $acl = 'system/adminnotification/mark_as_read';
                break;

            case 'remove':
                $acl = 'system/adminnotification/remove';
                break;

            case 'massRemove':
                $acl = 'system/adminnotification/remove';
                break;

            default:
                $acl = 'system/adminnotification/show_list';
        }
        return Mage::getSingleton('admin/session')->isAllowed($acl);
    }
}
