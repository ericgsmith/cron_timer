<?php

namespace Drupal\cron_timer;

/**
 * Defines an interface for the cron timer system.
 */
interface CronTimerInterface {

  /**
   * Check if the sleep time has passed since the last time this was executed.
   *
   * @param string $task_id
   *   The identified of the task.
   * @param int $sleep_time
   *   The number of seconds that must pass before the task can run again.
   *
   * @return bool
   *   TRUE if the sleep time has passed since the last time this was executed.
   */
  public function hasTimeSinceLastRunPassed($task_id, $sleep_time);

  /**
   * Register the task as executed.
   *
   * @param string $task_id
   *   The identified of the task.
   */
  public function markAsComplete($task_id);

}
