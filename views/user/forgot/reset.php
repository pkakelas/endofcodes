<?php
    include 'views/header.php';
?> 
<p>Please enter a new password</p>
<div>
<?php 
    $form = new Form( 'forgotpasswordrequest', 'update' );  
    $form->output( function( $self ) use ( $passwordEmpty, $passwordInvalid, $passwordNotMatched, $passwordToken ) {
        if ( $passwordEmpty ) {
            $self->createError( "Please enter a new password" );
        }
        if ( $passwordInvalid ) {
            $self->createError( "Your new password must be more than 6 characters long" );
        }
        if ( $passwordNotMatched ) {
            $self->createError( "Your two passwords do not match" );
        }
        $self->createLabel( 'password', 'Password' );
        $self->createInput( 'password', 'password' );
        $self->createLabel( 'passwordRepeat', 'Password (repeat)' );
        $self->createInput( 'password', 'passwordRepeat' );
        $self->createInput( 'submit', '', '', 'Change password' );
        $self->createInput( 'hidden', 'passwordToken', '', $passwordToken );
    } );
?>
</div><?
    include 'views/footer.php';
?>
