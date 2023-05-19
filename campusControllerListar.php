<?php

header("Content-Type: text/html;charset=utf-8");
session_start();

require_once "campusConfig.php";

// buscar         
$cursoCuerpo = $_GET["cursoCuerpoSelect"];
$bloque = $_GET["bloqueSelect"];
$clasificacion = $_GET["clasificacionSelect"];
$examen = $_GET["examenSelect"];
$nivel = $_GET["nivelSelect"];
$percentErrorFilter = $_GET["percentErrorFilter"];
$textFilter = $_GET["textFilter"];
$sinRespuestasFilter = $_GET["sinRespuestasFilter"];
$favoritasFilter = $_GET["favoritasFilter"];
$clasifPlusFilter = $_GET["clasifPlusFilter"];
$bugFilter = $_GET["bugFilter"];   
$noExisteRespuestaFilter = $_GET["noExisteRespuestaFilter"]; 
$idPreguntaClasificacion = $_GET["idPreguntaClasificacion"];
$idPreguntaClasificacionRespondidas = $idPreguntaClasificacion;

echo '<br>';
echo "<h1>LISTADO DE BUSQUEDA:</h1> PARAMS: Cuerpo:". $cursoCuerpo . " / Bloque:". $bloque . " / Tema:" . $clasificacion;
echo '<br>';
//exit;


// FILTRO DE CURSO (este filtro obligatorio), 
$queryPregunta = "SELECT RAND() as random, p.id, p.texto, pc.id as idPreguntaClasificacion, pc.idClasificacion, co.tema, e.descripcion as examen, e.modalidad,
IFNULL((select concat(o.descripcion,' ', o.anio) from tabOferta o where e.idOferta = o.id),'') as oferta, e.fecha_examen, 
c.descripcion as cuerpo,
i.link
FROM tabPreguntas p
JOIN tabPreguntasClasificacion pc ON pc.idPregunta = p.id
JOIN tabClasificacion co ON pc.idClasificacion = co.id
LEFT JOIN tabExamen e ON p.idExamen = e.id
LEFT JOIN tabImagen i ON p.idImagen = i.id
LEFT JOIN tabClasificacionCuerpo cc ON cc.idClasificacion = co.id
LEFT JOIN tabCuerpo c ON cc.idCuerpo = c.id
WHERE 
cc.idCuerpo = " . $cursoCuerpo . "
and pc.id not in ( 0 )";
//AND c.tipo = 1 ";

// FILTRO DE CLASIFICACION (+)
if (isset($clasifPlusFilter) && $clasifPlusFilter!= null && $clasifPlusFilter == "true") {
    $queryPregunta = $queryPregunta . " AND co.tipo = 1 ";
} 

// FILTRO DE BLOQUE
if (isset($bloque) && $bloque!= null && $bloque!= "") {
    $queryPregunta = $queryPregunta . " AND (co.idBloque = " . $bloque . ") ";
}

// FILTRO DE CLASIFICACION
if (isset($clasificacion) && $clasificacion!= null && $clasificacion!= "") {
    $queryPregunta = $queryPregunta . " AND (co.id = " . $clasificacion . ") ";
}


// FILTRO DE EXAMEN
if (isset($examen) && $examen!= null && $examen!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Examen Filter ON ";
    $queryPregunta = $queryPregunta . " AND e.id = " . $examen . " ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Examen Filter OFF ";
}


// FILTRO DE NIVEL
if (isset($nivel) && $nivel!= null && $nivel!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Nivel Filter ON ";
    $queryPregunta = $queryPregunta . " AND p.nivel = '" . $nivel . "' ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Nivel Filter OFF ";
}

// FILTRO DE TEXTO
if (isset($textFilter) && $textFilter!= null && $textFilter!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Text Filter ON ";
    $queryPregunta = $queryPregunta . " AND (
            upper(p.texto) like '%" . $textFilter . "%' 
            OR exists (select 1 from tabRespuestas tr 
                where tr.idPregunta = p.id and upper(tr.texto) like '%".$textFilter."%')
            )";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Text Filter OFF ";
}

