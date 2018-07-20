<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace quoma\core\db;

use Yii;
use yii\db\Migration;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Description of DynamicMigration
 *
 * @author mmoyano
 */
class DynamicMigration extends Migration{

    private $migrationPath;
    
    /**
     * @var string the name of the table for keeping applied migration information.
     */
    public $migrationTable = '{{%migration}}';
    
    /**
     * Maximum length of a migration name.
     * @since 2.0.13
     */
    const MAX_NAME_LENGTH = 180;
    
    /**
     * The name of the dummy migration that marks the beginning of the whole migration history.
     */
    const BASE_MIGRATION = 'm000000_000000_base';
    
    public function init() {
        parent::init();
        $this->db = Yii::$app->dynamicDb->getDb();
    }
    
    /**
     * Corre las migraciones del path especificado
     * @param string $migrationPath
     * @return boolean|int
     * @throws InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function migrate(string $migrationPath)
    {
        $path = Yii::getAlias($migrationPath);
        if (!is_dir($path)) {
            throw new InvalidConfigException("Migration failed. Directory specified in migrationPath doesn't exist: $this->migrationPath");
        }
        $this->migrationPath = $path;
        
        $migrations = $this->getNewMigrations();
        if (empty($migrations)) {
            return true;
        }

        $n = count($migrations);

        foreach ($migrations as $migration) {
            $nameLimit = $this->getMigrationNameLimit();
            if ($nameLimit !== null && strlen($migration) > $nameLimit) {
                throw new \yii\web\HttpException(500, "The migration name '$migration' is too long. Its not possible to apply this migration.");
            }
        }
        
        $applied = 0;
        foreach ($migrations as $migration) {
            if (!$this->migrateUp($migration)) {
                throw new \yii\web\HttpException(500, "Migration '$migration' failed.");
            }
            $applied++;
        }

        return $applied;
    }
    
    /**
     * Upgrades with the specified migration class.
     * @param string $class the migration class name
     * @return bool whether the migration is successful
     */
    protected function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        $migration = $this->createMigration($class);
        if ($migration->up() !== false) {
            $this->addMigrationHistory($class);

            return true;
        }

        return false;
    }
    
    /**
     * Creates a new migration instance.
     * @param string $class the migration class name
     * @return \yii\db\Migration the migration instance
     */
    protected function createMigration($class)
    {
        $this->includeMigrationFile($class);

        return Yii::createObject([
            'class' => $class,
            'db' => $this->db,
            'compact' => $this->compact,
        ]);
    }
    
    
    /**
     * Includes the migration file for a given migration class name.
     *
     * This function will do nothing on namespaced migrations, which are loaded by
     * autoloading automatically. It will include the migration file, by searching
     * [[migrationPath]] for classes without namespace.
     * @param string $class the migration class name.
     * @since 2.0.12
     */
    protected function includeMigrationFile($class)
    {
        $class = trim($class, '\\');
        if (strpos($class, '\\') === false) {
            if (is_array($this->migrationPath)) {
                foreach ($this->migrationPath as $path) {
                    $file = $path . DIRECTORY_SEPARATOR . $class . '.php';
                    if (is_file($file)) {
                        require_once $file;
                        break;
                    }
                }
            } else {
                $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
                require_once $file;
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    protected function addMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->insert($this->migrationTable, [
            'version' => $version,
            'apply_time' => time(),
        ])->execute();
    }
    
    /**
     * Returns the migrations that are not applied.
     * @return array list of new migrations
     */
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $class => $time) {
            $applied[trim($class, '\\')] = true;
        }

        $migrationPaths = [];
        if (is_array($this->migrationPath)) {
            foreach ($this->migrationPath as $path) {
                $migrationPaths[] = [$path, ''];
            }
        } elseif (!empty($this->migrationPath)) {
            $migrationPaths[] = [$this->migrationPath, ''];
        }
        
        $migrations = [];
        foreach ($migrationPaths as $item) {
            list($migrationPath, $namespace) = $item;
            if (!file_exists($migrationPath)) {
                continue;
            }
            $handle = opendir($migrationPath);
            while (($file = readdir($handle)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $path = $migrationPath . DIRECTORY_SEPARATOR . $file;
                if (preg_match('/^(m(\d{6}_?\d{6})\D.*?)\.php$/is', $file, $matches) && is_file($path)) {
                    $class = $matches[1];
                    if (!empty($namespace)) {
                        $class = $namespace . '\\' . $class;
                    }
                    $time = str_replace('_', '', $matches[2]);
                    if (!isset($applied[$class])) {
                        $migrations[$time . '\\' . $class] = $class;
                    }
                }
            }
            closedir($handle);
        }
        ksort($migrations);

        return array_values($migrations);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getMigrationHistory($limit)
    {
        if ($this->db->schema->getTableSchema($this->migrationTable, true) === null) {
            $this->createMigrationHistoryTable();
        }
        $query = (new Query())
            ->select(['version', 'apply_time'])
            ->from($this->migrationTable)
            ->orderBy(['apply_time' => SORT_DESC, 'version' => SORT_DESC]);

        $query->limit($limit);
        $rows = $query->all($this->db);
        $history = ArrayHelper::map($rows, 'version', 'apply_time');
        unset($history[self::BASE_MIGRATION]);
        return $history;

    }
    
    /**
     * Creates the migration history table.
     */
    protected function createMigrationHistoryTable()
    {
        $tableName = $this->db->schema->getRawTableName($this->migrationTable);
        $this->db->createCommand()->createTable($this->migrationTable, [
            'version' => 'varchar(' . static::MAX_NAME_LENGTH . ') NOT NULL PRIMARY KEY',
            'apply_time' => 'integer',
        ])->execute();
        $this->db->createCommand()->insert($this->migrationTable, [
            'version' => self::BASE_MIGRATION,
            'apply_time' => time(),
        ])->execute();
    }
    
    /**
     * Returns the file path matching the give namespace.
     * @param string $namespace namespace.
     * @return string file path.
     * @since 2.0.10
     */
    private function getNamespacePath($namespace)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, Yii::getAlias('@' . str_replace('\\', '/', $namespace)));
    }
    
    /**
     * {@inheritdoc}
     * @since 2.0.13
     */
    protected function getMigrationNameLimit()
    {
        return static::MAX_NAME_LENGTH;
    }
}
