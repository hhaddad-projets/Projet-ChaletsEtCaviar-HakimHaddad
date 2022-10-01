<?php
if (!class_exists('wdDebugData')) {
    class wdDebugData {

        private $storage_key;

        private $storage;

        function __construct($storage_key) {
            $this->storage_key = $storage_key;
            $this->storage = get_option($storage_key, array());
        }

        function pushData($data, $key, $force_override = true, $force_save = false, $singular = true, $limit = 10) {
            if ($singular) {
                if ($force_override)
                    $this->storage[$key] = $data;
                else if ( !isset($this->storage[$key]) )
                    $this->storage[$key] = $data;
            } else {
                $this->storage[$key][] = $data;
                $this->storage[$key] = array_slice($this->storage[$key], (-1) * $limit);
            }

            if ($force_save)
                $this->save();
        }

        function getData($key, $default = null) {
            if ( isset($this->storage[$key]) )
                return $this->storage[$key];

            return $default;
        }

        function save() {
            return update_option($this->storage_key, $this->storage);
        }

        function destroy() {
            $this->storage = array();
            delete_option( $this->storage_key );
        }

        function getSerializedStorage() {
            return base64_encode( serialize( $this-> storage ) );
        }

        function getStorage() {
            return $this->storage;
        }
    }
}