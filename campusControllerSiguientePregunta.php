<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here

//data: { cursoCuerpoSelect: cuerpo, temaSelect: tema, nivelSelect: nivel},
//data: { cursoCuerpoSelect: cuerpo, temaSelect: tema, nivelSelect: nivel},

// En funcion del si es consulta nueva o se ha pulsado en siguiente, cogemos los datos del POST o de los almacenados en SESSION

if ($_POST["idPreguntaClasificacion"] == "0") {
    // buscar         
    $cursoCuerpo = $_POST["cursoCuerpoSelect"];
    $bloque = $_POST["bloqueSelect"];
    $clasificacion = $_POST["clasificacionSelect"];
    $tema = $_POST["temaSelect"];
    $oferta = $_POST["ofertaSelect"];
    $examen = $_POST["examenSelect"];
    $nivel = $_POST["nivelSelect"];
    $percentErrorFilter = $_POST["percentErrorFilter"];
    $textFilter = $_POST["textFilter"];
    $sinRespuestasFilter = $_POST["sinRespuestasFilter"];
    $favoritasFilter = $_POST["favoritasFilter"];
    $clasifPlusFilter = $_POST["clasifPlusFilter"];
    $bugFilter = $_POST["bugFilter"];   
    $noExisteRespuestaFilter = $_POST["noExisteRespuestaFilter"]; 
    $idPreguntaClasificacion = $_POST["idPreguntaClasificacion"];
    $idPreguntaClasificacionRespondidas = $idPreguntaClasificacion;

    // guardamos las variables en session para mantener la consulta y poder avanzar pregunta a pregunta en ella
    $_SESSION["cursoCuerpoSelect"] = $cursoCuerpo;
    $_SESSION["bloqueSelect"] = $bloque;
    $_SESSION["clasificacionSelect"] = $clasificacion;
    $_SESSION["temaSelect"] = $tema;
    $_SESSION["ofertaSelect"] = $oferta;
    $_SESSION["examenSelect"] = $examen;
    $_SESSION["nivelSelect"] = $nivel;
    $_SESSION["percentErrorFilter"] = $percentErrorFilter;
    $_SESSION["textFilter"] = $textFilter;
    $_SESSION["sinRespuestasFilter"] = $sinRespuestasFilter;
    $_SESSION["favoritasFilter"] = $favoritasFilter;
    $_SESSION["clasifPlusFilter"] = $clasifPlusFilter;
    $_SESSION["bugFilter"] = $bugFilter;
    $_SESSION["noExisteRespuestaFilter"] = $noExisteRespuestaFilter;
    $_SESSION["idPreguntaClasificacionRespondidas"] = $idPreguntaClasificacionRespondidas;

} else {
    // siguiente
    // recuperamos las variables en session, excepto el secualcial de idPregunta     
    $cursoCuerpo = $_SESSION["cursoCuerpoSelect"];
    $bloque = $_SESSION["bloqueSelect"];
    $clasificacion = $_SESSION["clasificacionSelect"];
    $tema = $_SESSION["temaSelect"];
    $oferta = $_SESSION["ofertaSelect"];
    $examen = $_SESSION["examenSelect"];
    $nivel = $_SESSION["nivelSelect"];
    $percentErrorFilter = $_SESSION["percentErrorFilter"];
    $textFilter = $_SESSION["textFilter"];
    $sinRespuestasFilter = $_SESSION["sinRespuestasFilter"];
    $favoritasFilter = $_SESSION["favoritasFilter"];
    $clasifPlusFilter = $_SESSION["clasifPlusFilter"];
    $bugFilter = $_SESSION["bugFilter"];
    $noExisteRespuestaFilter = $_SESSION["noExisteRespuestaFilter"];
    $idPreguntaClasificacion = $_POST["idPreguntaClasificacion"];
    $idPreguntaClasificacionRespondidas = $_SESSION["idPreguntaClasificacionRespondidas"] . ", " . $idPreguntaClasificacion;
    $_SESSION["idPreguntaClasificacionRespondidas"] = $idPreguntaClasificacionRespondidas;

    // comprobamos si se contesto la ultima pregunta
    $sinContestarSession = $_POST["sinContestarSession"];
    if ($sinContestarSession == "false") { // si no se ha contestado., +1
        $_SESSION["sinContestarSession"] = $_SESSION["sinContestarSession"] + 1;
    }
}



// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

