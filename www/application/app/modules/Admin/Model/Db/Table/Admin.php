<?php

class Admin_Model_Db_Table_Admin extends Core_Model_Db_Table {

    protected $_name = "admin";
    protected $_primary = "admin_id";

    public function getStats() {


        $date = new Siberian_Date();
        $date->addDay(1);
        $endDate= $date->toString("yyyy-MM-dd HH:mm:ss");
        $date->setDay(1);
        $startDate = $date->toString("yyyy-MM-dd HH:mm:ss");

        //select MONTH(`created_at`), count(*) as count from admin group by MONTH(`created_at`)

        $select = $this->select()
            ->from($this->_name, array("count" => new Zend_Db_Expr("COUNT(*)"), "day"=> new Zend_Db_Expr("DATE(created_at)")))
            ->where("created_at <= ?", $endDate)
            ->where("created_at > ?", $startDate)
            ->order("created_at")
            ->group("created_at")
        ;



        return $this->fetchAll($select);

    }

    public function isAllowedToAddPages($admin_id, $app_id) {

        $select = $this->_db->select()
            ->from("application_admin", array("is_allowed_to_add_pages"))
            ->where("app_id = ?", $app_id)
            ->where("admin_id = ?", $admin_id);
        ;

        return $this->_db->fetchOne($select);

    }

}