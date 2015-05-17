<?php

class Application_DeviceController extends Core_Controller_Default {

    /**
     * @todo Rediriger l'utilisateur sur la bonne url en fonction de son device (App Store, Google Play ou site mobile)
     */
    public function checkAction() {

        die;

    }

    public function apkisgeneratedAction(){

        $appName = $this->getRequest()->getParam('app_name');
        $apk_base_path = Core_Model_Directory::getBasePathTo("var/tmp/applications/android/$appName/Siberian/app/build/outputs/apk/app-release.apk");
        $apk_path = Core_Model_Directory::getPathTo("var/tmp/applications/android/$appName/Siberian/app/build/outputs/apk/app-release.apk");

        $apk_is_generated = false;
        $link = $this->getUrl().$apk_path;
        $link = str_replace("//", "/", $link);

        if(file_exists($apk_base_path)) {
            if(time()-filemtime($apk_base_path) <= 600) {
                $apk_is_generated = true;
            }
        }

        $user = new Backoffice_Model_User();

        try {

            $user = $user->findAll(null, "user_id ASC", array("limit" => "1"))->current();

            $sender = System_Model_Config::getValueFor("support_email");
            $support_name = System_Model_Config::getValueFor("support_name");
            $layout = $this->getLayout()->loadEmail('application', 'download_source');
            $subject = $this->_('Android APK Generation');
            $layout->getPartial('content_email')->setLink($link);
            $layout->getPartial('content_email')->setApkStatus($apk_is_generated);

            $content = $layout->render();

            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyHtml($content);
            $mail->setFrom($sender, $support_name);
            $mail->addTo($user->getEmail());
            $mail->setSubject($subject);
            $mail->send();

        } catch(Exception $e) {
            $logger = Zend_Registry::get("logger");
            $logger->sendException("Fatal Error Sending the APK Generation Email: \n".print_r($e, true), "apk_generation_");
        }

        die('ok');
    }

}
