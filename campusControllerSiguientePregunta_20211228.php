<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here

//data: { cursoCuerpoSelect: cuerpo, temaSelect: tema, nivelSelect: nivel},
//data: { cursoCuerpoSelect: cuerpo, temaSelect: tema, nivelSelect: nivel},

// En funcion del si es consulta nueva o se ha pulsado en siguiente, cogemos los datos del POST o de los almacenados en SESSION

if ($_POST["idPreguntaClasificacion"] == "'0 - 0'") {
    // buscar         
    $cursoCuerpo = $_POST["cursoCuerpoSelect"];
    $tema = $_POST["temaSelect"];
    $nivel = $_POST["nivelSelect"];
    $percentErrorFilter = $_POST["percentErrorFilter"];
    $textFilter = $_POST["textFilter"];
    $sinRespuestasFilter = $_POST["sinRespuestasFilter"];
    $idPreguntaClasificacion = $_POST["idPreguntaClasificacion"];
    $idPreguntaClasificacionRespondidas = $idPreguntaClasificacion;

    // guardamos las variables en session
    $_SESSION["cursoCuerpoSelect"] = $cursoCuerpo;
    $_SESSION["temaSelect"] = $tema;
    $_SESSION["nivelSelect"] = $nivel;
    $_SESSION["percentErrorFilter"] = $percentErrorFilter;
    $_SESSION["textFilter"] = $textFilter;
    $_SESSION["sinRespuestasFilter"] = $sinRespuestasFilter;
    $_SESSION["idPreguntaClasificacionRespondidas"] = $idPreguntaClasificacionRespondidas;

} else {
    // siguiente
    // recuperamos las variables en session, excepto el secualcial de idPregunta     
    $cursoCuerpo = $_SESSION["cursoCuerpoSelect"];
    $tema = $_SESSION["temaSelect"];
    $nivel = $_SESSION["nivelSelect"];
    $percentErrorFilter = $_SESSION["percentErrorFilter"];
    $textFilter = $_SESSION["textFilter"];
    $sinRespuestasFilter = $_SESSION["sinRespuestasFilter"];
    $idPreguntaClasificacion = $_POST["idPreguntaClasificacion"];
    $idPreguntaClasificacionRespondidas = $_SESSION["idPreguntaClasificacionRespondidas"] . ", " . $idPreguntaClasificacion;
    $_SESSION["idPreguntaClasificacionRespondidas"] = $idPreguntaClasificacionRespondidas;

}

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

//TODO: idea, a nivel de test, una pregunta puede estar vinculada a 3 temas, seria como 3 preguntas, pero a nivel de tabPregunta solo esta 1 vez, a nivel de estadisticas solo 1 vez, pero a nivel de tema, la preguna puede aparece 3 veces en el buscador, ya que esta "duplicada" digamos para 3 temas. 

$queryPregunta = "SELECT RAND() as random, p.id, p.texto, pc.idClasificacion, c.tema,
e.descripcion as examen, e.modalidad,
(select concat(o.descripcion,' ', o.anio) from tabOferta o where e.idOferta = o.id) as oferta
FROM tabPreguntas p 
LEFT JOIN tabExamen e ON p.idExamen = e.id
JOIN tabPreguntasClasificacion pc ON pc.idPregunta = p.id
JOIN tabClasificacion c ON pc.idClasificacion = c.id ";

// FILTRO DE CURSO (este filtro obligatorio), 
// TODO: ademas a futuro, los usuarios se suscribiran a un curso no a todos los que hay. 
if (isset($cursoCuerpo) && $cursoCuerpo!= null && $cursoCuerpo!= "") {
    $pregunta['metadatos'] = "CursoCuerpo Filter ON ";

    $queryPregunta = $queryPregunta . " JOIN tabOfertaCuerpo_Clasificacion occ ON c.id = occ.idClasificacion ";
    $queryPregunta = $queryPregunta . " JOIN tabOfertaCuerpo oc ON occ.idOfertaCuerpo = oc.id ";
    $queryPregunta = $queryPregunta . " JOIN tabCuerpo c2 ON oc.idCuerpo = c2.id ";
    $queryPregunta = $queryPregunta . " WHERE CONCAT(p.id , ' - ' , c.id) not in (". $idPreguntaClasificacionRespondidas .") ";
    $queryPregunta = $queryPregunta . " AND c2.id = " . $cursoCuerpo . " ";
} else {
    $pregunta['metadatos'] = "CursoCuerpo Filter OFF ";
    $queryPregunta = $queryPregunta . "WHERE CONCAT(p.id , ' - ' , c.id) not in (". $idPreguntaClasificacionRespondidas .") ";
}


