<?php

class Media_Mobile_Gallery_Image_ViewController extends Application_Controller_Mobile_Default {

    public function findAction() {

        if ($image_id = $this->getRequest()->getParam("image_id")) {

            try {

                $offset = $this->getRequest()->getParam('offset', 0);
                $data = array("images" => array());

                $image = new Media_Model_Gallery_Image();
                $image->find($image_id);

                if (!$image->getId() OR $image->getValueId() != $this->getCurrentOptionValue()->getId()) {
                    throw new Exception($this->_('An error occurred while loading pictures. Please try later.'));
                }

                $images = $image->setOffset($offset)->getImages();

                foreach ($images as $key => $link) {
                    $key+=$offset;
                    $data["images"][] = array(
                        "offset" => $link->getOffset(),
                        "image_id" => $key,
                        "is_visible" => false,
                        "url" => $link->getImage(),
                        "title" => $link->getTitle(),
                        "description" => $link->getDescription(),
                        "author" => $link->getAuthor()
                    );
                }

            } catch (Exception $e) {
                $data = array('error' => 1, 'message' => $e->getMessage());
            }

            $this->_sendHtml($data);
        }

    }

}
