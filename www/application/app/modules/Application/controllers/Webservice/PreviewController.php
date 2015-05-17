<?php

class Application_Webservice_PreviewController extends Core_Controller_Default
{
    public function loginAction() {

        try {

            $data = $this->getRequest()->getPost();
            if (!$this->getRequest()->isPost()) {
                $data = Zend_Json::decode($this->getRequest()->getRawBody());

                $this->getResponse()->setHeader("Access-Control-Allow-Credentials", true, true);
                $this->getResponse()->setHeader("Access-Control-Allow-Methods", "PUT", true);
                $this->getResponse()->setHeader("Access-Control-Allow-Origin", "*", true);
                $this->getResponse()->setHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept, Pragma", true);

            }

            if (!empty($data)) {

                $canBeLoggedIn = false;

                if (empty($data['email']) OR empty($data['password'])) {
                    throw new Exception($this->_('Authentication failed. Please check your email and/or your password'));
                }
                $admin = new Admin_Model_Admin();
                $admin->findByEmail($data['email']);

                if ($admin->authenticate($data['password'])) {

                    $applications = $admin->getApplications();

                    $data = array('applications' => array());

                    foreach ($applications as $application) {

                        if(!$application->isActive()) continue;

                        $url = parse_url($application->getUrl());
                        $key = "";
                        if (stripos($url["path"], $application->getKey())) {
                            $url["path"] = str_replace($application->getKey(), "", $url["path"]);
                            $key = $application->getKey();
                        }
                        $icon = '';
                        if ($application->getIcon()) {
                            $icon = $this->getRequest()->getBaseUrl() . $application->getIcon();
                        }

                        $data['applications'][] = array(
                            'id' => $application->getId(),
                            'icon' => $icon,
                            'startup_image' => str_replace("//", "/", $application->getStartupImageUrl()),
                            'startup_image_retina' => str_replace("//", "/", $application->getStartupImageUrl("retina")),
                            'name' => $application->getName(),
                            'scheme' => $url['scheme'],
                            'domain' => $url['host'],
                            'path' => ltrim($url['path'], '/'),
                            'key' => $key,
                            'url' => $application->getUrl(),
                        );

                    }

                } else {
                    throw new Exception($this->_('Authentication failed. Please check your email and/or your password'));
                }

            }

        } catch(Exception $e) {
            $data = array('error' => $this->_('Authentication failed. Please check your email and/or your password'));
        }

        $this->getResponse()->setBody(Zend_Json::encode($data))->sendResponse();
        die;

    }

}