// FILTRO DE TEMA (o este filtro obligatorio), 
if (isset($tema) && $tema!= null && $tema!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Tema Filter ON ";
    $queryPregunta = $queryPregunta . " AND c.id = " . $tema . " ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Tema Filter OFF ";
}

// FILTROS DE OFERTA, EXAMEN, ...


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
    $queryPregunta = $queryPregunta . " AND upper(p.texto) like '%" . $textFilter . "%' ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Text Filter OFF ";
}

// FILTRO DE % ERROR
if (isset($percentErrorFilter) && $percentErrorFilter!= null && $percentErrorFilter!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | PercentError Filter ON ";
    $queryPregunta = $queryPregunta . " AND (
    select count(1) from tabRespuestasUsuario ru where ru.idPregunta = p.id and ru.idUsuario = 1 and acierto = 0)
    /
    ((select count(1) from tabRespuestasUsuario ru where ru.idPregunta = p.id and ru.idUsuario = 1 and acierto = 1)
    +
    (select count(1) from tabRespuestasUsuario ru where ru.idPregunta = p.id and ru.idUsuario = 1 and acierto = 0)
    ) > (" . $percentErrorFilter . "/100)";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | PercentError Filter OFF ";
}


// FILTRO DE SIN-RESPUESTAS
if (isset($sinRespuestasFilter) && $sinRespuestasFilter!= null && $sinRespuestasFilter == "true") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | SinRepuesta Filter ON ";
    $queryPregunta = $queryPregunta . " AND not exists (select 1 from tabRespuestasUsuario ru WHERE ru.idPregunta = p.id AND ru.idUsuario = " . $_SESSION["session_id_username"] . " ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | SinRepuesta Filter OFF ";
}

$queryPregunta = $queryPregunta . " ORDER BY random " ; // LIMIT 0,6";

//printf($queryPregunta);
//exit;

$resultPregunta = mysqli_query($link, $queryPregunta);

$pregunta['numTotalPreguntas'] = mysqli_num_rows($resultPregunta)-1;

while ($row = mysqli_fetch_array($resultPregunta))
{
    //printf("Procesando %dº pregunta: %s.",$row['id'], $row['texto']); 

    $pregunta['idPregunta'] = $row['id'];
    $idPregunta = $row['id'];
    $pregunta['texto'] = $row['texto'];
    $pregunta['idClasificacion'] = $row['idClasificacion'];
    $pregunta['tema'] = $row['tema'];
    $pregunta['examen'] = $row['examen'];
    $pregunta['modalidad'] = $row['modalidad'];
    $pregunta['oferta'] = $row['oferta'];
    break;
}

$queryRespuestas = "SELECT id,texto,correcta FROM tabRespuestas WHERE idPregunta = " . $idPregunta . " ORDER BY ID ASC LIMIT 0,6";
$resultRespuestas = mysqli_query($link, $queryRespuestas);

//printf("Select returned resultRespuestas %d rows.\n", mysqli_num_rows($resultRespuestas));
//exit;

$i=0;
while ($row = mysqli_fetch_array($resultRespuestas))
{
    switch ($i) {
        case 0:
            $pregunta['respuestaA'] = $row['texto'];
            if ($row['correcta']==1){
                $pregunta['respuestaCorrecta'] = 'A';
            }
            break;
        case 1:
            $pregunta['respuestaB'] = $row['texto'];
            if ($row['correcta']==1){
                $pregunta['respuestaCorrecta'] = 'B';
            }
            break;
        case 2:
            $pregunta['respuestaC'] = $row['texto'];
            if ($row['correcta']==1){
                $pregunta['respuestaCorrecta'] = 'C';
            }
            break;
        case 3:
            $pregunta['respuestaD'] = $row['texto'];
            if ($row['correcta']==1){
                $pregunta['respuestaCorrecta'] = 'D';
            }
            break;
    }
    $i++;    
}

