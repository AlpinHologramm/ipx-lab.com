<?php

class Application_View_Customization_Features_Edit_Tabbareditor extends Core_View_Default {

    protected $_icon_width;
    protected $_icon_height;

    public function getIconWidth() {

        if(!$this->_icon_width) {
            $this->_calcIconSize();
        }

        return $this->_icon_width;

    }

    public function getIconHeight() {

        if(!$this->_icon_height) {
            $this->_calcIconSize();
        }

        return $this->_icon_height;

    }

    protected function _calcIconSize() {

        $width = 150;
        $height = 150;
        $application = $this->getApplication();
        $option = $this->getOptionValue();
        if(!$option->getFolderCategoryId() AND $application->getLayoutId() == 8) {
            $rect_blocks = array(1,7);
            $num = 7;
            for( $i=1; $i < 10; $i++ ) {
                $num+=7; $rect_blocks[]= $num;
                $num+=6; $rect_blocks[]= $num;
            }

            $cur_pos = $option->getPosition();
            $options = $application->getPages();

            foreach($options as $kk => $val) {
                if($val->getValueId() == $option->getId()) {
                    $cur_pos = $kk;
                    break;
                }
            }

            if(in_array($cur_pos, $rect_blocks)) {
                $width = 418;
                $height = 206;
            }

        }

        $this->_icon_width = $width;
        $this->_icon_height = $height;

    }

}
