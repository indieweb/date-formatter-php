<?php
class HTMLClassTest extends PHPUnit_Framework_TestCase {

  public function setUp() {
    date_default_timezone_set('UTC');
    IndieWeb\DateFormatter::$timezoneOffsetFormat = 'O';
  }

  private function _assertPropertyEquals($expected, $parsed, $property) {
    $item = $parsed['items'][0];
    $value = null;
    if(array_key_exists($property, $item['properties'])) {
      $value = $item['properties'][$property][0];
    }
    $this->assertEquals($expected, $value);
  }

  public function testDefaultClassNames() {
    $formatted = IndieWeb\DateFormatter::format('2013-10-08T07:00:00-04:00', '2013-10-08T17:00:00-07:00');
    $expected = 'October 8, 2013 from 7:00am (-0400) to 5:00pm (-0700)';
    $this->assertEquals($expected, strip_tags($formatted));
    $parsed = Mf2\parse('<div class="h-test">'.$formatted.'</div>');
    $this->_assertPropertyEquals('2013-10-08T07:00:00-04:00', $parsed, 'start');
  }

  public function testCustomClassNames() {
    $formatted = IndieWeb\DateFormatter::format('2013-10-08T07:00:00-04:00', '2013-10-08T17:00:00-07:00', 'dt-departure', 'dt-arrival');
    $expected = 'October 8, 2013 from 7:00am (-0400) to 5:00pm (-0700)';
    $this->assertEquals($expected, strip_tags($formatted));
    $parsed = Mf2\parse('<div class="h-test">'.$formatted.'</div>');
    $this->_assertPropertyEquals('2013-10-08T07:00:00-04:00', $parsed, 'departure');
    $this->_assertPropertyEquals('2013-10-08T17:00:00-07:00', $parsed, 'arrival');
  }

}
