<?php
namespace IndieWeb;

use DateTime;

class DateFormatter {
  public static $timezoneOffsetFormat = 'O';  // -0700
  // public static $timezoneOffsetFormat = 'P';  // -07:00

  private static $_regexDateTimeTimezone = '/([0-9]{4}-[0-9]{2}-[0-9]{2})[T ]([0-9]{2}:[0-9]{2}:[0-9]{2})(?:([-+][0-9]{2}):?([0-9]{2}))?/';
  private static $_regexDate = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';

  public static function format($startISO, $endISO=false, $html=true) {

    if(is_object($startISO) && get_class($startISO) == 'DateTime') {
      $startISO = $startISO->format('c');
    }

    if(is_object($endISO) && get_class($endISO) == 'DateTime') {
      $endISO = $endISO->format('c');
    }

    $includeStartTimezone = false;

    if(preg_match(self::$_regexDateTimeTimezone, $startISO, $ms)) {
      if(array_key_exists(3, $ms)) {
        $startISO = $ms[1].'T'.$ms[2].$ms[3].':'.$ms[4];
        $start = DateTime::createFromFormat('Y-m-d\TH:i:sT', $startISO);
        $includeEndTimezone = true; // If the start date includes a timezone, show the timezone (on the end date)
      } else {
        $startISO = $ms[1].'T'.$ms[2];
        $start = DateTime::createFromFormat('Y-m-d\TH:i:s', $startISO);
        $includeEndTimezone = false;
      }

      $end = false;
      if($endISO) {
        if(preg_match(self::$_regexDateTimeTimezone, $endISO, $me)) {
          if(array_key_exists(3, $me)) {
            $endISO = $me[1].'T'.$me[2].$me[3].':'.$me[4];
            $end = DateTime::createFromFormat('Y-m-d\TH:i:sT', $endISO);
            $includeEndTimezone = true;
            if($start->format('T') != $end->format('T'))
              $includeStartTimezone = true; // Show the start timezone if it's different from the end timezone
          } else {
            $endISO = $me[1].'T'.$me[2];
            $end = DateTime::createFromFormat('Y-m-d\TH:i:s', $endISO);
          }
        }
      }

      // Return null if the start date could not be parsed, or if an end date was specified but could not be parsed
      if($start === false || ($endISO && $end === false)) {
        return null;
      }

      ob_start();
      if($endISO) {
        if($start->format('Y') != $end->format('Y')) {
          // Different year
          self::_renderDifferentYearWithTime($start, $end, $startISO, $endISO, $html, $includeStartTimezone, $includeEndTimezone);
        } else {
          if($start->format('F') == $end->format('F')) {
            // Same month
            if($start->format('j') == $end->format('j')) {
              // Same month and day
              self::_renderSameYearSameMonthSameDayWithTime($start, $end, $startISO, $endISO, $html, $includeStartTimezone, $includeEndTimezone);
            } else {
              // Same month, different day
              self::_renderSameYearSameMonthDifferentDayWithTime($start, $end, $startISO, $endISO, $html, $includeStartTimezone, $includeEndTimezone);
            }
          } else {
            // Different month
            self::_renderSameYearDifferentMonthDifferentDayWithTime($start, $end, $startISO, $endISO, $html, $includeStartTimezone, $includeEndTimezone);
          }
        }
      } else {
        self::_renderStartOnlyWithTime($start, $startISO, $html, $includeStartTimezone, $includeEndTimezone);
      }
      return ob_get_clean();

    } elseif(preg_match(self::$_regexDate, $startISO)) {
      $start = DateTime::createFromFormat('Y-m-d', $startISO);

      $end = false;
      if($endISO) {
        if(preg_match(self::$_regexDate, $endISO)) {
          $end = DateTime::createFromFormat('Y-m-d', $endISO);
        }
      }

      // Return null if the start date could not be parsed, or if an end date was specified but could not be parsed
      if($start === false || ($endISO && $end === false)) {
        return null;
      }

      ob_start();
      if($endISO) {
        if($start->format('Y') != $end->format('Y')) {
          // Different year
          self::_renderDifferentYear($start, $end, $startISO, $endISO, $html);
        } else {
          if($start->format('F') != $end->format('F')) {
            // Different month
            self::_renderDifferentMonth($start, $end, $startISO, $endISO, $html);
          } else {
            // Same month
            self::_renderDifferentDay($start, $end, $startISO, $endISO, $html);
          }
        }
      } else {
        self::_renderStartOnly($start, $startISO, $html);
      }
      return ob_get_clean();

    }

    return null;
  }

  private static function _renderStartOnlyWithTime(DateTime $start, $startISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y \a\t g:ia' . ($includeEndTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
  }

  private static function _renderDifferentYearWithTime(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y g:ia' . ($includeStartTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j, Y g:ia' . ($includeEndTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
  }

  private static function _renderSameYearSameMonthSameDayWithTime(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    echo ' from ';
    echo $start->format('g:ia' . ($includeStartTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
    echo ' to ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('g:ia' . ($includeEndTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
  }

  private static function _renderSameYearSameMonthDifferentDayWithTime(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    echo ' at ';
    echo $start->format('g:ia' . ($includeStartTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('M j \a\t g:ia' . ($includeEndTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
  }

  private static function _renderSameYearDifferentMonthDifferentDayWithTime(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y g:ia' . ($includeStartTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j \a\t g:ia' . ($includeEndTimezone ? ' (O)' : ''));
    if($html) echo '</time>';
  }

  private static function _renderStartOnly(DateTime $start, $startISO, $html, $includeStartTimezone=false, $includeEndTimezone=false) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    if($html) echo '</time>';
  }

  private static function _renderDifferentDay(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j');
    if($html) echo '</time>';
    echo '-';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('j, Y');
    if($html) echo '</time>';
  }

  private static function _renderDifferentMonth(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j');
    if($html) echo '</time>';
    echo ' through ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j, Y');
    if($html) echo '</time>';
  }

  private static function _renderDifferentYear(DateTime $start, DateTime $end, $startISO, $endISO, $html, $includeStartTimezone=false, $includeEndTimezone=true) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    if($html) echo '</time>';
    echo ' through ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j, Y');
    if($html) echo '</time>';
  }


}
