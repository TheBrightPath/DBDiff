<?php namespace DBDiff;

use DBDiff\Params\ParamsFactory;
use DBDiff\DB\DiffCalculator;
use DBDiff\Exceptions\BaseException;


class DBDiff {
    
    public function run() {

        // Increase memory limit
        ini_set('memory_limit', '512M');

        try {
            /** @var \DBDiff\Params\DefaultParams $params */
            $params = ParamsFactory::get();

            // Diff
            $diffCalculator = new DiffCalculator(null, $params);
            $diff = $diffCalculator->getDiff();

            // Empty diff
            if (empty($diff['schema']) && empty($diff['data'])) {
                Logger::info("Identical resources");
            } else {

                foreach ( $params->filters as $filter )
                {
                    $diff = new $filter($diff->getDiff(), $params);
                }

                $up =''; $down = '';
                if ($params->include !== 'down') {
                    $up = $diff->getUp();
                }
                if ($params->include !== 'up') {
                    $down = $diff->getDown();
                }

                // Generate
                $templater = new Templater($params, $up, $down);
                $templater->output();
            }

            Logger::success("Completed");

        } catch (\Exception $e) {
            if ($e instanceof BaseException) {
                Logger::error($e->getMessage(), true);
            } else {
                Logger::error("Unexpected error: " . $e->getMessage());
                throw $e;
            }
        }

    }
}
