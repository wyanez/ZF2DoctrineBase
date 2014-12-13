<?php
namespace ZF2DoctrineBase\Entity;

/**
*  Define los metodos que debe tener una entidad del negocio persisitble en Base de Datos 
*  @author William Yanez - Julio 2013 
*/

interface Entity{    

   //Retorna la representacion del objeto como un array
   function getArrayCopy();

   //Retorna un array con los nombres de los campos
   function getArrayProperties();
   
}
?>