<?php
class BasicTest extends PHPUnit_Framework_TestCase {

  public function setUp() {
    date_default_timezone_set('UTC');
    DateFormatter::$timezoneOffsetFormat = 'O';
  }

  private function _testEquals($expected, $start, $end=false) {
    $formatted = DateFormatter::format($start, $end, false);
    $this->assertEquals($expected, $formatted);
  }

  public function testInvalidStartDate() {
    $this->_testEquals(null, 'invalid');
  }

  public function testStartDateOnly() {
    $this->_testEquals('October 31, 2013 at 7:10pm (-0700)', '2013-10-31T19:10:00-07:00');
  }

  public function testSameYearSameMonthSameDayDifferentTime() {
    $this->_testEquals('October 8, 2013 from 7:00am to 5:00pm (-0700)', '2013-10-08T07:00:00-07:00', '2013-10-08T17:00:00-07:00');
  }

  public function testSameYearSameMonthDifferentDayDifferentTime() {
    $this->_testEquals('October 8, 2013 at 7:00am until Oct 10 at 5:00pm (-0700)', '2013-10-08T07:00:00-07:00', '2013-10-10T17:00:00-07:00');
  }

  public function testSameYearDifferentMonthDifferentDayDifferentTime() {
    $this->_testEquals('August 31, 2013 7:00am until September 1 at 5:00pm (-0700)', '2013-08-31T07:00:00-07:00', '2013-09-01T17:00:00-07:00');
  }

  public function testDifferentYearDifferentMonthDifferentDayDifferentTime() {
    $this->_testEquals('December 31, 2013 7:00am until January 1 at 5:00pm (-0700)', '2013-12-31T07:00:00-07:00', '2013-01-01T17:00:00-07:00');
  }

  public function testDifferentYearSameMonthSameDayDifferentTime() {
    $this->_testEquals('September 1, 2013 7:00am until September 1, 2014 5:00pm (-0700)', '2013-09-01T07:00:00-07:00', '2014-09-01T17:00:00-07:00');
  }
  
}
