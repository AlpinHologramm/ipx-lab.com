<?php

class Comment_Mobile_ViewController extends Application_Controller_Mobile_Default {


    public function indexAction() {
        $this->forward('index', 'index', 'Front', $this->getRequest()->getParams());
    }

    public function templateAction() {
        $this->loadPartials($this->getFullActionName('_').'_l'.$this->_layout_id, false);
    }

    public function findAction() {

        if($value_id = $this->getRequest()->getParam('value_id') AND $comment_id = $this->getRequest()->getParam('news_id')) {
            $application = $this->getApplication();
            $comment = new Comment_Model_Comment();
            $comment->find($comment_id);

            if($comment->getId() AND $comment->getValueId() == $value_id) {

                $color = $application->getBlock('background')->getColor();

                $data = array(
                    "id" => $comment->getId(),
                    "author" => $application->getName(),
                    "message" => $comment->getText(),
                    "picture" => $comment->getImageUrl(),
                    "icon" => $application->getIcon(74),
                    "can_comment" => true,
                    "created_at" => $this->_durationSince($comment->getCreatedAt()),
                    "number_of_likes" => count($comment->getLikes()),
                    "flag_icon" => $this->_getColorizedImage($this->_getImage("pictos/flag.png"), $color),
                    "code" => $this->getCurrentOptionValue()->getCode()
                    );

                $this->_sendHtml($data);
            }

        }

    }

    public function addlikeAction() {

        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {

            try {

                $customer_id = $this->getSession()->getCustomerId();
                $ip = md5($_SERVER['REMOTE_ADDR']);
                $ua = md5($_SERVER['HTTP_USER_AGENT']);
                $like = new Comment_Model_Like();

                if(!$like->findByIp($data['news_id'], $customer_id, $ip, $ua)) {

                    $like->setCommentId($data['news_id'])
                    ->setCustomerId($customer_id)
                    ->setCustomerIp($ip)
                    ->setAdminAgent($ua)
                    ;

                    $like->save();

                    $message = $this->_('Your like has been successfully added');
                    $html = array('success' => 1, 'message' => $message);

                } else {
                    throw new Exception($this->_("You can't like more than once the same news"));
                }

            }
            catch(Exception $e) {
                $html = array('error' => 1, 'message' => $e->getMessage());
            }

            $this->_sendHtml($html);
        }

    }

    public function flagpostAction() {

        if($value_id = $this->getRequest()->getParam('value_id') AND $comment_id = $this->getRequest()->getParam('news_id')) {
            $application = $this->getApplication();
            $comment = new Comment_Model_Comment();
            $comment->find($comment_id);

            if($comment->getId() AND $comment->getValueId() == $value_id) {

                $comment->setFlag($comment->getFlag() + 1);
                $comment->save();

                $message = $this->_('Your flag has successfully been notified');
                $html = array('success' => 1, 'message' => $message);

                $this->_sendHtml($html);
            }
        }
    }

    public function flagcommentAction() {

        if($value_id = $this->getRequest()->getParam('value_id') AND $answer_id = $this->getRequest()->getParam('answer_id')) {
            $application = $this->getApplication();
            $answer = new Comment_Model_Answer();
            $answer->find($answer_id);

            $comment = new Comment_Model_Comment();
            $comment->find($answer->getCommentId());

            if($answer->getId() AND $comment->getValueId() == $value_id) {

                $answer->setFlag($answer->getFlag() + 1);
                $answer->save();

                $message = $this->_('Your flag has successfully been notified');
                $html = array('success' => 1, 'message' => $message);

                $this->_sendHtml($html);
            }
        }
    }


}