//$pregunta['id'] = '10';
//$pregunta['texto'] = 'pregunta';
//$pregunta['respuestaA'] = 'respuestaA';
//$pregunta['respuestaB'] = 'respuestaB';
//$pregunta['respuestaC'] = 'respuestaC';
//$pregunta['respuestaD'] = 'respuestaD';
//$pregunta['respuestaCorrecta'] = 'B';

$pregunta['metadatos'] = $pregunta['metadatos'] . " | Consulta con parametros: cursoCuerpo=" . $cursoCuerpo . " | tema=" . $tema . " | nivel=" . $nivel . " | percentErrorFilter=" . $percentErrorFilter . " | textFilter=" . $textFilter . " | sinRespuestasFilter=" . $sinRespuestasFilter;

//printf("Llegamos al final");

/*

Estadisticas PREGUNTA

*/

$queryEstadisticas = "SELECT count(1) as num FROM tabRespuestasUsuario 
WHERE idPregunta = " . $idPregunta . " AND acierto = 0 AND idUsuario = " . $_SESSION["session_id_username"];

//printf($queryEstadisticas);
//exit;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['erroresPregunta'] = $row['num'];
    break;
}
if ( is_null($pregunta['erroresPregunta'])) $pregunta['erroresPregunta'] = 0;


$queryEstadisticas = "SELECT count(1) as num FROM tabRespuestasUsuario 
WHERE idPregunta = " . $idPregunta . " AND acierto = 1 AND idUsuario = " . $_SESSION["session_id_username"];

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['aciertosPregunta'] = $row['num'];
    break;
}
if ( is_null($pregunta['aciertosPregunta'])) $pregunta['aciertosPregunta'] = 0;


/*

Estadisticas TEMA

*/

$queryEstadisticas = "SELECT count(1) as num FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc 
WHERE ru.idPregunta = p.id AND ru.acierto = 1 AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id AND pc.idClasificacion = " . $pregunta['idClasificacion'] ;

//printf($queryEstadisticas);
//exit;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['aciertosTema'] = $row['num'];
    break;
}
if ( is_null($pregunta['aciertosTema'])) $pregunta['aciertosTema'] = 0;

$queryEstadisticas = "SELECT count(1) as num FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc 
WHERE ru.idPregunta = p.id AND ru.acierto = 0 AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id AND pc.idClasificacion = " . $pregunta['idClasificacion'] ;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['erroresTema'] = $row['num'];
    break;
}
if ( is_null($pregunta['erroresTema'])) $pregunta['erroresTema'] = 0;


/*

Estadisticas CURSO/CUERPO

*/

$queryEstadisticas = "SELECT count(1) as num from (select distinct ru.id 
FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc,
tabOfertaCuerpo_Clasificacion occ, tabOfertaCuerpo oc, tabCuerpo c 
WHERE ru.idPregunta = p.id 
AND ru.acierto = 1 
AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id 
AND pc.idClasificacion = occ.idClasificacion
AND occ.idOfertaCuerpo = oc.id
AND oc.idCuerpo = " . $cursoCuerpo. ") consulta" ;

//printf($queryEstadisticas);
//exit;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['aciertosCursoOferta'] = $row['num'];
    break;
}
if ( is_null($pregunta['aciertosCursoOferta'])) $pregunta['aciertosCursoOferta'] = 0;

$queryEstadisticas = "SELECT count(1) as num from (select distinct ru.id 
FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc,
tabOfertaCuerpo_Clasificacion occ, tabOfertaCuerpo oc, tabCuerpo c 
WHERE ru.idPregunta = p.id 
AND ru.acierto = 0 
AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id 
AND pc.idClasificacion = occ.idClasificacion
AND occ.idOfertaCuerpo = oc.id
AND oc.idCuerpo = " . $cursoCuerpo. ") consulta" ;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['erroresCursoOferta'] = $row['num'];
    break;
}
if ( is_null($pregunta['erroresCursoOferta'])) $pregunta['erroresCursoOferta'] = 0;

/*

Estadisticas SESION

*/

$pregunta['aciertosSession'] = $_SESSION['aciertosSession'] ;       // calculo total de la sesion
$pregunta['erroresSession'] = $_SESSION['erroresSession'] ;

echo json_encode($pregunta, JSON_INVALID_UTF8_SUBSTITUTE);

?>