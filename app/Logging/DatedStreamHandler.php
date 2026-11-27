<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Escribe en {directorio}/AAAA/Mes/DD.log (ej. storage/logs/2026/November/27.log).
 *
 * - La ruta se calcula en cada escritura: cambia de archivo al cambiar el día aunque el
 *   proceso siga vivo (colas, Octane) o la configuración esté cacheada (config:cache).
 * - Las carpetas de año y mes se crean con 0777 y los archivos con 0666, sin depender del
 *   umask: así la web (www) y el cron (root) pueden escribir y crear archivos en ellas
 *   las haya creado quien las haya creado.
 */
class DatedStreamHandler extends StreamHandler
{
    private ?string $dirAsegurado = null;

    public function __construct(
        private string $directory,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
        private int $dirPermission = 0777,
        ?int $filePermission = 0666,
    ) {
        $this->directory = rtrim($directory, '/\\');
        parent::__construct($this->rutaDelDia(), $level, $bubble, $filePermission);
    }

    protected function write(LogRecord $record): void
    {
        $ruta = $this->rutaDelDia();
        if ($ruta !== $this->url) {
            $this->close();
            $this->url = $ruta;
        }
        $this->asegurarDirectorio(dirname($ruta));

        parent::write($record);
    }

    private function rutaDelDia(): string
    {
        return $this->directory.'/'.date('Y').'/'.date('F').'/'.date('d').'.log';
    }

    private function asegurarDirectorio(string $dir): void
    {
        if ($this->dirAsegurado === $dir) {
            return;
        }

        // Crea año y mes de uno en uno para aplicar el permiso a cada carpeta nueva.
        $actual = $this->directory;
        foreach (explode('/', substr($dir, strlen($this->directory) + 1)) as $parte) {
            $actual .= '/'.$parte;
            if (! is_dir($actual) && @mkdir($actual, $this->dirPermission)) {
                @chmod($actual, $this->dirPermission);
            }
        }

        $this->dirAsegurado = $dir;
    }
}
