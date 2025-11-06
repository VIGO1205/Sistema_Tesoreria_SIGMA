<?php

namespace App\Helpers;

class TableAction {
    const DEFAULT_IMPORT_DIR = "components.actions.";
    public string $importDir;
    public bool $needsPermission = false;
    public ?string $resource = null;
    public string $routeName;

    /**
     * Construye un nuevo objeto TableAction que sirve para definir las acciones en cada fila de la tabla que estás incluyendo.
     * @param string $actionName Ruta desde la carpeta raíz del proyecto a la acción o nombre de la acción.
     * @param string $routeName Nombre de la ruta a la que se dirigirá al usuario cuando se establezca el ID afectado.
     * @param string $resource Si es que requiere permiso en Gate, especificar a qué recurso se accede.
     */

    public function __construct(string $actionName, string $routeName, $resource = null){
        if (strpos($actionName, '.') !== false){
            $this->importDir = $actionName;
        } else {
            $this->importDir = self::DEFAULT_IMPORT_DIR . $actionName;
        }

        if ($resource){
            $this->resource = $resource;
            $this->needsPermission = true;
        }

        $this->routeName = $routeName;
    }

    /**
     * Devuelve HTML de importación en base al TableAction actual.
     * @param array $params Parámetros que se desea pasar a la acción.
     */

    public function new(...$params){
        return view($this->importDir, [
            'resource' => $this->resource,
            'routeName' => $this->routeName,
            'params' => $params,
        ]);
    }
}