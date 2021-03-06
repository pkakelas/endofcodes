<?php
    class Follow extends ActiveRecordBase {
        public $follower;
        public $followed;
        protected static $attributes = [ 'followerid', 'followedid' ];
        protected $followerid;
        protected $followedid;
        protected static $tableName = 'follows';

        public function __construct( $followerid = false, $followedid = false ) {
            if ( $followerid !== false && $followedid !== false ) {
                $this->follower = new User( $followerid );
                $this->followed = new User( $followedid );
                try {
                    $res = dbSelectOne(
                        'follows',
                        [ 'followerid', 'followedid' ],
                        compact( 'followerid', 'followedid' )
                    );
                }
                catch ( DBExceptionWrongCount $e ) {
                    throw new ModelNotFoundException();
                }
            }
        }

        protected function onBeforeCreate() {
            $this->followerid = $this->follower->id;
            $this->followedid = $this->followed->id;
        }

        public function delete() {
            $followerid = $this->follower->id;
            $followedid = $this->followed->id;
            dbDelete(
                'follows',
                compact( 'followerid', 'followedid' )
            );
        }
    }
?>
