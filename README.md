# Cron Timer

Provides a simple service to throttle the execution of tasks based on a time limit.

Useful if you want to enforce a sleep period between task runs regardless of how often cron is running.

## Example

```?php

function my_module_cron() {
  $cron_timer = \Drupal::service('cron_timer');
  // Ensure 1 hour (3600 seconds) has passed since the last run.
  if ($cron_timer->hasTimeSinceLastRunPassed('my_module_key', 3600) {
    // Run your task here.
    $cron_timer->markAsComplete('my_module_key');
  }
}
```
