<?php
namespace ZF2DoctrineBase\Model;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

use ZF2DoctrineBase\Entity\Entity;

abstract class BaseDoctrineModel implements ObjectManagerAwareInterface{

    /**
    *   @var Doctrine\ORM\EntityManager
    */
    protected $entityManager;

    /**
    *   @var string
    */
    protected $repository;

    /**
    *   Arreglo de Mensajes con los errores de las Validaciones del modelo
    *   @var array
    */
    protected $arr_msg;
    
    public function __construct(){  
        $this->arr_msg = array();
        $this->repository = "";
    }

    //getter para los mensajes de error
    public function getErrorMessages(){
        return $this->arr_msg;  
    }

    public function listAll(){
        return $this->entityManager->getRepository($this->getRepository())->findAll();
    }

    public function listPaginate($page=1,$items_per_page=10,$fieldSort=null,$orderSort='asc'){
        $repository=$this->entityManager->getRepository($this->getRepository());
        if(!is_null($fieldSort)){
            $queryBuilder=$repository->createQueryBuilder('query')->orderBy('query.'.$fieldSort,$orderSort);
        }
        else $queryBuilder=$repository->createQueryBuilder('query');

        $adapter = new DoctrineAdapter(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($items_per_page);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    public function get($id){
        return $this->entityManager->find($this->getRepository(),$id);
    }

    public function save(Entity $entidad=null){
        $this->initErrorMessages($entidad);
        try {
            if($entidad !=null) $this->entityManager->persist($entidad);
            $this->entityManager->flush();
            $ok=true;
        } catch (\Doctrine\DBAL\DBALException $e) {
            $ok=false;
            $this->process_exception_save($e);
        }
        return $ok;
    }

    private function process_exception_save($e){
        if((strpos($e->getMessage(),"SQLSTATE[23000]")===false) &&
           (strpos($e->getMessage(),"SQLSTATE [23000, 2627]")===false)){
                $this->arr_msg['id'][]=$e->getMessage();
        }
        else{
            $msg = "ERROR AL INTENTAR GUARDAR : ".$e->getMessage();
            $field = 'id';
            //ToDo obtener el codigo en base a la PK
            if(!(strpos($e->getMessage(),"1062 Duplicate entry")===false)){
                $posdospuntos= strrpos($e->getMessage(),':');
                if(!($posdospuntos===false)){
                    $auxstr = substr($e->getMessage(),$posdospuntos+1);
                    preg_match_all("/\'[^']+\'/",$auxstr,$matches);                    
                    $field =str_replace("'", "", $matches[0][1]);
                    $value = $matches[0][0];
                    $msg="Error: Valor $value ya existe!";
                }
            }
            else if(!(strpos($e->getMessage(),"for key 'PRIMARY'")===false))
                 $msg = "ERROR: Registro ya Existe! ";
            $this->arr_msg[$field][] = $msg;
        }
    }

    public function remove(Entity $entidad){
        $this->initErrorMessages($entidad);
        try {
            $this->entityManager->remove($entidad);
            $this->entityManager->flush();          
            $ok=true;

        } catch (\Doctrine\DBAL\DBALException $e) {
            $ok=false;
            if(strpos($e->getMessage(),"SQLSTATE[23000]:")===false)
                $this->arr_msg['id'][]=$e->getMessage();        
            else
                $this->arr_msg['id'][]="ERROR: Registro NO se puede eliminar, tiene registros asociados!";
        }
        return $ok; 
    }

     protected function initErrorMessages(Entity $entidad=null){
        if($entidad !=null && count($this->arr_msg)==0){
            foreach ($entidad->getArrayProperties() as $field) {
                $this->arr_msg[$field]=array();
            }
        }
     }

     //Implementacion por defecto del metodo validate. Debe ser sobreescrito clases hijas
     public function validate(Entity $entidad){
        $this->initErrorMessages($entidad);
        return true;
     }

    //Retorna un string con el repositorio asociado de Doctrine
    public function getRepository(){
        return $this->repository;
    }

    //Metodos de la interface ObjectManagerAwareInterface para proveer el EntityManager de Doctrine
    public function getObjectManager(){
        return $this->entityManager;
    }

    public function setObjectManager(ObjectManager $em){
        $this->entityManager = $em;
    }
} 
?>