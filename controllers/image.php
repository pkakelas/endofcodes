<?php
    class ImageController extends ControllerBase {
        public function create( $image ) {
            require_once 'models/image.php';
            require_once 'models/extentions.php';
            if ( !isset( $_SESSION[ 'user' ] ) ) {
                throw new HTTPUnauthorizedException();
            }
            $user = $_SESSION[ 'user' ];
            $userImage = new Image();
            $userImage->tmpName = $image[ 'tmpName' ];
            $userImage->name = $image[ 'name' ];
            $userImage->user = $user;
            $user->image = $userImage;
            try {
                $userImage->save();
                $user->save();
            }
            catch ( ModelValidationException $e ) {
                go( 'user', 'update', [ $e->error => true ] );
            }
            go( 'user', 'view', [ 'username' => $user->username ] );
        }
    }
?>
