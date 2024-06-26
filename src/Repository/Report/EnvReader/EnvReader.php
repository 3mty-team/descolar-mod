<?php

namespace App\Repository\Report\EnvReader;

use App\Repository\Report\EnvReader\Interfaces\IEnv;

class EnvReader
{
    private static ?IEnv $envManager = null;

    /**
     * Get the instance of the EnvManager
     *
     * @return IEnv The instance of the EnvManager
     */
    public static function getInstance(): IEnv
    {
        if (is_null(self::$envManager)) {
            self::$envManager = new EnvManager();
        }

        return self::$envManager;
    }
}