<?php
class DateFormatter {
  public static $timezoneOffsetFormat = 'O';  // -0700
  // public static $timezoneOffsetFormat = 'P';  // -07:00

  public static function format($startISO, $endISO=false, $html=true) {
    ob_start();

    $start = DateTime::createFromFormat('Y-m-d\TH:i:sT', $startISO);
    if($endISO)
      $end = DateTime::createFromFormat('Y-m-d\TH:i:sT', $endISO);

    if($start === false || ($endISO && $end === false)) {
      return null;
    }

    if($endISO) {
      if($start->format('Y') != $end->format('Y')) {
        self::_renderDifferentYear($start, $end, $startISO, $endISO, $html);
      } else {
        if($start->format('F') == $end->format('F')) { 
          // Same month
          if($start->format('j') == $end->format('j')) {
            // Same month and day
            self::_renderSameYearSameMonthSameDay($start, $end, $startISO, $endISO, $html);
          } else {
            // Same month, different day
            self::_renderSameYearSameMonthDifferentDay($start, $end, $startISO, $endISO, $html);
          }
        } else {
          // Different month
          self::_renderSameYearDifferentMonthDifferentDay($start, $end, $startISO, $endISO, $html);
        }
      }
    } else {
      if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
      echo $start->format('F j, Y \a\t g:ia (O)');
      if($html) echo '</time>';
    }
    return ob_get_clean();
  }

  private static function _renderDifferentYear(DateTime $start, DateTime $end, $startISO, $endISO, $html) {
    // Different year
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y g:ia');
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j, Y g:ia (O)');
    if($html) echo '</time>';
  }

  private static function _renderSameYearSameMonthSameDay(DateTime $start, DateTime $end, $startISO, $endISO, $html) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    echo ' from ';
    echo $start->format('g:ia');
    if($html) echo '</time>';
    echo ' to ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('g:ia (O)');
    if($html) echo '</time>';
  }

  private static function _renderSameYearSameMonthDifferentDay(DateTime $start, DateTime $end, $startISO, $endISO, $html) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y');
    echo ' at ';
    echo $start->format('g:ia');
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('M j \a\t g:ia (O)');
    if($html) echo '</time>';
  }

  private static function _renderSameYearDifferentMonthDifferentDay(DateTime $start, DateTime $end, $startISO, $endISO, $html) {
    if($html) echo '<time class="dt-start" datetime="' . $startISO . '">';
    echo $start->format('F j, Y g:ia');
    if($html) echo '</time>';
    echo ' until ';
    if($html) echo '<time class="dt-end" datetime="' . $endISO . '">';
    echo $end->format('F j \a\t g:ia (O)');
    if($html) echo '</time>';
  }

}