//TODO: idea, a nivel de test, una pregunta puede estar vinculada a 3 temas, seria como 3 preguntas, pero a nivel de tabPregunta solo esta 1 vez, a nivel de estadisticas solo 1 vez, pero a nivel de tema, la preguna puede aparece 3 veces en el buscador, ya que esta "duplicada" digamos para 3 temas. 
// FILTRO DE CURSO (este filtro obligatorio), 
$queryPregunta = "SELECT RAND() as random, p.id, p.texto, pc.id as idPreguntaClasificacion, pc.idClasificacion, co.tema, e.descripcion as examen, e.modalidad,
IFNULL((select concat(o.descripcion,' ', o.anio) from tabOferta o where e.idOferta = o.id),'') as oferta, e.fecha_examen, 
(select cu.descripcion from tabCuerpo cu where cu.id = " . $cursoCuerpo . " ) as cuerpo,
i.link
FROM tabPreguntas p
JOIN tabPreguntasClasificacion pc ON pc.idPregunta = p.id
JOIN tabClasificacionOriginal co ON pc.idClasificacion = co.id
LEFT JOIN tabExamen e ON p.idExamen = e.id
LEFT JOIN tabImagen i ON p.idImagen = i.id
WHERE pc.id not in (". $idPreguntaClasificacionRespondidas .")";
//AND c.tipo = 1 ";

// FILTRO DE CLASIFICACION (+)
if (isset($clasifPlusFilter) && $clasifPlusFilter!= null && $clasifPlusFilter == "true") {
    $queryPregunta = $queryPregunta . " AND co.tipo = 1 ";
} /*else {
    // FILTRO DE CUERPO  (este filtro obligatorio)
    // La pregunta esta vinculada a nivel de temario al cuerpo
    // Se busca ser un poco mas ajustados al temario de cada convocatoria
    $queryPregunta = $queryPregunta . " AND EXISTS (SELECT 1 
        FROM  tabOfertaCuerpo_Clasificacion occ, tabOfertaCuerpo oc, 
        tabClasificacion c, tabMapeoClasificacionOriginal m
        WHERE oc.activa = 'S'
        AND occ.idOfertaCuerpo = oc.id
        AND occ.idClasificacion = c.id 
        AND c.id = m.idClasificacion
        AND m.idClasificacionOriginal = co.id
        AND oc.idCuerpo = " . $cursoCuerpo . ") ";
}*/

// FILTRO DE BLOQUE
if (isset($bloque) && $bloque!= null && $bloque!= "") {
    $queryPregunta = $queryPregunta . " AND (co.idBloque = " . $bloque . ") ";
}

// FILTRO DE CLASIFICACION
if (isset($clasificacion) && $clasificacion!= null && $clasificacion!= "") {
    $queryPregunta = $queryPregunta . " AND (co.id = " . $clasificacion . ") ";
}

// FILTRO DE TEMA
// La pregunta esta vinculada a temario oficial elegido
if (isset($tema) && $tema!= null && $tema!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Tema Filter ON ";
    $queryPregunta = $queryPregunta . " AND co.id IN ( 
        -- sea un tema          
        -- o un tema del bloque seleccionado
        SELECT DISTINCT mapeo1.idClasificacionOriginal 
        FROM tabClasificacion tema1, tabMapeoClasificacionOriginal mapeo1
        WHERE mapeo1.idClasificacion =  tema1.id
        AND tema1.id = " . $tema . "
        UNION
        SELECT DISTINCT mapeo2.idClasificacionOriginal 
        FROM tabClasificacion tema2, tabClasificacion tema3, tabMapeoClasificacionOriginal mapeo2
        WHERE mapeo2.idClasificacion =  tema3.id
        AND tema2.id = " . $tema . "
        AND tema3.idBloque = tema2.id
    )";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Tema Filter OFF ";
}

// FILTROS DE OFERTA, 
// La pregunta esta vinculada al examen / oferta indicado
/*if (isset($oferta) && $oferta!= null && $oferta!= "") {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Oferta Filter ON ";
    $queryPregunta = $queryPregunta . " AND e.idOferta = " . $oferta . " ";
} else {
    $pregunta['metadatos'] = $pregunta['metadatos'] . " | Oferta Filter OFF ";
}*/

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

//echo $queryPregunta;
//exit;

$resultPregunta = mysqli_query($link, $queryPregunta);

$pregunta['numTotalPreguntas'] = mysqli_num_rows($resultPregunta)-1;

while ($row = mysqli_fetch_array($resultPregunta))
{
    //printf("Procesando %dº pregunta: %s.",$row['id'], $row['texto']); 

    $pregunta['idPregunta'] = $row['id'];
    $pregunta['idPreguntaClasificacion'] = $row['idPreguntaClasificacion'];    
    $idPregunta = $row['id'];
    $pregunta['texto'] = $row['texto'];
    $pregunta['idClasificacion'] = $row['idClasificacion'];
    $pregunta['tema'] = $row['tema'];
    $pregunta['examen'] = $row['examen'];
    $pregunta['fechaExamen'] = $row['fecha_examen'];
    $pregunta['modalidad'] = $row['modalidad'];
    $pregunta['oferta'] = $row['oferta'];
    $pregunta['cuerpo'] = $row['cuerpo'];
    $pregunta['link'] = $row['link'];

    $pregunta['sql'] = $queryPregunta;
    break;
}

$queryRespuestas = "SELECT id,texto,correcta FROM tabRespuestas WHERE idPregunta = " . $idPregunta . " ORDER BY ID ASC LIMIT 0,6";
$resultRespuestas = mysqli_query($link, $queryRespuestas);

//printf("Select returned resultRespuestas %d rows.\n", mysqli_num_rows($resultRespuestas));
//exit;

