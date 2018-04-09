<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends APIController
{
    public function backup()
    {
        $db_host = env('DB_HOST');
        $db_port = env('DB_PORT');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');

        $command = sprintf('mysqldump -h\'%s\' -P\'%s\' -u\'%s\' -p\'%s\' %s', $db_host, $db_port, $db_user, $db_pass, $db_name);
        dd($command);
    }
}
