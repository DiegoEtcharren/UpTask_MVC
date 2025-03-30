<?php 

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController {

    public static function index()
    {
        isSession();
        isAuth();
        $proyectoUrl = $_GET['url'];
        if (!$proyectoUrl) {
            header('Location: /');
            exit;
        }

        $proyecto = Proyecto::where('url', $proyectoUrl);

        if (!$proyecto || $proyecto->propietarioId != $_SESSION['id'] ) {
            header('Location: /404');
            exit;
        }

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);

    }

    public static function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            isSession();
            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId);
            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al Agregar la Tarea'
                ];
                echo json_encode($respuesta);
                return;
            };
            // Instancear una tarea: 
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if ($resultado['resultado']) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $resultado['id'],
                    'mensaje' => 'Tarea Creada Correctamente'
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al Agregar la Tarea'
                ]; 
            }
            echo json_encode($respuesta);
            
        }
    }

    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }


}

?>