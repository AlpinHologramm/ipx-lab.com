<?php

class Application_Customization_Design_StyleController extends Application_Controller_Default {

    public function editAction() {
        $this->loadPartials();
        if($this->getRequest()->isXmlHttpRequest()) {
            $html = array('html' => $this->getLayout()->getPartial('content_editor')->toHtml());
            $this->getLayout()->setHtml(Zend_Json::encode($html));
        }
    }

    public function changelayoutAction() {

        if($datas = $this->getRequest()->getPost()) {

            try {
                $html = array();

                if(empty($datas['layout_id'])) throw new Exception($this->_('An error occurred while changing your layout.'));

                $layout = new Application_Model_Layout_Homepage();
                $layout->find($datas['layout_id']);

                if(!$layout->getId()) throw new Exception($this->_('An error occurred while changing your layout.'));

                $html = array('success' => 1);

                if($layout->getId() != $this->getApplication()->getLayoutId()) {

                    $visibility = $layout->getVisibility();

                    switch($layout->getVisibility()) {
                        case Application_Model_Layout_Homepage::VISIBILITY_ALWAYS:
                        case Application_Model_Layout_Homepage::VISIBILITY_HOMEPAGE:
                            $visibility = Application_Model_Layout_Homepage::VISIBILITY_HOMEPAGE;
                            break;
                        case Application_Model_Layout_Homepage::VISIBILITY_TOGGLE:
                            $visibility = Application_Model_Layout_Homepage::VISIBILITY_TOGGLE;
                            break;
                    }

                    $this->getApplication()
                        ->setLayoutId($datas['layout_id'])
                        ->setLayoutVisibility($visibility)
                        ->save()
                    ;
                    $html['reload'] = 1;
                    $html["display_layout_options"] = $layout->getVisibility() == Application_Model_Layout_Homepage::VISIBILITY_ALWAYS;
                    $html["layout_id"] = $layout->getId();
                    $html["layout_visibility"] = $this->getApplication()->getLayoutVisibility();
                }

            }
            catch(Exception $e) {
//                $html = array('message' => 'Une erreur est survenue lors de la sauvegarde, merci de réessayer ultérieurement');
                $html = array(
                    'message' => $e->getMessage(),
                    'message_button' => 1,
                    'message_loader' => 1,
                );
            }

            $this->getLayout()->setHtml(Zend_Json::encode($html));

        }

    }

    public function changelayoutvisibilityAction() {

        if($datas = $this->getRequest()->getPost()) {

            try {
                $html = array();

                if(empty($datas['layout_id'])) throw new Exception($this->_('An error occurred while changing your layout.'));

                $layout = new Application_Model_Layout_Homepage();
                $layout->find($datas['layout_id']);

                if(!$layout->getId()) throw new Exception($this->_('An error occurred while changing your layout.'));

                $html = array();

                if($layout->getId() == $this->getApplication()->getLayoutId()) {

                    $html["success"] = 1;

                    $visibility = $layout->getVisibility();

                    if($layout->getVisibility() == Application_Model_Layout_Homepage::VISIBILITY_ALWAYS) {
                        $visibility = !empty($datas["layout_is_visible_in_all_the_pages"]) ?
                            Application_Model_Layout_Homepage::VISIBILITY_ALWAYS :
                            Application_Model_Layout_Homepage::VISIBILITY_HOMEPAGE;
                    }

                    $this->getApplication()
                        ->setLayoutId($datas['layout_id'])
                        ->setLayoutVisibility($visibility)
                        ->save()
                    ;
                    $html['reload'] = 1;
                    $html["display_layout_options"] = $layout->getVisibility() == Application_Model_Layout_Homepage::VISIBILITY_ALWAYS;
                    $html["layout_id"] = $layout->getId();
                    $html["layout_visibility"] = $this->getApplication()->getLayoutVisibility();
                }

            }
            catch(Exception $e) {
                $html = array(
                    'message' => $e->getMessage(),
                    'message_button' => 1,
                    'message_loader' => 1,
                );
            }

            $this->getLayout()->setHtml(Zend_Json::encode($html));

        }

    }

    public function mutualizebackgroundimagesAction() {

        try {
            $this->getApplication()
                ->setUseHomepageBackgroundImageInSubpages((int) $this->getRequest()->getPost('use_homepage_background_image_in_subpages', 0))
                ->save()
            ;

            $html = array('success' => '1');

        }
        catch(Exception $e) {
            $html = array('message' => $e->getMessage());
        }

        $this->getLayout()->setHtml(Zend_Json::encode($html));
    }

    public function savehomepageAction() {

        if($datas = $this->getRequest()->getPost()) {

            try {

                $application = $this->getApplication();
                $filetype = $this->getRequest()->getParam('filetype');
                $relative_path = '/'.$this->getApplication()->getId().'/homepage_image/'.$filetype.'/';
                $folder = Application_Model_Application::getBaseImagePath().$relative_path;
                $datas['dest_folder'] = $folder;
                $datas['ext'] = 'jpg';
                
                $uploader = new Core_Model_Lib_Uploader();
                $file = $uploader->savecrop($datas);
                $url = "";

                switch($filetype) {
                    case "standard":
                        $application->setBackgroundImage($relative_path.$file);
                        break;
                    case "hd":
                        $application->setBackgroundImageHd($relative_path.$file);
                        break;
                    case "tablet":
                        $application->setBackgroundImageTablet($relative_path.$file);
                        break;
                }
                
                $application->save();

                $url = $application->getHomepageBackgroundImageUrl($filetype);

                $datas = array(
                    'success' => 1,
                    'file' => $url
                );
            } catch (Exception $e) {
                $datas = array(
                    'error' => 1,
                    'message' => $e->getMessage()
                );
            }

            $this->getLayout()->setHtml(Zend_Json::encode($datas));
        }
    }

    public function deletehomepageAction() {
        $filetype = $this->_request->getparam('filetype');
        try {
            if($filetype == 'bg') {
                $this->getApplication()->setHomepageBackgroundImageRetinaLink(null);
                $this->getApplication()->setHomepageBackgroundImageLink(null);
                $this->getApplication()->setHomepageBackgroundImageId(null);
            } else if($filetype == 'icon') {
                $this->getApplication()->setHomepageLogoLink(null);
            }
            $this->getApplication()->save();
            $html = array('success' => '1');
        } catch(Exception $e) {
            $html = array('message' => $e->getMessage());
        }
        $this->getLayout()->setHtml(Zend_Json::encode($html));
    }

    public function savefontAction() {
        if($datas = $this->getRequest()->getPost()) {

            try {
                if(!empty($datas['font_family'])) $this->getApplication()->setFontFamily($datas['font_family']);
//                if(!empty($datas['font_size'])) $this->getApplication()->setFontSize($datas['font_size']);

                $this->getApplication()->save();

                $html = array('success' => '1');

            }
            catch(Exception $e) {
                $html = array('message' => $e->getMessage());
            }

            $this->getLayout()->setHtml(Zend_Json::encode($html));
        }
    }

    public function savelocaleAction() {
        if($datas = $this->getRequest()->getPost()) {

            try {
                if(!empty($datas['locale'])) $this->getApplication()->setLocale($datas['locale']);
                $this->getApplication()->save();
                $html = array('success' => '1');
            }
            catch(Exception $e) {
                $html = array('message' => $e->getMessage());
            }

            $this->getLayout()->setHtml(Zend_Json::encode($html));
        }
    }

}

