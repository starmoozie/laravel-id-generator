<?php namespace Starmoozie\IdGenerator\Traits;

use Starmoozie\IdGenerator\IdGenerator;

trait IdFactory
{
    private static function validateIdConfig()
    {
        if (!isset(self::$idConfig)) throw new \Exception("config array harus diisi");

        if (!isset(self::$idConfig['length']) || !isset(self::$idConfig['prefix'])) {
            throw new \Exception("index length & prefix harus ada");
        }
    }

    public static function bootIdGeneratable()
    {
        self::creating(function ($model) {
            self::validateIdConfig();

            $config = [
                'table'  => $model->getTable(),
                'length' => self::$idConfig['length'],
                'prefix' => self::$idConfig['prefix'],
            ];

            if (isset(self::$idConfig['reset_on_prefix_change'])) {
                $config['reset_on_prefix_change'] = self::$idConfig['reset_on_prefix_change'];
            }

            if (isset(self::$idConfig['field'])) {
                $config['field']                 = self::$idConfig['field'];
                $model[self::$idConfig['field']] = IdGenerator::generate($config);
            }
            else {
                $model->id = IdGenerator::generate($config);
            }
        });
    }
}
