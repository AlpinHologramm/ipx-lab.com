<?php

class Padlock_ApplicationController extends Application_Controller_Default
{

    public function editpostAction() {

        if($datas = $this->getRequest()->getPost()) {

            try {
                $application = $this->getApplication();

                // Test s'il y a un value_id
                if(empty($datas['value_id'])) throw new Exception($this->_('An error occurred while saving. Please try again later.'));

                // Récupère l'option_value en cours
                $option_value = new Application_Model_Option_Value();
                $option_value->find($datas['value_id']);

                // Test s'il y a embrouille entre la value_id en cours de modification et l'application en session
                if(!$option_value->getId() OR $option_value->getAppId() != $this->getApplication()->getId()) {
                    throw new Exception($this->_('An error occurred while saving. Please try again later.'));
                }

                // Prépare le weblink
                $html = array();
                $padlock = $option_value->getObject();
                if(!$padlock->getId()) {
                    $padlock->setValueId($datas['value_id']);
                }

                $value_ids = array();
                if(!empty($datas['app_is_locked'])) {
                    $application->setRequireToBeLoggedIn(1);
                } else {
                    $value_ids = !empty($datas['value_ids']) ? $datas['value_ids'] : array();
                    $application->setRequireToBeLoggedIn(0);
                }

                $allow_everyone = (int) !empty($datas['allow_all_customers_to_access_locked_features']);
                $application->setData('allow_all_customers_to_access_locked_features', $allow_everyone)->save();

                $padlock->setAppId($application->getId())
                    ->setValueIds($value_ids)
                    ->save()
                ;

                $html = array(
                    'success' => '1',
                    'success_message' => $this->_('Info successfully saved'),
                    'message_timeout' => 2,
                    'message_button' => 0,
                    'message_loader' => 0
                );

            }
            catch(Exception $e) {
                $html = array(
                    'message' => $e->getMessage(),
                    'message_button' => 1,
                    'message_loader' => 1
                );
            }

            $this->getResponse()
                ->setBody(Zend_Json::encode($html))
                ->sendResponse()
            ;
            die;

        }

    }

}