// FILTRO DE % ERROR
if (isset($percentErrorFilter) && $percentErrorFilter!= null && $percentErrorFilter!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | PercentError Filter ON ";
    $queryPregunta = $queryPregunta . " 
    AND IFNULL(
       (select count(1) from tabRespuestasUsuario ru where ru.idPregunta = p.id and ru.idUsuario = " . $_SESSION["session_id_username"] . " and acierto = 0)
       / (select count(1) from tabRespuestasUsuario ru where ru.idPregunta = p.id and ru.idUsuario = " . $_SESSION["session_id_username"] . " )
       ,0) > (" . $percentErrorFilter . "/100)";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | PercentError Filter OFF ";
}

// FILTRO de FAVORITAS
if (isset($favoritasFilter) && $favoritasFilter!= null && $favoritasFilter == "true") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Favoritas Filter ON ";
    $queryPregunta = $queryPregunta . " AND exists (select 1 from tabMarcaPreguntaUsuario mpu WHERE mpu.marca='S' AND mpu.idPregunta = p.id AND mpu.idUsuario = " . $_SESSION["session_id_username"] . " ) ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Favoritas Filter OFF ";
}

// FILTRO de BUG
if (isset($bugFilter) && $bugFilter!= null && $bugFilter == "true") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Bug Filter ON ";
    $queryPregunta = $queryPregunta . " AND exists (select 1 from tabDudaErrorPreguntaUsuario depu WHERE depu.idPregunta = p.id ) ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Bug Filter OFF ";
}

// FILTRO NO EXISTE RESPUESTA
if (isset($noExisteRespuestaFilter) && $noExisteRespuestaFilter!= null && $noExisteRespuestaFilter == "true") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | NoExisteRepuesta Filter ON ";
    $queryPregunta = $queryPregunta . " AND not exists (select 1 from tabRespuestas r WHERE r.idPregunta = p.id AND r.correcta = 1 ) ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | NoExisteRepuesta Filter OFF ";
}

// FILTRO DE SIN-RESPUESTAS
if (isset($sinRespuestasFilter) && $sinRespuestasFilter!= null && $sinRespuestasFilter == "true") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | SinRepuesta Filter ON ";
    $queryPregunta = $queryPregunta . " AND not exists (select 1 from tabRespuestasUsuario ru WHERE ru.idPregunta = p.id AND ru.idUsuario = " . $_SESSION["session_id_username"] . " ) ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | SinRepuesta Filter OFF ";
}

$queryPregunta = $queryPregunta . " ORDER BY random " ; // LIMIT 0,6";

echo $queryPregunta;
//exit;
echo '<br>';
echo '<br>';

$resultPregunta = mysqli_query($link, $queryPregunta);

while ($row = mysqli_fetch_array($resultPregunta))
{
    //echo 'Procesando pregunta:' . $row['id'] . ' | ' . $row['texto']; 
    //exit;

    echo  '<br>';
    echo  '<b>ID: </b>' . $row['id'] . ' ';
    echo  '<b>TEMA: </b>' . $row['tema'] . ' ';
    echo  '<b>EXAMEN: </b>' . $row['examen'] . ' ';
    echo  '<br><b>' . "PREGUNTA: " . '</b><br>';
    echo  $row['texto'] . '<br>';
    echo  '<br>';
    //exit;

    $sql = "SELECT texto, correcta FROM `tabRespuestas` WHERE idPregunta = " . $row['id'] . " ORDER BY ID ASC LIMIT 0,6";
    $resultRespuestas = mysqli_query($link, $sql);

    //exit;
        
    while ($row = mysqli_fetch_array($resultRespuestas))
    {      
        
        if (boolval($row['correcta']) == true ){
            echo "<div style='color: #339800;'>";
            echo "(*): ";         
            echo $row['texto'] . '</div>';
            
        } else {
            echo "<div>" . $row['texto'] . '</div>';
        }

        
    }

    echo  '<br>';
    echo  '<hr size="2px" color="black">';
}


?>
