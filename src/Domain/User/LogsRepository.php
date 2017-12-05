<?php
namespace Domain\User;

class LogsRepository
{
    private $logs_model;

    public function __construct() {
        $this->logs_model = new Log;
    }

    public function getLogsFromUserIdPaginated($user_id, $options) {
        return $this->logs_model
            ->where('user_id', $user_id)
            ->orderBy(Log::FIXED_ORDER, $options['order'])
            ->offset($options['offset'])
            ->limit($options['limit'])
            ->get();
    }
}