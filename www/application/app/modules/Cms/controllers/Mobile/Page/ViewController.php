<?php

class Cms_Mobile_Page_ViewController extends Application_Controller_Mobile_Default
{


    public function _toJson($block) {

        $block->unsMobileTemplate()->unsTemplate();
        $block_data = $block->getData();

        switch($block->getType()) {
            case "text":
                $block_data["image_url"] = $block->getImageUrl();
            break;
            case "image":
                $library = new Cms_Model_Application_Page_Block_Image_Library();
                $libraries = $library->findAll(array('library_id' => $block->getLibraryId()), 'image_id ASC', null);
                $block_data["gallery"] = array();
                foreach($libraries as $image) {
                    $block_data["gallery"][] = array(
                        "id" => $image->getId(),
                        "url" => $image->getImageFullSize()
                    );
                }
            break;
            case "video":
                $video = $block->getObject();
                $block_data["cover_url"] = $video->getImageUrl();
                $url = $video->getLink();
                $video_id = $video->getId();
                if($video->getTypeId() == "youtube") {
                    $url = "http://www.youtube.com/embed/{$video->getYoutube()}?autoplay=1";
                    $video_id = $video->getYoutube();
                }
                $block_data["url"] = $url;
                $block_data["video_id"] = $video_id;
            break;
        }

        return $block_data;

    }


    public function findallbypageAction() {
        if($value_id = $this->getRequest()->getParam('value_id')
           && $page_id = $this->getRequest()->getParam('page_id')) {

            try {

                $pageRepository = new Cms_Model_Application_Page();
                $page = $pageRepository->find($page_id);

                $json = array();

                $blocks = $page->getBlocks();
                $json = array();

                foreach($blocks as $block) {
                    $json[] = $this->_toJson($block);
                }
                $data = array("blocks" => $json);
            }
            catch(Exception $e) {
                $data = array('error' => 1, 'message' => $e->getMessage());
            }

        }else{
            $data = array('error' => 1, 'message' => 'An error occurred during process. Please try again later.');
        }

        $this->_sendHtml($data);
    }

    public function findallAction() {

        if($value_id = $this->getRequest()->getParam('value_id')) {

            $option = $this->getCurrentOptionValue();
            $page = new Cms_Model_Application_Page();
            $page->find($option->getId(), 'value_id');
            $blocks = $page->getBlocks();
            $data = array("blocks" => array());

            foreach($blocks as $block) {
                $data["blocks"][] = $this->_toJson($block);
            }

            $data["page_title"] = $option->getTabbarName();

            $this->_sendHtml($data);
        }

    }

}