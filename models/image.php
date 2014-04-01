<?php
    require_once 'models/user.php';
    require_once 'models/extentions.php';

    class Image extends ActiveRecordBase {
        public $tmpName;
        public $name;
        public $target_path;
        public $ext;
        public $user;
        protected $userid;
        protected static $attributes = [ 'name', 'userid' ];
        protected static $tableName = 'images';

        public static function findByUser( $user ) {
            return new Image( $user->imageid );
        }

        public function __construct( $id = false ) {
            if ( $id ) {
                global $config;

                $this->exists = true;
                $imageInfo = dbSelectOne( 'images', [ '*' ], compact( "id" ) );
                $this->id = $id;
                $this->name = $imageInfo[ 'name' ];
                $this->ext = Extention::get( $this->name );
                $this->targetPath = $config[ 'paths' ][ 'avatarPath' ] . $id . '.' . $this->ext;
            }
        }

        protected function onBeforeSave() {
            $this->ext = Extention::get( $this->name );
            $this->userid = $this->user->id;
            $this->name = basename( $this->name );
            if ( !Extention::valid( $this->ext ) ) {
                throw new ModelValidationException( 'imageInvalid' );
            }
        }

        protected function onCreate() {
            global $config;

            $targetPath = $config[ 'paths' ][ 'avatarPath' ];
            $ext = $this->ext;
            $name = $this->id . "." . $ext;
            $this->targetPath = $targetPath . $name;
            $this->upload();
        }

        public function upload() {
            $tmpName = $this->tmpName;
            $targetPath = $this->targetPath;
            return move_uploaded_file( $tmpName, $targetPath );
        }
    }
?>
