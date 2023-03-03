create procedure cargaExamen() 
begin
  

	--DECLARE 

	/* IF NOT EXISTS */
	INSERT INTO `tabOferta`(`id`, `descripcion`, `idAdministracion`, `modalidad`) 
	VALUES ('[value-1]','[value-2]','[value-3]','[value-4]');

	INSERT INTO `tabExamen`(`id`, `descripcion`, `idOferta`) 
	VALUES ('[value-1]','[value-2]','[value-3]');
	
	INSERT INTO `tabPreguntas` (`id`, `idExamen`, `texto`, `nivel`) 
	VALUES ('[value-1]','[value-2]','[value-3]','[value-4]');

	/* IF NOT EXISTS */
	INSERT INTO `tabClasificacion`(`id`, `tema`) 
	VALUES ('[value-1]','[value-2]')
	
	INSERT INTO `tabPreguntasClasificacion` (`id`, `idPregunta`, `idClasificacion`) 
	VALUES ('[value-1]','[value-2]','[value-3]');

	INSERT INTO `tabRespuestas`(`id`, `idPregunta`, `texto`, `correcta`) 
	VALUES ('[value-1]','[value-2]','[value-3]','[value-4]');

  
  if (2 < 3) then
      select 1;
  end if;
end;









-- Execute the procedure
call cargaExamen();

-- Drop the procedure
drop procedure cargaExamen;