INSERT INTO `tabAdministracion`(`nombre`) 
select distinct administracion from cargaDatos where administracion not in (select nombre from tabAdministracion);


INSERT INTO `tabClasificacion`(`tema`)
select distinct Clasificacion from cargaDatos where Clasificacion not in (select tema from tabClasificacion);

  
INSERT INTO `tabOferta`(`descripcion`, `idAdministracion`, anio)  
select distinct CONCAT(oferta, " ",examen), '1', oferta from cargaDatos where oferta not in (select descripcion from tabOferta);