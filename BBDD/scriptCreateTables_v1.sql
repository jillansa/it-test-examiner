create table tabExamen (
    ID_EXAMEN number,
    DES_EXAMEN varchar2,
	OFERTA varchar2,
	MODALIDAD varchar2,
	FECHA DATE,
	ADMINISTRACION varchar2
);

create table tabPreguntas (
    ID_PREGUNTA number,
    ID_EXAMEN number,
	TXT_PREGUNTA varchar2,

    
);

create table tabClasificaciones (
	ID_CLASIFICACION number, 
	DES_CLASIFICACION varchar2
	
);

create table tabPreguntasClasificaciones (
	ID_PREGUNTA number,
	ID_CLASIFICACION number
);

create table tabRespuestasExamen (
    PREGUNTA datatype,
    EXAMEN datatype,

    
);

create table tabUsuario (
    id number,
	DNI varchar(12),
	username varchar(50),
	password varchar(20),
	activo varchar(1),
	nombre
	email
	telefono
	direccion
	provincia
	municipio
	pais
	apellido1
	apellido2
);

create table tabRespuestasUsuario (
    PREGUNTA datatype,
    EXAMEN datatype,

    
);

create table tabNotasUsuario (
    PREGUNTA datatype,
    EXAMEN datatype,

    
);


