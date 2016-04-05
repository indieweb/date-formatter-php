<?php
class BasicTest extends PHPUnit_Framework_TestCase {

  public function setUp() {
    date_default_timezone_set('UTC');
    IndieWeb\DateFormatter::$timezoneOffsetFormat = 'O';
  }

  private function _testEquals($expected, $start, $end=false) {
    $formatted = IndieWeb\DateFormatter::format($start, $end, false);
    $this->assertEquals($expected, $formatted);
  }

  private function _testTimeEquals($expected, $start, $end=false) {
    $formatted = IndieWeb\DateFormatter::formatTime($start, $end, false);
    $this->assertEquals($expected, $formatted);
  }

  public function testInvalidStartDate() {
    $this->_testEquals(null, 'invalid');
  }

  public function testInvalidEndDate() {
    $this->_testEquals(null, '2013-10-31T19:00:00-07:00', 'invalid');
  }

  public function testWithDateObjects() {
    $this->_testEquals('October 8, 2013 from 7:00am to 5:00pm (-0700)', new DateTime('2013-10-08T07:00:00-07:00'), new DateTime('2013-10-08T17:00:00-07:00'));
  }

  public function testStartDateAndTimeOnly() {
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
    $this->_testEquals('December 31, 2013 7:00am until January 1, 2014 5:00pm (-0700)', '2013-12-31T07:00:00-07:00', '2014-01-01T17:00:00-07:00');
  }

  public function testDifferentYearSameMonthSameDayDifferentTime() {
    $this->_testEquals('September 1, 2013 7:00am until September 1, 2014 5:00pm (-0700)', '2013-09-01T07:00:00-07:00', '2014-09-01T17:00:00-07:00');
  }

  public function testTimezoneWithNoColons() {
    $this->_testEquals('December 31, 2013 7:00am until January 1, 2014 5:00pm (-0700)', '2013-12-31T07:00:00-0700', '2014-01-01T17:00:00-0700');
  }

  public function testNoTimezone() {
    $this->_testEquals('October 8, 2013 from 7:00am to 5:00pm', '2013-10-08T07:00:00', '2013-10-08T17:00:00');
  }

  public function testDateFormatNoT() {
    $this->_testEquals('December 31, 2013 7:00am until January 1, 2014 5:00pm (-0700)', '2013-12-31 07:00:00-0700', '2014-01-01 17:00:00-0700');
  }

  public function testDateOnlyStartDateOnly() {
    $this->_testEquals('September 3, 2013', '2013-09-03');
  }

  public function testDateOnlySameDay() {
    $this->_testEquals('September 3, 2013', '2013-09-03', '2013-09-03');
  }

  public function testDateOnlySameYearSameMonthDifferentDay() {
    $this->_testEquals('September 3-8, 2013', '2013-09-03', '2013-09-08');
  }

  public function testDateOnlySameYearDifferentMonthDifferentDay() {
    $this->_testEquals('September 28 through October 3, 2013', '2013-09-28', '2013-10-03');
  }

  public function testDateOnlyDifferentYearDifferentMonthDifferentDay() {
    $this->_testEquals('December 30, 2013 through January 2, 2014', '2013-12-30', '2014-01-02');
  }

  // Tests when the start and end times are in a different timezone

  public function testTZDiffSameYearSameMonthSameDayDifferentTime() {
    $this->_testEquals('October 8, 2013 from 7:00am (-0400) to 5:00pm (-0700)', '2013-10-08T07:00:00-04:00', '2013-10-08T17:00:00-07:00');
  }

  public function testTZDiffSameYearSameMonthDifferentDayDifferentTime() {
    $this->_testEquals('October 8, 2013 at 7:00am (-0400) until Oct 10 at 5:00pm (-0700)', '2013-10-08T07:00:00-04:00', '2013-10-10T17:00:00-07:00');
  }

  public function testTZDiffSameYearDifferentMonthDifferentDayDifferentTime() {
    $this->_testEquals('August 31, 2013 7:00am (-0400) until September 1 at 5:00pm (-0700)', '2013-08-31T07:00:00-04:00', '2013-09-01T17:00:00-07:00');
  }

  public function testTZDiffDifferentYearDifferentMonthDifferentDayDifferentTime() {
    $this->_testEquals('December 31, 2013 7:00am (-0400) until January 1, 2014 5:00pm (-0700)', '2013-12-31T07:00:00-04:00', '2014-01-01T17:00:00-07:00');
  }

  public function testTZDiffDifferentYearSameMonthSameDayDifferentTime() {
    $this->_testEquals('September 1, 2013 7:00am (-0400) until September 1, 2014 5:00pm (-0700)', '2013-09-01T07:00:00-04:00', '2014-09-01T17:00:00-07:00');
  }

  // Tests for formatting only times

  public function testTimeStartOnlyNoTime() {
    $this->_testTimeEquals(null, '2015-01-01');
  }

  public function testTimeStartOnlyWithTimezone() {
    $this->_testTimeEquals('9:00am (-0800)', '2015-01-01T09:00:00-0800');
  }

  public function testTimeStartOnlyNoTimezone() {
    $this->_testTimeEquals('9:00am', '2015-01-01T09:00:00');
  }

  public function testTimeDifferentEndTime() {
    $this->_testTimeEquals('9:00am - 10:00am (-0800)', '2015-01-01T09:00:00-0800', '2015-01-01T10:00:00-0800');
  }

  public function testTimeDifferentEndTimeZone() {
    $this->_testTimeEquals('9:00am (-0700) - 12:00pm (-0800)', '2015-01-01T09:00:00-0700', '2015-01-01T12:00:00-0800');
  }

}
