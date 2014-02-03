<?php
    include_once 'models/country.php';
    
    class CountryTest extends UnitTest {
        protected $countries = array(
            'GR' => 'Greece',
            'UK' => 'England',
            'RU' => 'Russia'
        );
        
        protected function insertCountries() {
            $countries = $this->countries;
            $shortnames = array_keys( $countries );
            $names = array_values( $countries );
            foreach ( $countries as $shortname => $name ) {
                $country = new Country();
                $country->shortname = $shortname;
                $country->name = $name;
                $country->save();
            }
            return false;
        }
        public function testFindAll() {
            $inserted = true;
            $this->insertCountries();
            $countriesArray = Country::findAll(); 
            $arrayExists = is_array( $countriesArray );
            $success = count( $countriesArray ) > 3;
            $this->assertTrue( $arrayExists, '$Country::findall() did not return an array' );
            $this->assertTrue( $success, '$Country::findall() did not return the as much countries as it should' );
        } 
        public function testCountryConstruct() {
            $countries = $this->countries;
            $testId = 1;
            $shortnames = array_keys( $countries );
            $shortname = $shortnames[ 0 ];
            $this->insertCountries();
            $country = new Country( $testId );
            $this->assertEquals ( $testId, $country->id, "'new country()' did not return the appropriate country" );
            $this->assertEquals ( $country->shortname, $shortname, "'new country()' did not return the appropriate shortname" );
            $this->assertEquals ( $country->name, $countries[ $shortname ], "'new country()' did not return the appropriate name" );
        }
    }

    return new CountryTest();
?>