$i=0;

$pregunta['respuestaCorrecta'] = '';

while ($row = mysqli_fetch_array($resultRespuestas))
{

    //printf("ResponseCorectly: " . $row['id'] . " : " . $row['correcta'] . " / ". boolval($row['correcta']) . " | " . $row['texto'] );
    //exit;

    switch ($i) {

        case 0:
            $pregunta['respuestaA'] = $row['texto'];
            $pregunta['idRespuestaA'] = $row['id'];
            if (boolval($row['correcta']) == true ){
                $pregunta['respuestaCorrecta'] = 'A';
            }
            break;
        case 1:
            $pregunta['respuestaB'] = $row['texto'];
            $pregunta['idRespuestaB'] = $row['id'];
            if (boolval($row['correcta']) == true ){
                $pregunta['respuestaCorrecta'] = 'B';
            }
            break;
        case 2:
            $pregunta['respuestaC'] = $row['texto'];
            $pregunta['idRespuestaC'] = $row['id'];
            if (boolval($row['correcta']) == true ){
                $pregunta['respuestaCorrecta'] = 'C';
            }
            break;
        case 3:
            $pregunta['respuestaD'] = $row['texto'];
            $pregunta['idRespuestaD'] = $row['id'];
            if (boolval($row['correcta']) == true ){
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

//printf("Select returned resultRespuestas %d rows.\n", mysqli_num_rows($resultRespuestas));
//exit;

/**************************************************************************

Estadisticas PREGUNTA

***************************************************************************/

/* Comprobar si hay favorita Indicador FAVORITA */
$queryFavorita = "SELECT count(1) FROM tabMarcaPreguntaUsuario
 WHERE marca = 'S'
 AND idPregunta = " . $idPregunta . " AND idUsuario = " . $_SESSION["session_id_username"];
$resultFavorita = mysqli_query($link, $queryFavorita);
$row = mysqli_fetch_row($resultFavorita);
$pregunta['favorita']  = $row[0];


$queryErrores = "SELECT count(1) as num FROM tabRespuestasUsuario 
WHERE idPregunta = " . $idPregunta . " AND acierto = 0 AND idUsuario = " . $_SESSION["session_id_username"];

//printf($queryErrores);
//exit;

$resultEstadisticas = mysqli_query($link, $queryErrores);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['erroresPregunta'] = $row['num'];
    break;
}
//if ( is_null($pregunta['erroresPregunta'])) $pregunta['erroresPregunta'] = 0;


$queryAciertos = "SELECT IFNULL(count(1),0) as num FROM tabRespuestasUsuario 
WHERE idPregunta = " . $idPregunta . " AND acierto = 1 AND idUsuario = " . $_SESSION["session_id_username"];

$resultEstadisticas = mysqli_query($link, $queryAciertos);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['aciertosPregunta'] = $row['num'];
    break;
}
//if ( is_null($pregunta['aciertosPregunta'])) $pregunta['aciertosPregunta'] = 0;


/**************************************************************************

Estadisticas TEMA

***************************************************************************/

$queryEstadisticas = "SELECT IFNULL(count(1),0) as num FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc 
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


/**************************************************************************

Estadisticas CURSO/CUERPO

***************************************************************************/

$queryEstadisticas = "SELECT count(1) as num from (select distinct ru.id 
FROM tabRespuestasUsuario ru, tabPreguntas p, tabPreguntasClasificacion pc,
tabClasificacionOriginal co, tabClasificacionOriginalCuerpo coc, tabCuerpo c 
WHERE ru.idPregunta = p.id 
AND ru.acierto = 1 
AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id 
AND pc.idClasificacion = co.id
AND coc.idClasificacion = co.id
AND coc.idCuerpo = " . $cursoCuerpo. ") consulta" ;

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
tabClasificacionOriginal co, tabClasificacionOriginalCuerpo coc, tabCuerpo c 
WHERE ru.idPregunta = p.id 
AND ru.acierto = 0 
AND ru.idUsuario = " . $_SESSION["session_id_username"] . 
" AND pc.idPregunta = p.id 
AND pc.idClasificacion = co.id
AND coc.idClasificacion = co.id
AND coc.idCuerpo = " . $cursoCuerpo. ") consulta" ;

$resultEstadisticas = mysqli_query($link, $queryEstadisticas);

while ($row = mysqli_fetch_array($resultEstadisticas))
{
    $pregunta['erroresCursoOferta'] = $row['num'];
    break;
}
if ( is_null($pregunta['erroresCursoOferta'])) $pregunta['erroresCursoOferta'] = 0;

/**************************************************************************

Estadisticas SESION

***************************************************************************/

$pregunta['aciertosSession'] = $_SESSION['aciertosSession'] ;       // calculo total de la sesion
$pregunta['erroresSession'] = $_SESSION['erroresSession'] ;
$pregunta['sinContestarSession']= $_SESSION["sinContestarSession"];



echo json_encode($pregunta, JSON_INVALID_UTF8_SUBSTITUTE);

?>