<?php

namespace Drupal\Tests\cron_timer\Unit;

use Drupal\cron_timer\CronTimer;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\cron_timer\CronTimer
 * @group cron_timer
 */
class CronTimerTest extends UnitTestCase {

  /**
   * @var \Drupal\cron_timer\CronTimer
   */
  protected $cronTimer;

  /**
   * @var \Drupal\Core\State\StateInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $state;

  /**
   * @var \Drupal\Component\Datetime\TimeInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $timer;

  /**
   * The current time.
   *
   * @var int
   */
  protected $time;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->time = time();
    $this->timer = $this->getMock('\Drupal\Component\Datetime\TimeInterface');
    $this->timer
      ->expects($this->any())
      ->method('getCurrentTime')
      ->willReturn($this->time);

    $this->state = $this->getMock('\Drupal\Core\State\StateInterface');

    $this->cronTimer = new CronTimer($this->state, $this->timer);
  }

  /**
   * Test that the time has passed since the last execution.
   */
  public function testTimeHasPassed() {
    $last_run = $this->time - 600;

    $this->state
      ->expects($this->any())
      ->method('get')
      ->with('cron_timer.foo.bar')
      ->willReturn($last_run);

    $has_passed = $this->cronTimer->hasTimeSinceLastRunPassed('foo.bar', 500);
    $this->assertTrue($has_passed);
  }

  /**
   * Test that the time has not passed since the last execution.
   */
  public function testTimeHasNotPassed() {
    $last_run = $this->time - 400;

    $this->state
      ->expects($this->any())
      ->method('get')
      ->with('cron_timer.foo.baz')
      ->willReturn($last_run);

    $has_passed = $this->cronTimer->hasTimeSinceLastRunPassed('foo.baz', 500);
    $this->assertFalse($has_passed);
  }

  /**
   * Test that the task is marked as complete.
   */
  public function testMarkAsComplete() {
    $this->state
      ->expects($this->once())
      ->method('set')
      ->with('cron_timer.foo.zip', $this->time);
    $this->cronTimer->markAsComplete('foo.zip');
  }

}
