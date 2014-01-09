<?php
    abstract class ControllerBase {
        protected function protectFromForgery( $token, $http_request_method ) {
            if ( $http_request_method === 'POST'
            && ( $token !== $_SESSION[ 'form' ][ 'token' ] || $token == '' ) ) { 
                throw new HTTPUnauthorizedException();
            }
        }
        protected function getControllerMethod( $requested_method, $http_request_method ) {
            $method = $requested_method; 

            $methods = array( 
                'create' => 1,
                'listing' => 0,
                'delete' => 1,
                'update' => 1,
                'view' => 0
            );
            if ( !isset( $methods[ $method ] ) ) {
                $method = 'view';
            }
            if ( $methods[ $method ] == 1 && $http_request_method != 'POST' ) {
                $method .= 'View';
            }

            return $method;
        }
        protected function getControllerVars( $get, $post, $files, $http_request_method ) {
            switch ( $http_request_method ) {
                case 'POST':
                    $vars = array_merge( $post, $files );
                    break;
                case 'GET':
                    $vars = $get;
                    break;
                default:
                    $vars = array(); 
                    break;
            }

            return $vars;
        }
        protected function callWithNamedArgs( $method_reflection, $callable, $vars ) {
            $parameters = $method_reflection->getParameters();
            $arguments = array();

            foreach ( $parameters as $parameter ) {
                if ( isset( $vars[ $parameter->name ] ) ) {
                    $arguments[] = $vars[ $parameter->name ];
                }
                else {
                    try {
                        $arguments[] = $parameter->getDefaultValue();
                    }
                    catch ( ReflectionException $e ) {
                        $arguments[] = null;
                    }
                }
            }
            call_user_func_array( $callable, $arguments );
        }
        public function dispatch( $get, $post, $files, $http_request_method ) {
            if ( !isset( $get[ 'method' ] ) ) {
                $get[ 'method' ] = '';
            }
            $method = $this->getControllerMethod( $get[ 'method' ], $http_request_method );
            $vars = $this->getControllerVars( $get, $post, $files, $http_request_method );
            if ( !isset( $vars[ 'token' ] ) ) {
                $token = '';
            }
            else {
                $token = $vars[ 'token' ];
            }
            $this->protectFromForgery( $token, $http_request_method );
            $this_reflection = new ReflectionObject( $this );
            $method_reflection = $this_reflection->getMethod( $method );

            $this->callWithNamedArgs( $method_reflection, array( $this, $method ), $vars );
        }
    }
?>