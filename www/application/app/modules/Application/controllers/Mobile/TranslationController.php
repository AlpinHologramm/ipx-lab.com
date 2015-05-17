<?php

class Application_Mobile_TranslationController extends Application_Controller_Mobile_Default {

    public function findallAction() {

        $data = array();

        if(Core_Model_Language::getCurrentLanguage() != Core_Model_Language::DEFAULT_LANGUAGE) {

            Core_Model_Translator::addModule("mcommerce");
            Core_Model_Translator::addModule("comment");

            $translations = array(
                "OK",
                "Website",
                "Phone",
                "Locate",
                "Contact successfully added to your address book",
                "Unable to add the contact to your address book",
                "You must give the permission to the app to add a contact to your address book",
                "You already have this user in your contact",
                "The address you're looking for does not exists.",
                // Map
                "An unexpected error occurred while calculating the route.",
                // Mcommerce
                "Cart",
                "Proceed",
                "Next",
                "Payment",
                "Delivery",
                "My information",
                "Review",
                "Validate",
                "The payment has been cancelled, something wrong happened? Feel free to contact us.",
                // Places
                "Map",
                "Invalid place",
                "Unable to calculate the route.",
                "No address to display on map.",
                "You must share your location to access this page.",
                // Comment
                "No place to display on map.",
                "An error occurred while loading places.",

                // General
                "This section is unlocked for mobile users only",
                "You are gone offline",


            );

            foreach($translations as $translation) {
                $data[$translation] = $this->_($translation);
            }

        }

        $this->_sendHtml($data);
    }

}
