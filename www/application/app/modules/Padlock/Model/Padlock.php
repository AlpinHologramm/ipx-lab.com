<?php
class Padlock_Model_Padlock extends Core_Model_Default {

    protected $_value_ids = null;

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->_db_table = 'Padlock_Model_Db_Table_Padlock';
        return $this;
    }

    public function copyTo($option) {

        $this->setId(null)
            ->setValueId($option->getId())
            ->save()
        ;

        return $this;
    }

    public function prepareFeature($option_value) {
        $this->setValueId($option_value->getId())->save();
        parent::prepareFeature($option_value);
    }

    public function save() {

        parent::save();

        if($this->getAppId() AND is_array($this->_value_ids)) {
            $this->saveValueIds($this->getAppId());
        }
    }

    public function delete() {
        $this->__tryToUnlockEverything();
        return parent::delete();
    }

    public function saveValueIds($app_id) {
        $this->getTable()->saveValueIds($app_id, $this->_value_ids);
        return $this;
    }

    public function getValueIds($app_id = null) {

        if(!$this->_value_ids) {

            $this->_value_ids = array();

            if(is_null($app_id) AND $this->getAppId()) {
                $app_id = $this->getAppId();
            }

            if(!is_null($app_id)) {
                $this->_value_ids = $this->getTable()->findValueIds($app_id);
            }

        }

        return $this->_value_ids;
    }

    public function setValueIds($value_ids) {
        $this->_value_ids = $value_ids;
        return $this;
    }

    private function __tryToUnlockEverything() {

        $current_option = new Application_Model_Option_Value();
        $current_option->find($this->getValueId());
        if($current_option->getId() AND $current_option->getApplication()) {

            $application = $current_option->getApplication();
            $has_another_padlock = false;
            foreach($application->getOptions() as $option) {
                if($option->getCode() == $current_option->getCode() AND $option->getId() != $current_option->getId() AND $option->getData("is_active")) {
                    Zend_Debug::dump($current_option->getData());
                    Zend_Debug::dump($option->getData());
                    $has_another_padlock = true;
                }
            }

            if(!$has_another_padlock) {
                $this->setValueIds(array());
                $application->setRequireToBeLoggedIn(0)->save();
                $this->saveValueIds($application->getId());
            }

        }

    }

}
