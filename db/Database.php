<?php

namespace twent\mvccore\db;

use PDO;
use twent\mvccore\{App};

class Database
{
    public PDO $pdo;

    public function __construct(array $dbConfig)
    {
        $host = $dbConfig['host'] ?? '';
        $user = $dbConfig['user'] ?? '';
        $password = $dbConfig['password'] ?? '';

        $this->pdo = new PDO($host, $user, $password);
        // Вывод ошибок подключения к БД
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(App::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once App::$ROOT_DIR . '/migrations/' . $migration;
            $migrationClassName = pathinfo($migration, PATHINFO_FILENAME);
            $magrationInstance = new $migrationClassName();
            $this->log("Применение миграции $migration");
            $magrationInstance->up();
            $this->log("Миграция $migration применена");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("Нет доступных миграций для применения");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }

    protected function getAppliedMigrations()
    {
        $stmnt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmnt->execute();

        return $stmnt->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $newMigrations)
    {
        // Разбиваем массив миграций, приводим к правильному виду для SQL
        $migrations = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        $stmnt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $migrations
        ");
        $stmnt->execute();
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }

}
