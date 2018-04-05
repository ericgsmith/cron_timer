<?php

namespace Drupal\cron_timer;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\State\StateInterface;

/**
 * Provides the cron timer system to throttle tasks.
 */
class CronTimer implements CronTimerInterface {

  // Prefix for the state value.
  const STATE_PREFIX = 'cron_timer.';

  /**
   * State service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs a cron timer object.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service to use.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service to use.
   */
  public function __construct(StateInterface $state, TimeInterface $time) {
    $this->state = $state;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public function hasTimeSinceLastRunPassed($task_id, $sleep_time) {
    $last_execution = $this->getTimeLastExecuted($task_id);
    return $last_execution + $sleep_time < $this->getTime()->getCurrentTime();
  }

  /**
   * {@inheritdoc}
   */
  public function markAsComplete($task_id) {
    $this->getState()->set($this->getStateKey($task_id), $this->getTime()->getCurrentTime());
  }

  /**
   * Get the time in seconds that the task was last executed.
   *
   * @param string $task_id
   *   The identified of the task.
   *
   * @return int
   *   The time in seconds when the task was last run.
   */
  protected function getTimeLastExecuted($task_id) {
    return (int) $this->getState()->get($this->getStateKey($task_id)) ?: 0;
  }

  /**
   * Get the key to use in the state service for a task.
   *
   * @param string $task_id
   *   The identified of the task.
   *
   * @return string
   *   The key to use for the state service.
   */
  protected function getStateKey($task_id) {
    return self::STATE_PREFIX . $task_id;
  }

  /**
   * Get the state service.
   *
   * @return \Drupal\Core\State\StateInterface
   *   The state service.
   */
  protected function getState() {
    return $this->state;
  }

  /**
   * Get the time service.
   *
   * @return \Drupal\Component\Datetime\TimeInterface
   *   The time service.
   */
  protected function getTime() {
    return $this->time;
  }

}
