<?php

class Media_Model_Gallery_Music extends Core_Model_Default {

    protected $_key;

    protected $_tracks = array();
    protected $_albums = array();

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->_db_table = 'Media_Model_Db_Table_Gallery_Music';
        $this->_key = Api_Model_Key::findKeysFor('soundcloud');
        return $this;
    }

    public function getAllAlbums() {
        if(!$this->_albums) {
            $elements = new Media_Model_Gallery_Music_Elements();
            $elements = $elements->findAll(array('gallery_id' => $this->getId()), 'position ASC');
            foreach($elements as $element) {
                if($element->getAlbumId()) {
                    $album = new Media_Model_Gallery_Music_Album();
                    $album->find($element->getAlbumId());
                    $this->_albums[] = $album;
                }
            }
        }
        return $this->_albums;
    }

    public function getAllTracks($without_albums) {
        if(!$this->_tracks) {
            $elements = new Media_Model_Gallery_Music_Elements();
            $elements = $elements->findAll(array('gallery_id' => $this->getId()), 'position ASC');
            foreach($elements as $element) {
                if($element->getAlbumId()) {
                    $album = new Media_Model_Gallery_Music_Album();
                    $album->find($element->getAlbumId());
                    if($album->getType() != 'podcast') {
                        $tracks = new Media_Model_Gallery_Music_Track();
                        $tracks = $tracks->findAll(array('album_id' => $element->getAlbumId()), 'position ASC');
                        foreach($tracks as $track) {
                            $this->_tracks[] = $track;
                        }
                    } else {
                        $podcast = new Media_Model_Gallery_Music_Type_Podcast();
                        $data = $podcast->setFeedUrl($album->getPodcastUrl())->parse();
                        foreach($data["items"] as $item) {
                            $track = new Media_Model_Gallery_Music_Track();
                            $track->setName($item["title"])
                                    ->setDuration($item["duration"])
                                    ->setStreamUrl($item["stream_url"])
                                    ->setType('podcast');
                            $this->_tracks[] = $track;
                        }
                    }
                } else {
                    if($without_albums == true) {
                        $track = new Media_Model_Gallery_Music_Track();
                        $track->find($element->getTrackId());
                        $this->_tracks[] = $track;
                    }
                }
            }
        }

        return $this->_tracks;
    }

    public function getMosaicArtworkUrl() {
        $artwork_url = $this->getData('artwork_url');
        $artwork_urls = array();
        if($artwork_url) {
            $artwork_urls[] = Application_Model_Application::getImagePath().$artwork_url;
        } else {
            $elements = new Media_Model_Gallery_Music_Elements();
            $elements = $elements->findAll(array('gallery_id' => $this->getId()), 'position ASC');
            if($elements->count() > 0) {
                $i = 0;
                foreach($elements as $element) :
                    if($element->getAlbumId()) :
                        $objet = new Media_Model_Gallery_Music_Album();
                        $objet->find($element->getAlbumId());
                    endif;
                    if($element->getTrackId()) :
                        $objet = new Media_Model_Gallery_Music_Track();
                        $objet->find($element->getTrackId());
                    endif;
                    if($i < 4 && $objet->getArtworkUrl()) :
                        $artwork_urls[] = $objet->getArtworkUrl();
                        $i++;
                    endif;
                endforeach;
                while(count($artwork_urls) < 4) {
                    $objet = new Media_Model_Gallery_Music_Album();
                    $artwork_urls[] = $objet->getArtworkUrl();
                }
            } else {
                $album = new Media_Model_Gallery_Music_Album();
                $artwork_album = $album->getArtworkUrl();
                for($i = 0; $i < 4; $i++) {
                    $artwork_urls[] = $artwork_album;
                }
            }
        }
        return $artwork_urls;
    }

    public function getTotalTracks() {
        $total_tracks = $this->getAllTracks(true);
        return count($total_tracks);
    }

    public function getTotalDuration() {
        $total_tracks = $this->getAllTracks(true);
        $total_duration = 0;
        foreach($total_tracks as $track) {
            $total_duration += $track->getDuration();
        }

        $total_duration = floor($total_duration / 1000);
        $seconds = $total_duration % 60;
        $total_duration = floor($total_duration / 60);

        $minutes = $total_duration % 60;
        $total_duration = floor($total_duration / 60);

        $hours = $total_duration % 60;
        $total_duration = floor($total_duration / 60);

        $days = $total_duration % 24;
        $total_duration = floor($total_duration / 24);

        $return = '';

        if($days >= 1) {
            $return = $days.' '.$days == 1 ? $days.' '.$this->_('day') : $days.' '.$this->_('days');
        } elseif($hours >= 1) {
            $return = $hours.' '.$hours == 1 ? $hours.' '.$this->_('hour') : $hours.' '.$this->_('hours');
        } elseif($minutes >= 1) {
            $return = $minutes.' '.$minutes == 1 ? $minutes.' '.$this->_('minute') : $minutes.' '.$this->_('minutes');
        } else {
            $return = $seconds.' '.$seconds == 1 ? $seconds.' '.$this->_('second') : $seconds.' '.$this->_('seconds');
        }

        return $return;
    }

    public function getSoundcloudId() {
        return $this->_key->getClientId();
    }

    public function getSoundcloudSecret() {
        return $this->_key->getSecretId();
    }

    public function getNextElementsPosition() {
        $lastPosition = $this->getTable()->getLastElementsPosition();
        if(!$lastPosition) $lastPosition = 0;
        return ++$lastPosition;
    }

}
