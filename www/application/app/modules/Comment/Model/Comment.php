<?php
class Comment_Model_Comment extends Core_Model_Default {

    protected $_answers;
    protected $_likes;

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->_db_table = 'Comment_Model_Db_Table_Comment';
        return $this;
    }

    public function findLast($value_id, $pos_id) {
        $row = $this->getTable()->findLast($value_id, $pos_id);
        if($row) {
            $this->setData($row->getData())
                ->setId($row->getId())
            ;
        }
        return $this;
    }

    public function findLastest($value_id) {
        return $comments = $this->getTable()->findLastest($value_id);
    }

    public function findAllWithPhoto($value_id) {
        return $comments = $this->getTable()->findAllWithPhoto($value_id);
    }

    public function findAllWithLocation($value_id) {
        return $comments = $this->getTable()->findAllWithLocation($value_id);
    }

    public function findAllWithLocationAndPhoto($value_id) {
        return $comments = $this->getTable()->findAllWithLocationAndPhoto($value_id);
    }

    public function pullMore($value_id, $start, $count) {
        return $comments = $this->getTable()->pullMore($value_id, $start, $count);
    }

    public function getImageUrl() {
        $image_path = Application_Model_Application::getImagePath().$this->getData('image');
        $base_image_path = Application_Model_Application::getBaseImagePath().$this->getData('image');
        if($this->getData('image') AND file_exists($base_image_path)) {
            return $image_path;
        }
        return null;
    }

    public function getAnswers() {
        if(!$this->getId()) return array();
        if(is_null($this->_answers)) {
            $answer = new Comment_Model_Answer();
            $answer->setStatus($this);
            $this->_answers = $answer->findByComment($this->getId(), true);
            foreach($this->_answers as $answer) {
                $answer->setComment($this);
            }
        }

        return $this->_answers;
    }

    public function getLikes() {
        if(!$this->getId()) return array();
        if(is_null($this->_likes)) {
            $like = new Comment_Model_Like();
            $this->_likes = $like->findByComment($this->getId());
            foreach($this->_likes as $like) {
                $like->setComment($this);
            }
        }

        return $this->_likes;
    }

}
