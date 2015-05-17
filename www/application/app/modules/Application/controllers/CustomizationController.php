<?php

class Application_CustomizationController extends Application_Controller_Default {

    public function indexAction() {
        $this->_redirect('application/customization_design_style/edit');
    }

    public function checkAction() {

        if($this->getRequest()->isPost()) {

            $errors = $this->getApplication()->isAvailableForPublishing();

            if(!empty($errors)) {
                $message = $this->_('In order to publish your application, we need:<br />- ');
                $message .= join('<br />- ', $errors);

                $html = array(
                    'message' => $message,
                    'message_button' => 1,
                    'message_loader' => 1
                );
            }
            else {
                $url = $this->getUrl('subscription/application/create');
                $html = array('url' => $url);
            }

            $this->getResponse()->setBody(Zend_Json::encode($html))->sendResponse();
            die;
        }

    }
}
